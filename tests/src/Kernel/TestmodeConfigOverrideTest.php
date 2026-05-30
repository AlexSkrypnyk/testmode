<?php

declare(strict_types=1);

namespace Drupal\Tests\testmode\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\testmode\Testmode;

/**
 * Tests that Testmode respects $config[...] overrides from settings.php.
 *
 * See https://github.com/AlexSkrypnyk/testmode/issues/46.
 *
 * @group Testmode
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class TestmodeConfigOverrideTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['testmode'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['testmode']);
    // Reset the Testmode singleton so each test gets a fresh instance that
    // picks up the current container's config factory.
    $reflection = new \ReflectionClass(Testmode::class);
    $instance = $reflection->getProperty('instance');
    $instance->setAccessible(TRUE);
    $instance->setValue(NULL, NULL);
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown(): void {
    // Remove any $config override installed by a test so the global state
    // does not leak into other kernel tests sharing the same PHP process.
    unset($GLOBALS['config']['testmode.settings']);
    if (\Drupal::hasContainer()) {
      \Drupal::configFactory()->reset('testmode.settings');
    }
    parent::tearDown();
  }

  /**
   * Tests that a getter reflects $config[...] overrides.
   *
   * @param string $setter
   *   Setter method name on Testmode to write the stored value.
   * @param string $getter
   *   Getter method name on Testmode to read the effective value.
   * @param string $config_key
   *   The key under 'testmode.settings' that backs the getter.
   * @param mixed $stored_value
   *   Value written via the setter (becomes the raw stored value).
   * @param mixed $override_value
   *   Value installed as the $GLOBALS['config'] override.
   * @param mixed $expected
   *   Value expected to be returned by the getter once the override is set.
   *
   * @dataProvider dataProviderGetterReflectsOverride
   */
  public function testGetterReflectsOverride(string $setter, string $getter, string $config_key, mixed $stored_value, mixed $override_value, mixed $expected): void {
    $testmode = Testmode::getInstance();
    $testmode->{$setter}($stored_value);

    // Sanity check: without an override the getter reflects the stored value.
    $this->assertSame($stored_value, $testmode->{$getter}(), sprintf('Stored value is returned by %s() before the override is installed.', $getter));

    $GLOBALS['config']['testmode.settings'][$config_key] = $override_value;
    // Reset the config factory cache so the override is picked up.
    \Drupal::configFactory()->reset('testmode.settings');

    $this->assertSame($expected, $testmode->{$getter}(), sprintf('%s() returns the overridden value once $config[testmode.settings][%s] is set.', $getter, $config_key));
  }

  /**
   * Data provider for testGetterReflectsOverride().
   *
   * @return array<string, array<int, mixed>>
   *   Test cases.
   */
  public static function dataProviderGetterReflectsOverride(): array {
    return [
      'views_node' => ['setNodeViews', 'getNodeViews', 'views_node', ['content'], ['content', 'my_view'], ['content', 'my_view']],
      'views_term' => ['setTermViews', 'getTermViews', 'views_term', ['term_view'], ['term_view', 'other_term_view'], ['term_view', 'other_term_view']],
      'views_user' => ['setUserViews', 'getUserViews', 'views_user', ['user_admin_people'], ['user_admin_people', 'extra_user_view'], ['user_admin_people', 'extra_user_view']],
      'pattern_node' => ['setNodePatterns', 'getNodePatterns', 'pattern_node', ['[TEST%'], ['[QATEST%'], ['[QATEST%']],
      'pattern_term' => ['setTermPatterns', 'getTermPatterns', 'pattern_term', ['[TEST%'], ['[QATEST%'], ['[QATEST%']],
      'pattern_user' => ['setUserPatterns', 'getUserPatterns', 'pattern_user', ['%example%'], ['%qa%'], ['%qa%']],
    ];
  }

  /**
   * Tests that getListTerm() reflects $config[...] overrides.
   */
  public function testGetListTermReflectsOverride(): void {
    $testmode = Testmode::getInstance();
    $testmode->setTermsList(TRUE);
    $this->assertTrue($testmode->getListTerm(), 'Stored value is returned by getListTerm() before the override is installed.');

    $GLOBALS['config']['testmode.settings']['list_term'] = FALSE;
    \Drupal::configFactory()->reset('testmode.settings');

    $this->assertFalse($testmode->getListTerm(), 'getListTerm() returns the overridden value once $config[testmode.settings][list_term] is set.');
  }

}
