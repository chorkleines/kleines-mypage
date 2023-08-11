function formatMiddlewareMessage(middlewareResultText) {
  const middlewareResult = JSON.parse(middlewareResultText);

  var output = "# Middleware\n\n";
  var error = "";

  output += "| | Route |";
  for (const user of middlewareResult.users) {
    output += ` ${user.name} |`;
  }
  output += "\n";
  output += "| --- | --- |";
  for (const _user of middlewareResult.users) {
    output += ` :---: |`;
  }
  output += "\n";
  for (const route of middlewareResult.routes) {
    output += `| ${route.name} | ${route.uri} |`;
    for (const user of middlewareResult.users) {
      const mark = middlewareResult.results[user.id][route.uri].allowed
        ? "✅"
        : "❌";
      output += ` ${mark} |`;
      if (middlewareResult.results[user.id][route.uri].status === "fail") {
        error += `* Route \`${
          middlewareResult.results[user.id][route.uri].expectedUri
        }\` expected for user \`${user.id}\` when accessing \`${
          middlewareResult.results[user.id][route.uri].uri
        }\` but got \`${
          middlewareResult.results[user.id][route.uri].actualUri
        }\`\n`;
      }
    }
    output += "\n";
  }

  if (error !== "") {
    output += "\n";
    output += error;
  }

  return { output, error: error !== "" };
}

module.exports = async ({ github, context }) => {
  const { RESULTS: middlewareResult } = process.env;
  const { output: middlwareMessage, error } =
    formatMiddlewareMessage(middlewareResult);
  const comments = await github.rest.issues.listComments({
    owner: context.repo.owner,
    repo: context.repo.repo,
    issue_number: context.issue.number,
  });
  const comment = comments.data.find((c) => c.body.startsWith("# Middleware"));
  if (comment === null || comment === undefined) {
    await github.rest.issues.createComment({
      owner: context.repo.owner,
      repo: context.repo.repo,
      issue_number: context.issue.number,
      body: middlwareMessage,
    });
  } else {
    await github.rest.issues.updateComment({
      owner: context.repo.owner,
      repo: context.repo.repo,
      body: middlwareMessage,
      comment_id: comment.id,
    });
  }
  if (error) {
    throw new Error("Middleware tests failed");
  }
};
