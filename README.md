<div align="center">
  <a href="" rel="noopener">
  <img width=200px height=200px src="https://placehold.jp/000000/ffffff/200x200.png?text=Testmode&css=%7B%22border-radius%22%3A%22%20100px%22%7D" alt="Testmode logo"></a>
</div>

<h1 align="center">Drupal module to modify existing site content and configurations while running tests.</h1>

<div align="center">

[![GitHub Issues](https://img.shields.io/github/issues/AlexSkrypnyk/testmode.svg)](https://github.com/AlexSkrypnyk/testmode/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/AlexSkrypnyk/testmode.svg)](https://github.com/AlexSkrypnyk/testmode/pulls)
[![Test](https://github.com/AlexSkrypnyk/testmode/actions/workflows/test.yml/badge.svg)](https://github.com/AlexSkrypnyk/testmode/actions/workflows/test.yml)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/AlexSkrypnyk/testmode)
![LICENSE](https://img.shields.io/github/license/AlexSkrypnyk/testmode)
![Renovate](https://img.shields.io/badge/renovate-enabled-green?logo=renovatebot)

![PHP 8.2](https://img.shields.io/badge/PHP-8.2-777BB4.svg)
![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4.svg)
![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777BB4.svg)
![Drupal 10](https://img.shields.io/badge/Drupal-10-009CDE.svg)
![Drupal 11](https://img.shields.io/badge/Drupal-11-006AA9.svg)

</div>

---

This is a module to support testing, so it is not expected to be used in production (although, it adheres to Drupal coding standards and has good test coverage).

## Installation

```shell
composer require --dev drupal/testmode
```

## Use case

Running a Behat test on the site with existing content may result in
false-positives because of the live content being mixed with the test content.

Example: list of 3 featured articles. When the test creates 3 articles and makes
them featured, there may be existing featured articles that will confuse tests
resulting in a false-positive failure.

## How it works
1. When writing Behat tests, all test content items (nodes,
   terms, users) follow specific pattern. For example, node titles start with
   `[TEST] `.
2. A machine name of a view, which needs to be tested, is added to
   Testmode configuration form.
3. Behat test tagged with `@testmode` will put
   the site in test mode that will filter-out all items in the view that do not
   fit the pattern, leaving only content items created by the test.

## Maintenance / Development
Releases in GitHub are automatically pushed to http://drupal.org/project/testmode by CI.

## Issues
https://www.drupal.org/project/issues/testmode

## Local development

1. Install PHP with SQLite support and Composer
3. Clone this repository
4. Run `ahoy build`

## Building website

`ahoy build` assembles the codebase, starts the PHP server
and provisions the Drupal website with this extension enabled. These operations
are executed using scripts within [`.devtools`](.devtools) directory. CI uses
the same scripts to build and test this extension.

The resulting codebase is then placed in the `build` directory. The extension
files are symlinked into the Drupal site structure.

The `build` command is a wrapper for more granular commands:
```bash
ahoy assemble     # Assemble the codebase
ahoy start        # Start the PHP server
ahoy provision    # Provision the Drupal website
```

The `provision` command is useful for re-installing the Drupal website without
re-assembling the codebase.

### Drupal versions

The Drupal version used for the codebase assembly is determined by the
`DRUPAL_VERSION` variable and defaults to the latest stable version.

You can specify a different version by setting the `DRUPAL_VERSION` environment
variable before running the `ahoy build` command:

```bash
DRUPAL_VERSION=11 ahoy build        # Drupal 11
DRUPAL_VERSION=11@alpha ahoy build  # Drupal 11 alpha
DRUPAL_VERSION=10@beta ahoy build   # Drupal 10 beta
DRUPAL_VERSION=11.1 ahoy build      # Drupal 11.1
```

The `minimum-stability` setting in the `composer.json` file is
automatically adjusted to match the specified Drupal version's stability.

### Using Drupal project fork

If you want to use a custom fork of `drupal-composer/drupal-project`, set the
`DRUPAL_PROJECT_REPO` environment variable before running the `ahoy build`
command:

```bash
DRUPAL_PROJECT_REPO=https://github.com/me/drupal-project-fork.git ahoy build
```

### Patching dependencies

To apply patches to the dependencies, add a patch to the `patches` section of
`composer.json`. Local patches are be sourced from the `patches` directory.

### Providing `GITHUB_TOKEN`

To overcome GitHub API rate limits, you may provide a `GITHUB_TOKEN` environment
variable with a personal access token.

### Provisioning the website

The `provision` command installs the Drupal website from the `standard`
profile with the extension (and any `suggest`'ed extensions) enabled. The
profile can be changed by setting the `DRUPAL_PROFILE` environment variable.

The website will be available at http://localhost:8000. The hostname and port
can be changed by setting the `WEBSERVER_HOST` and `WEBSERVER_PORT` environment
variables.

An SQLite database is created in `/tmp/site_testmode.sqlite` file.
You can browse the contents of the created SQLite database using
[DB Browser for SQLite](https://sqlitebrowser.org/).

A one-time login link will be printed to the console.

## Coding standards

The `ahoy lint` command checks the codebase using multiple tools:
- PHP code standards checking against `Drupal` and `DrupalPractice` standards.
- PHP code static analysis with PHPStan.
- PHP deprecated code analysis and auto-fixing with Drupal Rector.
- Twig code analysis with Twig CS Fixer.

The configuration files for these tools are located in the root of the codebase.

### Fixing coding standards issues

To fix coding standards issues automatically, run `ahoy lint-fix`. This runs
the same tools as `lint` command but with the `--fix` option (for the tools
that support it).

## Testing

The `ahoy test` command runs the PHPUnit tests for this extension.

The tests are located in the `tests/src` directory. The `phpunit.xml` file
configures PHPUnit to run the tests. It uses Drupal core's bootstrap file
`core/tests/bootstrap.php` to bootstrap the Drupal environment before running
the tests.

The `test` command is a wrapper for multiple test commands:
```bash
ahoy test-unit        # Run Unit tests
ahoy test-kernel      # Run Kernel tests
ahoy test-functional  # Run Functional tests
```

### Running specific tests

You can run specific tests by passing a path to the test file or PHPUnit CLI
option (`--filter`, `--group`, etc.) to the `ahoy test` command:

```bash
ahoy test-unit tests/src/Unit/MyUnitTest.php
ahoy test-unit -- --group=wip
```

You may also run tests using the `phpunit` command directly:

```bash
cd build
php -d pcov.directory=.. vendor/bin/phpunit tests/src/Unit/MyUnitTest.php
php -d pcov.directory=.. vendor/bin/phpunit --group=wip
```

---
_This repository was created using the [Drupal Extension Scaffold](https://github.com/AlexSkrypnyk/drupal_extension_scaffold) project template_
