<?php

namespace Drupal\Tests\testmate\Unit;

use Drupal\testmate\Testmate;
use Drupal\Tests\UnitTestCase;

/**
 * Class TestmateTest.
 *
 * @group Testmate
 *
 * @package Drupal\testmate\Tests
 */
class TestmateTest extends UnitTestCase {

  /**
   * Test for Testmate::matchLike().
   *
   * @dataProvider providerMatchLike
   */
  public function testMatchLike($pattern, $subject, $is_match) {
    $actual = Testmate::matchLike($pattern, $subject);
    if ($is_match) {
      $this->assertTrue($actual, 'Expected match found');
    }
    else {
      $this->assertFalse($actual, 'Expected not match found');
    }
  }

  /**
   * Data provider for testMatchLike().
   */
  public function providerMatchLike() {
    return [
      ['', '', TRUE],
      ['t', 't', TRUE],
      ['t', 'text', TRUE],
      ['text', 'text', TRUE],

      // One or more characters.
      ['text%', 'text', TRUE],
      ['text%', 'textmore', TRUE],
      ['text%suffix', 'textmoresuffix', TRUE],
      ['%text', 'text', TRUE],
      ['%text', 'moretext', TRUE],
      ['prefix%text', 'prefixmoretext', TRUE],
      ['%text%', 'text', TRUE],
      ['%text%', 'moretextmore', TRUE],
      ['prefix%text%suffix', 'prefixmoretextmoresuffix', TRUE],

      // Single character.
      ['text_', 'text', FALSE],
      ['_text', 'text', FALSE],
      ['_text_', 'text', FALSE],
      ['text_', 'textA', TRUE],
      ['text__', 'textAB', TRUE],
      ['_text', 'Atext', TRUE],
      ['__text', 'ABtext', TRUE],
      ['__text__', 'ABtextAB', TRUE],
      ['prefix_text_suffix', 'prefixAtextAsuffix', TRUE],
      ['prefix_text_suffix', 'prefixABtextABsuffix', FALSE],

      // Mix of single and one or more characters.
      ['text_%', 'text', FALSE],
      ['text_%', 'textA', TRUE],
      ['text_%', 'textAB', TRUE],

      // Regex escape characters.
      ['[text', '[text', TRUE],
      ['[text%', '[text', TRUE],
      ['[text%', '[textsuffix', TRUE],
      ['[text_', '[textA', TRUE],
      ['_[text%', 'A[text', TRUE],
      ['_[text%', 'A[textsuffix', TRUE],

      // LIKE escape characters.
      ['text\%', 'text%', TRUE],
      ['text\%suffix', 'text%suffix', TRUE],
      ['text\_', 'text_', TRUE],
      ['text\_', 'text_suffix', TRUE],

      // Mix of normal and escape LIKE characters.
      ['text\%text2%', 'text%text2suffix', TRUE],
    ];
  }

  /**
   * Test for Testmate::multilineToArray().
   *
   * @dataProvider providerMultilineToArray
   */
  public function testMultilineToArray($string, $expected) {
    $actual = Testmate::multilineToArray($string);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for multilineToArray().
   */
  public function providerMultilineToArray() {
    return [
      ['', []],
      [' ', []],
      ['a', ['a']],
      [
        'a
        b', ['a', 'b'],
      ],
      [
        'a aa
        b', ['a aa', 'b'],
      ],
      [
        'a aa
        b
        c', ['a aa', 'b', 'c'],
      ],
      [
        'a aa
        b
        
        c', ['a aa', 'b', 'c'],
      ],
      // Array as input.
      [
        ['a', 'b'], ['a', 'b'],
      ],
    ];
  }

  /**
   * Test for Testmate::arrayToTextarea().
   *
   * @dataProvider providerArrayToTextarea
   */
  public function testArrayToTextarea($array, $expected) {
    $actual = Testmate::arrayToMultiline($array);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for arrayToTextarea().
   */
  public function providerArrayToTextarea() {
    return [
      [[], ''],
      [[''], ''],
      [['', ''], ''],
      [[' ', ''], ' '],
      [['a'], 'a'],
      [
        ['a', 'b'], 'a' . PHP_EOL . 'b',
      ],
      [[' a ', 'b'], ' a ' . PHP_EOL . 'b'],
      [[' a ', '', 'b'], ' a ' . PHP_EOL . 'b'],
      [[' a ', ' ', 'b'], ' a ' . PHP_EOL . ' ' . PHP_EOL . 'b'],
    ];
  }

}
