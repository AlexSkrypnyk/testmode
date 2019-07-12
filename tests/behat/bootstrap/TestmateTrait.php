<?php

namespace Drupal\testmate\Tests;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Drupal\testmate\Testmate;

/**
 * Trait TestmodeTrait.
 *
 * Include this trait in your FeatureContex.php file to enable Testamate test
 * mode for tests tagged with 'testmode'.
 */
trait TestmodeTrait {

  /**
   * Enable test mode before test run for scenarios tagged with @testmode.
   *
   * @BeforeScenario
   */
  public function testmodeBeforeScenarioEnableTestMode(BeforeScenarioScope $scope) {
    if ($scope->getScenario()->hasTag('testmode')) {
      self::testmodeEnableTestMode();
    }
  }

  /**
   * Disable test mode before test run for scenarios tagged with @testmode.
   *
   * @AfterScenario
   */
  public function testmodeBeforeScenarioDisableTestMode(AfterScenarioScope $scope) {
    if ($scope->getScenario()->hasTag('testmode')) {
      self::testmodeDisableTestMode();
    }
  }

  /**
   * Enable test mode.
   */
  protected static function testmodeEnableTestMode() {
    return Testmate::getInstance()->enableTestMode();
  }

  /**
   * Disable test mode.
   */
  protected static function testmodeDisableTestMode() {
    return Testmate::getInstance()->disableTestMode();
  }

}
