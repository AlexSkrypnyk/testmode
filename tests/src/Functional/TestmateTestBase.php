<?php

namespace Drupal\Tests\testmate\Functional;

use Drupal\testmate\Testmate;
use Drupal\Tests\views\Functional\ViewTestBase;
use Drupal\views\Tests\ViewTestData;

/**
 * Base class for all Testmate Views tests.
 */
abstract class TestmateTestBase extends ViewTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['testmate', 'testmate_test'];

  /**
   * Instance of the Testmate class.
   *
   * @var \Drupal\testmate\Testmate
   */
  protected $testmate;

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE) {
    parent::setUp($import_test_views);

    if ($import_test_views) {
      $this->drupalCreateContentType(['type' => 'article']);

      ViewTestData::createTestViews(get_class($this), ['testmate_test']);
    }

    $this->testmate = Testmate::getInstance();
  }

  /**
   * Helper to login as Admin user.
   */
  protected function drupalLoginAdmin() {
    $user = $this->createUser([], NULL, TRUE);
    $this->drupalLogin($user);
  }

}
