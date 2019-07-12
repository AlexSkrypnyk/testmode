<?php

namespace Drupal\Tests\testmate\Functional;

/**
 * Tests the configuration form.
 *
 * @group Testmate
 */
class ConfigFormTest extends TestmateTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['testmate'];

  /**
   * Test that configuration form works correctly.
   */
  public function testConfigFormDefaults() {
    $this->drupalLoginAdmin();

    $this->drupalGet('admin/config/development/testmate');
    $this->assertFieldByName('mode', 0);
    $this->assertFieldByName('views_node', 'content');
    $this->assertFieldByName('views_term', '');
    $this->assertFieldByName('list_term', 1);
    $this->assertFieldByName('views_user', 'user_admin_people');
    $this->assertFieldByName('pattern_node', '[TEST%');
    $this->assertFieldByName('pattern_term', '[TEST%');
    $this->assertFieldByName('pattern_user', '%example%');
  }

  /**
   * Test that enabling/disabling test mode through the form shows message.
   */
  public function testModeSwitch() {
    $this->drupalLoginAdmin();

    $this->drupalGet('admin/config/development/testmate');
    $this->assertFalse($this->testmate->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');

    $this->drupalPostForm('admin/config/development/testmate', [
      'mode' => 1,
    ], 'Save configuration');
    $this->assertTrue($this->testmate->isTestMode());
    $this->assertText('Test mode is enabled');

    $this->drupalPostForm('admin/config/development/testmate', [
      'mode' => 0,
    ], 'Save configuration');
    $this->assertFalse($this->testmate->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');
  }

}
