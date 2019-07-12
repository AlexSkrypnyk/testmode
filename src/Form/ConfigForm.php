<?php

namespace Drupal\testmate\Form;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\testmate\Testmate;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * Testmate class instance.
   *
   * @var \Drupal\testmate\Testmate
   */
  protected $testmate;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->testmate = Testmate::getInstance();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'testmate_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // Config is managed by the Testmate class.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Test mode'),
      '#description' => $this->t('Test mode is used to alter existing site data so it does not interfere with tests. For example, list of content would have only content items created during test.'),
      '#options' => [1 => $this->t('Enabled'), 0 => $this->t('Disabled')],
      '#default_value' => $this->testmate->isTestMode() ? 1 : 0,
    ];

    $form['lists'] = [
      '#type' => 'details',
      '#title' => $this->t('Lists'),
      '#description' => $this->t('A list of Drupal views to apply the filtering to. One per line.'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];

    $form['lists']['views_node'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Node'),
      '#rows' => '4',
      '#default_value' => Testmate::arrayToMultiline($this->testmate->getNodeViews()),
    ];

    $form['lists']['views_term'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Term'),
      '#rows' => '4',
      '#default_value' => Testmate::arrayToMultiline($this->testmate->getTermViews()),
    ];
    $form['lists']['list_term'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include Taxonomy Terms Overview'),
      '#description' => $this->t('Core Taxonomy Terms Overview page does not use Views. Check this if filtering should apply to this page.'),
      '#default_value' => $this->testmate->getListTerm(),
    ];

    $form['lists']['views_user'] = [
      '#type' => 'textarea',
      '#title' => $this->t('User'),
      '#rows' => '4',
      '#default_value' => Testmate::arrayToMultiline($this->testmate->getUserViews()),
    ];

    $like_doc_link = Link::fromTextAndUrl($this->t('MySQL LIKE'), Url::fromUri('https://dev.mysql.com/doc/refman/8.0/en/string-comparison-functions.html#operator_like'))->toString();

    $like_doc = '<ul>';
    $like_doc .= '<li><code>%</code> matches any number of characters, even zero characters.</li>';
    $like_doc .= '<li><code>_</code> matches exactly one character.</li>';
    $like_doc .= '<li><code>\%</code> matches one <code>%</code> character.</li>';
    $like_doc .= '<li><code>\_</code> matches one <code>_</code> character.</li>';
    $like_doc .= '</ul>';

    $form['patterns'] = [
      '#type' => 'details',
      '#title' => $this->t('Patterns'),
      '#description' => $this->t('Patterns below are used to filter out content and have @like_doc_link syntax: @like_doc Content items that do not match these patterns will be filtered out.', [
        '@like_doc_link' => $like_doc_link,
        '@like_doc' => new FormattableMarkup($like_doc, []),
      ]),
      '#collapsible' => TRUE,
      '#open' => FALSE,
    ];

    $form['patterns']['pattern_node'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node title'),
      '#default_value' => $this->testmate->getNodePattern(),
    ];

    $form['patterns']['pattern_term'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Term name'),
      '#default_value' => $this->testmate->getTermPattern(),
    ];

    $form['patterns']['pattern_user'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User mail'),
      '#default_value' => $this->testmate->getUserPattern(),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->testmate->setNodeViews($form_state->getValue('views_node'));
    $this->testmate->setTermViews($form_state->getValue('views_term'));
    $this->testmate->setUserViews($form_state->getValue('views_user'));
    $this->testmate->setTermsList($form_state->getValue('list_term'));
    $this->testmate->setNodePattern($form_state->getValue('pattern_node'));
    $this->testmate->setTermPattern($form_state->getValue('pattern_term'));
    $this->testmate->setUserPattern($form_state->getValue('pattern_user'));
    $this->testmate->toggleTestMode($form_state->getValue('mode'));
  }

}