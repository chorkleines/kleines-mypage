const puppeteer = require("puppeteer");
const fs = require("fs");

async function checkUrl(url, email, password) {
  const browser = await puppeteer.launch({
    headless: "new",
  });
  const page = await browser.newPage();

  async function login(email, password) {
    await page.goto("http://localhost:3000");
    console.log(page.url());
    await page.waitForNavigation({ waitUntil: "networkidle0" });
    await page.type("#email", email);
    await page.type("#password", password);
    await page.click("#login");
    console.log(page.url());
    await page.waitForSelector("#login:not(.btn-disabled)");
  }

  console.log(email);
  await login(email, password);
  await page.goto(url);
  console.log(page.url());
  await page.waitForNavigation({ waitUntil: "networkidle0" });
  console.log(page.url());
  const redirectUrl = page.url();
  await browser.close();
  return redirectUrl;
}

async function getResults() {
  const middlewareList = JSON.parse(
    fs.readFileSync("./.github/workflows/middleware.json", "utf8"),
  );

  const results = {};
  results.users = middlewareList.users;
  results.routes = [];
  for (const route of middlewareList.routes) {
    const result = {};
    result.name = route.name;
    result.route = route.uri;
    result.users = {};
    for (const user of middlewareList.users) {
      const url = `http://localhost:3000${route.uri}`;
      const expectedUrl = `http://localhost:3000${route.expected[user.id]}`;
      const actualUrl = await checkUrl(url, user.email, user.password);
      result.users[user.id] = {
        status: actualUrl === expectedUrl ? "success" : "fail",
        allowed: url === actualUrl,
        url,
        actualUrl,
        expectedUrl,
      };
    }
    results.routes.push(result);
  }
  return results;
}

(async () => {
  const results = await getResults();
  console.log(JSON.stringify(results));
})();