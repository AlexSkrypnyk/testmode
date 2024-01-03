<p align="center">
  <a href="" rel="noopener">
  <img width=200px height=200px src="https://placehold.jp/000000/ffffff/200x200.png?text=Testmode&css=%7B%22border-radius%22%3A%22%20100px%22%7D" alt="Test mode"></a>
</p>

<h1 align="center">Testmode</h1>

<div align="center">

[![GitHub Issues](https://img.shields.io/github/issues/AlexSkrypnyk/testmode.svg)](https://github.com/AlexSkrypnyk/testmode/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/AlexSkrypnyk/testmode.svg)](https://github.com/AlexSkrypnyk/testmode/pulls)
[![CircleCI](https://circleci.com/gh/AlexSkrypnyk/testmode.svg?style=shield)](https://circleci.com/gh/AlexSkrypnyk/testmode)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/AlexSkrypnyk/testmode)
![LICENSE](https://img.shields.io/github/license/AlexSkrypnyk/testmode)
![Renovate](https://img.shields.io/badge/renovate-enabled-green?logo=renovatebot)

</div>

---

<p align="center">This Drupal module is designed to modify existing site content and configurations while running tests.</p>

## Features
This is a module to support testing, so it is not expected to be used in
production (although, it adheres to Drupal coding standards and has good test
coverage).

## Installation

    composer require drupal/testmode

## Usage / Use case
Running a Behat test on the site with existing content may result in
FALSE positives because of the live content being mixed with test content.

Example: list of 3 featured articles. When the test creates 3 articles and make
them featured, there may be existing featured articles that will confuse tests
resulting in false positive failure.

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
