<?php

namespace Drupal\Tests\testmode\Functional;

/**
 * Tests the test mode.
 *
 * @group Testmode
 */
class ModeTest extends TestmodeTestBase {

  /**
   * Test that enabling and disabling of test mode works.
   */
  public function testMode() {
    $this->assertFalse($this->testmode->isTestMode(), 'Test mode is disabled by default');

    $this->testmode->enableTestMode();

    $this->assertTrue($this->testmode->isTestMode());

    $this->testmode->disableTestMode();

    $this->assertFalse($this->testmode->isTestMode(), 'Test mode is disabled by default');
  }

  /**
   * Test that enabling test mode shows message for Admin.
   */
  public function testModeAdminMessage() {
    $this->drupalLoginAdmin();

    $this->drupalGet('admin/config/development/testmode');
    $this->assertFalse($this->testmode->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');

    $this->testmode->enableTestMode();

    $this->assertTrue($this->testmode->isTestMode());
    $this->drupalGet('admin/config/development/testmode');
    $this->assertText('Test mode is enabled');

    $this->testmode->disableTestMode();

    $this->drupalGet('admin/config/development/testmode');
    $this->assertFalse($this->testmode->isTestMode(), 'Test mode is disabled by default');
    $this->assertNoText('Test mode is enabled');
  }

}
