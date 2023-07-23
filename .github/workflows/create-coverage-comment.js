function format_coverage_message(coverage) {
    return `# Coverage\n\n${coverage}`;
}

module.exports = async ({ github, context }) => {
    const { COVERAGE } = process.env;
    const coverage_message = format_coverage_message(COVERAGE);
    const comments = await github.rest.issues.listComments({
        owner: context.repo.owner,
        repo: context.repo.repo,
        issue_number: context.issue.number,
    });
    const comment = comments.data.find((c) => c.body.startsWith("# Coverage"));
    if (comment === null || comment === undefined) {
        await github.rest.issues.createComment({
            owner: context.repo.owner,
            repo: context.repo.repo,
            issue_number: context.issue.number,
            body: coverage_message,
        });
    } else {
        await github.rest.issues.updateComment({
            owner: context.repo.owner,
            repo: context.repo.repo,
            body: coverage_message,
            comment_id: comment.id,
        });
    }
};
