# How to contribute

MushRaider loves to welcome your contributions. There are several ways to help out:
* Create a ticket in GitHub, if you have found a bug
* Write patches for open bug/feature tickets
* Contribute to the core system

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free)
* Submit a ticket for your issue, assuming one does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug.
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository on GitHub.

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the master branch
  * Only target release branches if you are certain your fix must be on that
    branch
  * To quickly create a topic branch based on master; `git branch
    master/my_contribution master` then checkout the new branch with `git
    checkout master/my_contribution`. Better avoid working directly on the
    `master` branch, to avoid conflicts if you pull in updates from origin.
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Use descriptive commit messages and reference the #ticket number
* Your work should apply the MushRaider coding standards.

## Which branch to base the work

* Bugfix branches will be based on master.
* New features that are backwards compatible will be based on next minor release
  branch.
* New features or other non-BC changes will go in the next major release branch.

## Submitting Changes

* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the mushraider repository, with the
  correct target branch.

# Additional Resources

* [MushRaider website](http://mushraider.com)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)
