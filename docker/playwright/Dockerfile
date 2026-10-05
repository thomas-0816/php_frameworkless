# keep in sync with tasks_fe/tests/package-lock.json (playwright + puppeteer-core)
FROM mcr.microsoft.com/playwright:v1.63.0-noble

# default playwright image does not contain ms-edge
RUN npx -y playwright@1.63.0 install msedge \
    && apt-get clean \
    && rm -rf /tmp/* /var/lib/apt/lists/* /var/cache/apt/archives/* ~/.npm/ \
    && ln -s "$(ls -d /ms-playwright/chromium-*/chrome-linux*/chrome | head -n1)" /usr/local/sbin/chrome \
    && /usr/local/sbin/chrome --version

USER pwuser:pwuser

HEALTHCHECK none

ENV HOME=/tmp
