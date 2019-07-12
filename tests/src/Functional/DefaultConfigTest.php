<?php

namespace Drupal\Tests\testmode\Functional;

/**
 * Tests the default configuration.
 *
 * @group Testmode
 */
class DefaultConfigTest extends TestmodeTestBase {

  /**
   * Test that default configuration is correctly installed.
   */
  public function testDefaultConfig() {
    $this->assertEquals(['content'], $this->testmode->getNodeViews());
    $this->assertEquals([''], $this->testmode->getTermViews());
    $this->assertEquals(TRUE, $this->testmode->getListTerm());
    $this->assertEquals(['user_admin_people'], $this->testmode->getUserViews());
    $this->assertEquals('[TEST%', $this->testmode->getNodePattern());
    $this->assertEquals('[TEST%', $this->testmode->getTermPattern());
    $this->assertEquals('%example%', $this->testmode->getUserPattern());
  }

}
