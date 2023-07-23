module.exports = async ({ github, context }) => {
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
            body: process.env.COVERAGE,
        });
    } else {
        await github.rest.issues.updateComment({
            owner: context.repo.owner,
            repo: context.repo.repo,
            body: process.env.MESSAGE,
            comment_id: comment.id,
        });
    }
};
