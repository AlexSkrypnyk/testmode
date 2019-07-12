<?php

namespace Drupal\Tests\testmode\Functional;

/**
 * Tests the settings form.
 *
 * @group Testmode
 */
class SettingsFormTest extends TestmodeTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['testmode'];

  /**
   * Test that setting form works correctly.
   */
  public function testSettingsFormDefaults() {
    $this->drupalLoginAdmin();

    $this->drupalGet('admin/config/development/testmode');
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

    $this->drupalGet('admin/config/development/testmode');
    $this->assertFalse($this->testmode->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');

    $this->drupalPostForm('admin/config/development/testmode', [
      'mode' => 1,
    ], 'Save configuration');
    $this->assertTrue($this->testmode->isTestMode());
    $this->assertText('Test mode is enabled');

    $this->drupalPostForm('admin/config/development/testmode', [
      'mode' => 0,
    ], 'Save configuration');
    $this->assertFalse($this->testmode->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');
  }

}
