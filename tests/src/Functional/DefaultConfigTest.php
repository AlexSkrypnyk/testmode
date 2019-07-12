<?php

namespace Drupal\Tests\testmate\Functional;

/**
 * Tests the default configuration.
 *
 * @group Testmate
 */
class DefaultConfigTest extends TestmateTestBase {

  /**
   * Test that default configuration is correctly installed.
   */
  public function testDefaultConfig() {
    $this->assertEquals(['content'], $this->testmate->getNodeViews());
    $this->assertEquals([''], $this->testmate->getTermViews());
    $this->assertEquals(TRUE, $this->testmate->getListTerm());
    $this->assertEquals(['user_admin_people'], $this->testmate->getUserViews());
    $this->assertEquals('[TEST%', $this->testmate->getNodePattern());
    $this->assertEquals('[TEST%', $this->testmate->getTermPattern());
    $this->assertEquals('%example%', $this->testmate->getUserPattern());
  }

}
