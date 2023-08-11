const puppeteer = require("puppeteer");
const fs = require("fs");

async function testUrl(page, url) {
  await page.goto(url);
  await page.waitForNavigation({ waitUntil: "networkidle0" });
  const redirectUrl = page.url();
  return redirectUrl;
}

async function testUser(user, routes) {
  const browser = await puppeteer.launch({
    headless: "new",
  });
  const page = await browser.newPage();

  async function login(email, password) {
    await page.goto("http://localhost:3000");
    await page.waitForNavigation({ waitUntil: "networkidle0" });
    await page.type("#email", email);
    await page.type("#password", password);
    await page.click("#login");
    await page.waitForSelector("#login:not(.btn-disabled)");
  }

  await login(user.email, user.password);

  const results = {};
  for (const route of routes) {
    const url = `http://localhost:3000${route.uri}`;
    const expectedUrl = `http://localhost:3000${route.expected[user.id]}`;
    console.error(`Checking ${route.uri} for ${user.id}`);
    const actualUrl = await testUrl(page, url);
    results[route.uri] = {
      name: route.name,
      route: route.uri,
      status: actualUrl === expectedUrl ? "success" : "fail",
      allowed: url === actualUrl,
      actualUri: actualUrl.replace("http://localhost:3000", ""),
      expectedUri: expectedUrl.replace("http://localhost:3000", ""),
    };
  }

  await browser.close();
  return results;
}

async function getResults() {
  const middlewareList = JSON.parse(
    fs.readFileSync("./.github/workflows/middleware.json", "utf8"),
  );

  const results = {};
  results.users = middlewareList.users;
  results.routes = middlewareList.routes;
  results.results = {};
  for (const user of middlewareList.users) {
    const result = await testUser(user, middlewareList.routes);
    results.results[user.id] = result;
  }
  return results;
}

(async () => {
  const results = await getResults();
  console.log(JSON.stringify(results));
})();
