function formatMiddlewareMessage(middlewareResultText) {
  const middlewareResult = JSON.parse(middlewareResultText);

  var output = "# Middleware\n\n";

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
    output += `| ${route.name} | ${route.route} |`;
    for (const user of middlewareResult.users) {
      const mark = route.users[user.id].allowed ? ":white_check_mark:" : ":x:";
      output += ` ${mark} |`;
    }
    output += "\n";
  }

  return output;
}

module.exports = async ({ github, context }) => {
  const { RESULTS: middlewareResult } = process.env;
  const middlwareMessage = formatMiddlewareMessage(middlewareResult);
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
};
