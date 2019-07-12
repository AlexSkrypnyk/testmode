#!/usr/bin/env bash
##
# Run tests.
#
set -e

mkdir -p /tmp/artifacts

echo "==> Lint code"
composer lint
