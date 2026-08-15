<div align="center">
  <a href="" rel="noopener">
  <img width=200px height=200px src="logo.png" alt="Testmode logo"></a>
</div>

<h1 align="center">Drupal module to modify existing site content and configurations while running tests.</h1>

<div align="center">

[![GitHub Issues](https://img.shields.io/github/issues/AlexSkrypnyk/testmode.svg)](https://github.com/AlexSkrypnyk/testmode/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/AlexSkrypnyk/testmode.svg)](https://github.com/AlexSkrypnyk/testmode/pulls)
[![Test](https://github.com/AlexSkrypnyk/testmode/actions/workflows/test.yml/badge.svg)](https://github.com/AlexSkrypnyk/testmode/actions/workflows/test.yml)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/AlexSkrypnyk/testmode)
![LICENSE](https://img.shields.io/github/license/AlexSkrypnyk/testmode)
![Renovate](https://img.shields.io/badge/renovate-enabled-green?logo=renovatebot)

![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4.svg)
![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777BB4.svg)
![PHP 8.5](https://img.shields.io/badge/PHP-8.5-777BB4.svg)
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

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for local development, building the
site, checking coding standards, and running the tests.

---
_This repository was created using the [Drupal Extension Scaffold](https://github.com/AlexSkrypnyk/drupal_extension_scaffold) project template_
