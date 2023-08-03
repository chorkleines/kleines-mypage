const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        headless: "new",
        args: ["--no-sandbox", "--disable-setuid-sandbox"],
    });
    const page = await browser.newPage();

    const response = await page.goto("http://localhost:3000");

    // Set screen size
    await page.setViewport({ width: 1080, height: 1024 });

    for (const req of response.request().redirectChain()) {
        console.log(`${req.url()} => ${req.response().headers().location}`);
    }

    await browser.close();
})();
