import { chromium } from 'playwright';

const browser = await chromium.launch();
const page = await browser.newPage();
await page.setViewportSize({ width: 1440, height: 900 });

// Załaduj stronę i poczekaj na formularz logowania
await page.goto('http://localhost:5173');
await page.waitForSelector('input[name="username"]', { timeout: 20000 });

// Zaloguj
await page.fill('input[name="username"]', 'admin');
await page.fill('input[name="password"]', 't4jn3h4slo');
await page.click('.mint-button-primary');

// Poczekaj na pełne załadowanie po przeładowaniu strony
await page.waitForSelector('nav', { timeout: 30000 });
await page.waitForTimeout(5000);

// Przejdź do kandydatów przez hash
await page.evaluate(() => { window.location.hash = '#/Candidates'; });
await page.waitForTimeout(10000);

// Sprawdź czy jest iframe legacy view
const iframeEl = await page.$('iframe');
if (iframeEl) {
    console.log('Znaleziono iframe — legacy view');
    const iframe = await iframeEl.contentFrame();
    await iframe?.waitForLoadState('load');
    await page.screenshot({ path: '/var/www/minthcm/screenshot-candidates.png', fullPage: false });
} else {
    console.log('Brak iframe — Vue view');
    await page.screenshot({ path: '/var/www/minthcm/screenshot-candidates.png', fullPage: false });
}

// Wylistuj widoczne elementy głównej treści
const mainText = await page.evaluate(() => document.querySelector('main, [class*="content"], [class*="view"]')?.innerText?.substring(0, 200));
console.log('Treść główna:', mainText);

await browser.close();
console.log('Done');
