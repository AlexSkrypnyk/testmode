<?php

namespace Drupal\Tests\testmate\Functional;

/**
 * Tests the test mode.
 *
 * @group Testmate
 */
class ModeTest extends TestmateTestBase {

  /**
   * Test that enabling and disabling of test mode works.
   */
  public function testMode() {
    $this->assertFalse($this->testmate->isTestMode(), 'Test mode is disabled by default');

    $this->testmate->enableTestMode();

    $this->assertTrue($this->testmate->isTestMode());

    $this->testmate->disableTestMode();

    $this->assertFalse($this->testmate->isTestMode(), 'Test mode is disabled by default');
  }

  /**
   * Test that enabling test mode shows message for Admin.
   */
  public function testModeAdminMessage() {
    $this->drupalLoginAdmin();

    $this->drupalGet('admin/config/development/testmate');
    $this->assertFalse($this->testmate->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');

    $this->testmate->enableTestMode();

    $this->assertTrue($this->testmate->isTestMode());
    $this->drupalGet('admin/config/development/testmate');
    $this->assertText('Test mode is enabled');

    $this->testmate->disableTestMode();

    $this->drupalGet('admin/config/development/testmate');
    $this->assertFalse($this->testmate->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');
  }

}
