# Testmate 
Drupal 8 module used to alter existing site content and other
configurations when running tests.

[![CircleCI](https://circleci.com/gh/integratedexperts/testmate.svg?style=shield)](https://circleci.com/gh/integratedexperts/testmate)

## Usecase 
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
   Testmate configuration form. 
3. Behat test tagged with `@testmode` will put
   the site in test mode that will filter-out all items in the view that do not
   fit the pattern, leaving only content items created by the test.

## Development 
Releases in GitHub are automatically pushed to drupal.org by CI.
