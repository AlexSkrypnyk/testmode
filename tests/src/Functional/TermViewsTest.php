<?php

namespace Drupal\Tests\testmode\Functional;

use Drupal\Core\Language\LanguageInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\views\Views;

/**
 * Tests the term views.
 *
 * @group Testmode
 */
class TermViewsTest extends TestmodeTestBase {

  /**
   * Vocabulary for tests.
   *
   * @var \Drupal\taxonomy\Entity\Vocabulary
   */
  protected $vocabulary;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['taxonomy', 'views'];

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['test_testmode_term'];

  /**
   * Test term view without caching.
   */
  public function testTermViewNoCache() {
    $this->createVocabulary();
    $this->createTerms();

    // Login to bypass page caching.
    $this->drupalLogin($this->drupalCreateUser());

    // Add test view to a list of views.
    $this->testmode->setTermViews('test_testmode_term');

    $this->drupalGet('/test-testmode-term');
    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');

    $this->assertText('Term 1');
    $this->assertText('Term 2');
    $this->assertText('[TEST] Term 3');

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');

    $this->testmode->enableTestMode();

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');

    $this->assertNoText('Term 1');
    $this->assertNoText('Term 2');
    $this->assertText('[TEST] Term 3');

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');
  }

  /**
   * Test term view with tag-based caching.
   */
  public function testTermViewCacheTag() {
    $this->createVocabulary();
    $this->createTerms();

    // Login to bypass page caching.
    $this->drupalLogin($this->drupalCreateUser());

    // Add test view to a list of Testmode views.
    $this->testmode->setTermViews('test_testmode_term');

    // Enable Tag caching for this view.
    $view = Views::getView('test_testmode_term');
    $view->setDisplay('page_1');
    $view->display_handler->overrideOption('cache', [
      'type' => 'tag',
    ]);
    $view->save();

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'MISS');

    $this->assertText('Term 1');
    $this->assertText('Term 2');
    $this->assertText('[TEST] Term 3');

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'HIT');

    $this->testmode->enableTestMode();

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'MISS');

    $this->assertNoText('Term 1');
    $this->assertNoText('Term 2');
    $this->assertText('[TEST] Term 3');

    $this->drupalGet('/test-testmode-term');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'HIT');
  }

  /**
   * Test default Term Overview page with tag-based caching.
   */
  public function testTermOverview() {
    $this->createVocabulary();
    $this->createTerms();

    $this->testmode->setTermsList(TRUE);

    // Login to bypass page caching.
    $this->drupalLoginAdmin();

    $this->drupalGet('/admin/structure/taxonomy/manage/testmode_tags/overview');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');

    $this->assertText('Term 1');
    $this->assertText('Term 2');
    $this->assertText('[TEST] Term 3');

    $this->drupalGet('/admin/structure/taxonomy/manage/testmode_tags/overview');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');

    $this->testmode->enableTestMode();

    $this->drupalGet('/admin/structure/taxonomy/manage/testmode_tags/overview');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');

    $this->assertNoText('Term 1');
    $this->assertNoText('Term 2');
    $this->assertText('[TEST] Term 3');

    $this->drupalGet('/admin/structure/taxonomy/manage/testmode_tags/overview');
    $this->assertHeader('X-Drupal-Dynamic-Cache', 'UNCACHEABLE');
  }

  /**
   * Helper to create vocabulary.
   */
  protected function createVocabulary() {
    // Create the vocabulary for the tag field.
    $this->vocabulary = Vocabulary::create([
      'name' => 'Testmode tags',
      'vid' => 'testmode_tags',
    ]);
    $this->vocabulary->save();
  }

  /**
   * Helper to create terms.
   */
  protected function createTerms() {
    for ($i = 0; $i < 2; $i++) {
      $this->createTerm([
        'name' => sprintf('Term %s %s', $i + 1, $this->randomMachineName()),
      ]);
    }

    $this->createTerm([
      'name' => sprintf('[TEST] Term %s %s', $i + 1, $this->randomMachineName()),
    ]);
  }

  /**
   * Creates and returns a taxonomy term.
   *
   * @param array $settings
   *   (optional) An array of values to override the following default
   *   properties of the term:
   *   - name: A random string.
   *   - description: A random string.
   *   - format: First available text format.
   *   - vid: Vocabulary ID of self::$vocabulary object.
   *   - langcode: LANGCODE_NOT_SPECIFIED.
   *   Defaults to an empty array.
   *
   * @return \Drupal\taxonomy\Entity\Term
   *   The created taxonomy term.
   */
  protected function createTerm(array $settings = []) {
    $filter_formats = filter_formats();
    $format = array_pop($filter_formats);
    $settings += [
      'name' => $this->randomMachineName(),
      'description' => $this->randomMachineName(),
      // Use the first available text format.
      'format' => $format->id(),
      'vid' => $this->vocabulary->id(),
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ];
    $term = Term::create($settings);
    $term->save();
    return $term;
  }

}
