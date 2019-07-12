#!/usr/bin/env bash
##
# Build.
#
set -e

echo "==> Validate composer"
composer validate --ansi --strict

echo "==> Install dependencies"
composer install --ansi
