function format_coverage_message(coverageOutput) {
    const outputLines = coverageOutput.split("\n");

    const coverageLines = outputLines.filter((value) =>
        value.match(/\/.+\s\.+\s\d+\.\d\s%/),
    );
    const totalCoverageLine = outputLines.find((value) =>
        value.match(/Total\sCoverage\s\.+\s\d+\.\d\s%/),
    );

    var output = "# Coverage\n\n";
    output += "<details>\n";
    output += "<summary>Coverage for all files</summary>\n\n";
    output += "| Path | Percentage |\n";
    output += "| ---- | ---------- |\n";
    for (const coverageLine of coverageLines) {
        const coverageContents = coverageLine.split(" ");
        const path = coverageContents[1];
        const percentage = coverageContents.slice(-3, -1).join(" ");
        output += `| ${path} | ${percentage} |\n`;
    }
    output += "</details>\n\n";

    const totalCoverageContents = totalCoverageLine.split(" ");
    const totalPercentage = totalCoverageContents.slice(-3, -1).join(" ");
    output += "|                | Percentage |\n";
    output += "| -------------- | ---------- |\n";
    output += `| Total Coverage | ${totalPercentage} |\n`;

    return output;
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
