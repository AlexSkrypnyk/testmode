<?php

namespace Drupal\testmode;

use Drupal\Core\Cache\Cache;

/**
 * Class Testmode.
 *
 * Class to handle all module operations.
 *
 * @package Drupal\testmode
 */
class Testmode {

  /**
   * Defines cache key for views.
   */
  const CACHE_VIEWS = 'testmode:views';

  /**
   * The Testmode singleton.
   *
   * @var \Drupal\testmode\Testmode
   */
  protected static $instance = NULL;

  /**
   * Drupal config instance.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Drupal state instance.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Testmode constructor.
   */
  public function __construct() {
    $this->config = \Drupal::configFactory()->getEditable('testmode.settings');
    $this->state = \Drupal::state();
  }

  /**
   * Get the Testmode class instance.
   *
   * @return \Drupal\testmode\Testmode
   *   Instance of the Testmode class.
   */
  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Enable test mode.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function enableTestMode() {
    if (!$this->isTestMode()) {
      $this->setMode(TRUE);
      $this->invalidateCahes();
    }
    return $this;
  }

  /**
   * Disable test mode.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function disableTestMode() {
    if ($this->isTestMode()) {
      $this->setMode(FALSE);
      $this->invalidateCahes();
    }
    return $this;
  }

  /**
   * Toggle test mode.
   *
   * @param bool $enable
   *   Flag to enable test mode.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function toggleTestMode($enable) {
    if ($enable) {
      $this->enableTestMode();
    }
    else {
      $this->disableTestMode();
    }
    return $this;
  }

  /**
   * Invalidate caches.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function invalidateCahes() {
    Cache::invalidateTags([self::CACHE_VIEWS]);
    return $this;
  }

  /**
   * Check if test mode is enabled.
   *
   * @return bool
   *   TRUE if test mode is enabled, FALSE otherwise.
   */
  public function isTestMode() {
    return (bool) $this->state->get('testmode.enabled');
  }

  /**
   * Set test mode.
   *
   * @param bool $value
   *   FLag to set test mode.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  protected function setMode($value) {
    if ($value) {
      $this->state->set('testmode.enabled', TRUE);
    }
    else {
      $this->state->delete('testmode.enabled');
    }
    return $this;
  }

  /**
   * Get node views machine names.
   *
   * @return array
   *   Array of node views machine names.
   */
  public function getNodeViews() {
    $value = $this->config->get('views_node');
    return is_array($value) ? $value : [];
  }

  /**
   * Set node views machine names.
   *
   * @param string|array $value
   *   String delimited by a new line or an array.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setNodeViews($value) {
    $this->config->set('views_node', self::multilineToArray($value))->save();
    return $this;
  }

  /**
   * Get term views machine names.
   *
   * @return array
   *   Array of term views machine names.
   */
  public function getTermViews() {
    $value = $this->config->get('views_term');
    return is_array($value) ? $value : [];
  }

  /**
   * Set term views machine names.
   *
   * @param string|array $value
   *   String delimited by a new line or an array.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setTermViews($value) {
    $this->config->set('views_term', self::multilineToArray($value))->save();
    return $this;
  }

  /**
   * Get list term flag.
   *
   * @return bool
   *   TRUE if the flag is set, FALSE otherwise.
   */
  public function getListTerm() {
    return (bool) $this->config->get('list_term');
  }

  /**
   * Set list term flag.
   *
   * @param bool $value
   *   Flag to set the value.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setTermsList($value) {
    $this->config->set('list_term', (bool) $value)->save();
    return $this;
  }

  /**
   * Get user views machine names.
   *
   * @return array
   *   Array of user views machine names.
   */
  public function getUserViews() {
    $value = $this->config->get('views_user');
    return is_array($value) ? $value : [];
  }

  /**
   * Set user views machine names.
   *
   * @param string|array $value
   *   String delimited by a new line or an array.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setUserViews($value) {
    $this->config->set('views_user', self::multilineToArray($value))->save();
    return $this;
  }

  /**
   * Get node pattern.
   *
   * @return string
   *   Node pattern.
   */
  public function getNodePattern() {
    return (string) $this->config->get('pattern_node');
  }

  /**
   * Set node pattern.
   *
   * @param string $value
   *   Node pattern.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setNodePattern($value) {
    $this->config->set('pattern_node', trim($value))->save();
    return $this;
  }

  /**
   * Get term pattern.
   *
   * @return string
   *   Term pattern.
   */
  public function getTermPattern() {
    return $this->config->get('pattern_term');
  }

  /**
   * Set term pattern.
   *
   * @param string $value
   *   Term pattern.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setTermPattern($value) {
    $this->config->set('pattern_term', trim($value))->save();
    return $this;
  }

  /**
   * Get user pattern.
   *
   * @return string
   *   User pattern.
   */
  public function getUserPattern() {
    return $this->config->get('pattern_user');
  }

  /**
   * Set user pattern.
   *
   * @param string $value
   *   User pattern.
   *
   * @return \Drupal\testmode\Testmode
   *   Current class instance.
   */
  public function setUserPattern($value) {
    $this->config->set('pattern_user', trim($value))->save();
    return $this;
  }

  /**
   * Match subject to an MySQL LIKE pattern.
   *
   * @param string $like_pattern
   *   Patter in MySQL LIKE format.
   * @param string $subject
   *   String subject to match.
   *
   * @return bool
   *   TRUE if subject matches pattern, FALSE otherwise.
   */
  public static function matchLike($like_pattern, $subject) {
    $like_pattern = str_replace('\%', 'LIKE_PERCENT_CHARACTER_PLACEHOLDER', $like_pattern);
    $like_pattern = str_replace('\_', 'LIKE_UNDERSCORE_CHARACTER_PLACEHOLDER', $like_pattern);
    $like_pattern = preg_quote($like_pattern);
    $like_pattern = str_replace('LIKE_PERCENT_CHARACTER_PLACEHOLDER', '\%', $like_pattern);
    $like_pattern = str_replace('LIKE_UNDERSCORE_CHARACTER_PLACEHOLDER', '\_', $like_pattern);
    $like_pattern = preg_replace('/(?<!\\\\)\%/i', '.*', $like_pattern);
    $like_pattern = preg_replace('/(?<!\\\\)\_/', '.', $like_pattern);
    $pattern = '/' . $like_pattern . '/';

    return (bool) preg_match($pattern, $subject);
  }

  /**
   * Helper to convert multi-line strings into an array.
   *
   * @param string $string
   *   String value to convert.
   *
   * @return array
   *   Array of values.
   */
  public static function multilineToArray($string) {
    $lines = is_array($string) ? $string : explode("\n", str_replace("\r\n", "\n", $string));
    return array_values(array_filter(array_map('trim', $lines)));
  }

  /**
   * Helper to convert an array to multi-line string value.
   *
   * @param array $array
   *   Array to convert.
   *
   * @return string
   *   String value of the array.
   */
  public static function arrayToMultiline(array $array) {
    return implode(PHP_EOL, array_filter($array));
  }

}
