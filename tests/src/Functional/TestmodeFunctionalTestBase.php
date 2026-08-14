<?php

declare(strict_types=1);

namespace Drupal\Tests\testmode\Functional;

use Drupal\Tests\views\Functional\ViewTestBase;
use Drupal\testmode\Testmode;
use Drupal\user\Entity\User;
use Drupal\views\Tests\ViewTestData;

/**
 * Base class for all Testmode Views tests.
 */
abstract class TestmodeFunctionalTestBase extends ViewTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   *
   * @var string[]
   */
  protected static $modules = ['testmode', 'testmode_test'];

  /**
   * Instance of the Testmode class.
   *
   * @var \Drupal\testmode\Testmode
   */
  protected $testmode;

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE, $modules = ['views_test_config']): void {
    parent::setUp($import_test_views);

    if ($import_test_views) {
      $this->drupalCreateContentType(['type' => 'article']);

      ViewTestData::createTestViews(static::class, ['testmode_test']);
    }

    $this->testmode = Testmode::getInstance();
  }

  /**
   * Creates a user account.
   *
   * @param string[] $permissions
   *   Permissions to assign to the account.
   *
   * @return \Drupal\user\Entity\User
   *   The created account.
   */
  protected function createAccount(array $permissions = []): User {
    // Drupal 10 declares a 'User|false' return type for account creation.
    $account = $this->drupalCreateUser($permissions);

    if (!$account instanceof User) {
      throw new \RuntimeException('Unable to create a user account.');
    }

    return $account;
  }

  /**
   * Helper to login as Admin user.
   */
  protected function drupalLoginAdmin(): void {
    $user = $this->createUser([], NULL, TRUE);
    // @phpstan-ignore-next-line
    $this->drupalLogin($user);
  }

  /**
   * Checks that current response header contains a value.
   */
  public function responseHeaderContains(string $name, string $value): void {
    // @phpstan-ignore-next-line
    $actual = $this->session->getResponseHeader($name);
    $message = sprintf('Current response header "%s" contains "%s", but "%s" expected.', $name, $actual, $value);

    $this->assertContains($value, $actual, $message);
  }

}
