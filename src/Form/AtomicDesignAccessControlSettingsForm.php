<?php

namespace Drupal\atomic_design_access_control\Form;

/**
 * @file
 * Contains Drupal\customization\Form\CustomSettingsForm.
 */

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

/**
 * Class CustomSettingsForm.
 *
 * @package Drupal\customization\Form
 */
class AtomicDesignAccessControlSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'atomic_design_access_control.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('atomic_design_access_control.settings');

    $form['access_settings'] = [
      '#type' => 'details',
      '#title' => t('Access options'),
    ];

    /** @var \Drupal\node\Entity\NodeType[] $node_types */
    $node_types = NodeType::loadMultiple();
    $node_type_options = [];
    foreach ($node_types as $node_type) {
      $node_type_options[$node_type->id()] = $node_type->label();
    }
    $form['access_settings']['disallow_access'] = [
      '#title' => $this->t('Disallow access'),
      '#type' => "select",
      '#options' => $node_type_options,
      '#default_value' => $config->get('disallow_access'),
      '#multiple' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);

    $this->config('atomic_design_access_control.settings')
      ->set('disallow_access', $form_state->getValue('disallow_access'))
      ->save();

  }

}
