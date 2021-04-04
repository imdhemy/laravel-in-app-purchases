# Contributing

Contributions are **welcome** and will be fully **credited**.
Please read and understand the contribution guide before creating an issue or pull request.

**Table Of Contents:**

  * [Procedure](#procedure)
    + [Bug report](#bug-report)
    + [Feature request.](#feature-request)
    + [Code contribution](#code-contribution)
    + [Requirements](#requirements)
    + [Submitting a pull request](#submitting-a-pull-request)
  * [Better world!](#better-world)
    + [Etiquette](#etiquette)
    + [Viability](#viability)
    
## Procedure

### Bug report
- Attempt to replicate the problem, to ensure that it wasn't a coincidental incident.
- Search the reported issues to make sure your bug isn't previously reported.
- Check the pull requests with [WIP] tag to ensure that the bug doesn't have a fix in progress.
- Use the bug_report template and fill all fields that applies on your bug.

### Feature request.
- Check to make sure your feature suggestion isn't already present within the project.
- Check the pull requests with [WIP] tag to ensure that the feature isn't already in progress.
- Use the feature_request template and fill all fields that applies on your bug.

### Code contribution
- Check the open issues for issues related to your enhancement.
- If there is no feature or bug issue related to your new idea, either start an issue or a discussion describing it.
- Submit a pull request immediately even before completing your code **but**, add the [WIP] tag to the title. 
- If you want to contribute, but don't know where to start? check the project board and grab your next task.

### Requirements
Requirements related to code the code contribution listed below:

- [PSR-2 coding standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md). Use `composer format` to apply conventions.
- **Add tests!** - Your patch won't be accepted if it does not have tests.
- **Document any change in behaviour** - Make sure the `README.md` and any other  other relevant documentation are kept up-to-date.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](https://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

### Submitting a pull request

**Work in progress:**

Once you start yo write code and created a feature branch submit a pull request with the following guidelines:

- Base your branch and the PR from the `develop` branch.
- Add `[WIP]` tag to the pull request title. Example: `[WIP] Feature: Allow the dolphin to dance in the sky.`
- Use the pull request template and fill the **What** section for now.

**Work is finish:**

- Ensure your code is fulfilling the [requirements](#requirements).
- Ensure your code has no errors using the static analysis tool. `composer psalm`.
- Update the created pull request to remove the [WIP] tag and fill all the pull request fields.
- Base your branch and the PR from the `develop` branch.

## Better world!

### Etiquette

This project is open-source, and as such, the maintainers give their free time to build and maintain the source code
held within. They make the code freely available in the hope that it will be of use to other developers. It would be
extremely unfair for them to suffer abuse or anger for their hard work.

Please be considerate towards maintainers when raising issues or presenting pull requests. Let's show the
world that developers are civilized and selfless people.

It's the duty of the maintainer to ensure that all submissions to the project are of sufficient
quality to benefit the project. Many developers have different skillsets, strengths, and weaknesses. Respect the maintainer's decision, and do not be upset or abusive if your submission is not used.

### Viability

When requesting or submitting new features, first consider whether it might be useful to others. Open
source projects are used by many developers, who may have entirely different needs to your own. Think about
whether or not your feature is likely to be used by other users of the project.

**Happy coding**!
