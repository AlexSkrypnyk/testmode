<?php

namespace Drupal\Tests\testmode\Functional;

use Drupal\testmode\Testmode;
use Drupal\Tests\views\Functional\ViewTestBase;
use Drupal\views\Tests\ViewTestData;

/**
 * Base class for all Testmode Views tests.
 */
abstract class TestmodeTestBase extends ViewTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public static $modules = ['testmode', 'testmode_test'];

  /**
   * Instance of the Testmode class.
   *
   * @var \Drupal\testmode\Testmode
   */
  protected $testmode;

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE) {
    parent::setUp($import_test_views);

    if ($import_test_views) {
      $this->drupalCreateContentType(['type' => 'article']);

      ViewTestData::createTestViews(get_class($this), ['testmode_test']);
    }

    $this->testmode = Testmode::getInstance();
  }

  /**
   * Helper to login as Admin user.
   */
  protected function drupalLoginAdmin() {
    $user = $this->createUser([], NULL, TRUE);
    $this->drupalLogin($user);
  }

}
