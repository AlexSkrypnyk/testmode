<?php

/**
 * @file
 * Mental Health Online Drupal context for Behat testing.
 *
 * Disable classname rule as this file is Drupal-agnostic.
 * @phpcs:disable DrupalPractice.General.ClassName.ClassPrefix
 */

use Drupal\DrupalExtension\Context\DrupalContext;
use Drupal\testmode\Tests\TestmodeTrait;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  use TestmodeTrait;

}
