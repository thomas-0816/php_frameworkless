// @ts-check

import { builtinEnvironments } from 'vitest/runtime';

/**
 * @param {string} html
 * @param {string} script
 * @param {string} url
 * @param {string} cookie
 * @param {(url: URL | RequestInfo, options?: RequestInit) => Promise<Response>} fetch
 * @returns {Promise<{teardown: function}>}
 */
export async function render(html, script, url, cookie, fetch) {
    const options = { html: html, runScripts: 'outside-only', url: url, pretendToBeVisual: true };
    const env = await builtinEnvironments.jsdom.setup(global, { jsdom: options });

    window.fetch = fetch;
    window.confirm = () => true;
    window.console.warn = () => {};
    window.document.cookie = `${cookie}; Max-Age=60; path=/; SameSite=Strict`;
    window.performance.memory = { totalJSHeapSize: 10485760 * 2, usedJSHeapSize: 10485760 };
    /** @type {Window} */ (window).setInterval = (/** @type {() => void} */ handler) => {
        setTimeout(handler, 10);
        return 0;
    };

    // use system loader for code coverage, add timestamp to re-load module
    await import(new URL(`${script}?${Date.now()}`, import.meta.url).href);

    return {
        teardown: async () => {
            // wait for window.setInterval
            await new Promise((resolve) => setTimeout(resolve, 10));
            await env.teardown(global);
        },
    };
}

/**
 * @param {string} tag
 * @param {string} text
 * @returns {HTMLElement?}
 */
export function getElementByText(tag, text) {
    const xpath = `//${tag}[text()='${text}']`;
    const matchingElement = document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
    if (matchingElement.singleNodeValue instanceof HTMLElement) {
        return matchingElement.singleNodeValue;
    }
    return null;
}

/**
 * @param {string} name
 * @param {string} value
 */
export function fillInputByName(name, value) {
    const element = document.getElementsByName(name)[0];
    if (element instanceof HTMLInputElement) {
        element.value = value;
    }
}

/**
 * @param {string} text
 * @returns {Promise<string>}
 */
export async function waitForText(text) {
    return await new Promise((resolve) => {
        const interval = setInterval(() => {
            const html = document.documentElement.outerHTML;
            if (html.includes(text)) {
                clearInterval(interval);
                resolve(html);
            }
        }, 100);
    });
}
