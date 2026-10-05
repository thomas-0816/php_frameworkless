// @ts-check

import puppeteer from 'puppeteer-core';
import { formatOutput, lightHouse } from './utils/lighthouse.js';

const browser = await puppeteer.launch({
    headless: true,
    executablePath: '/usr/local/sbin/chrome',
    args: ['--no-sandbox', '--disable-setuid-sandbox'],
    acceptInsecureCerts: true,
});
const context = browser.defaultBrowserContext();
const tasksUrl = 'https://nginx/tasks/';
const loginUrl = 'http://nginx:8080/v1/customers/login';

// lighthouse must run on a fresh page, reusing a page yields a broken trace
// (missing network requests) and makes the trace engine throw

await (async () => {
    await context.setCookie({ domain: 'nginx', name: 'token', value: '' });

    const desktop = await lightHouse(await context.newPage(), tasksUrl, './lh_login_desktop.html', false);
    const mobile = await lightHouse(await context.newPage(), tasksUrl, './lh_login_mobile.html', true);

    console.info('Login (desktop, mobile)');
    console.info(formatOutput(desktop, mobile).join('\n'), '\n');
})();

await (async () => {
    const body = JSON.stringify({ email: 'foo@bar.baz', password: 'insecure' });
    const login = await fetch(loginUrl, { method: 'POST', body: body });
    const token = String((await login.json()).token);
    await context.setCookie({ domain: 'nginx', name: 'token', value: token });

    const desktop = await lightHouse(await context.newPage(), tasksUrl, './lh_list_desktop.html', false);
    const mobile = await lightHouse(await context.newPage(), tasksUrl, './lh_list_mobile.html', true);

    console.info('Task List (desktop, mobile)');
    console.info(formatOutput(desktop, mobile).join('\n'), '\n');
})();

await browser.close();