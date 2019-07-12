<?php

/**
 * @file
 * Mental Health Online Drupal context for Behat testing.
 */

use Drupal\DrupalExtension\Context\DrupalContext;
use Drupal\testmate\Tests\TestmodeTrait;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  use TestmodeTrait;

}
