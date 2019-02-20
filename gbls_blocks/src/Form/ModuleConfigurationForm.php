<?php

namespace Drupal\gbls_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gbls_blocks_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'gbls_blocks.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('gbls_blocks.settings');
    $form = [
      'poverty_base' => [
        '#type' => 'number',
        '#title' => $this->t('Base poverty rate'),
        '#default_value' => $config->get('poverty_base')
      ],
      'poverty_increment' => [
        '#type' => 'number',
        '#title' => $this->t('Poverty rate increment'),
        '#default_value' => $config->get('poverty_increment')
      ],
      'checker_title' => [
        '#type' => 'textfield',
        '#title' => $this->t('Page title'),
        '#default_value' => $config->get('checker_title')
      ],
      'checker_description' => [
        '#type' => 'textarea',
        '#title' => $this->t('Page description'),
        '#default_value' => $config->get('checker_description')
      ],
      'checker_qualifies' => [
        '#type' => 'textarea',
        '#title' => $this->t('Message if the client qualifies'),
        '#default_value' => $config->get('checker_qualifies')
      ],
      'checker_disqualifies' => [
        '#type' => 'textarea',
        '#title' => $this->t("Message if the client doesn't qualify"),
        '#default_value' => $config->get('checker_disqualifies')
      ],

    ];
    //$form['your_message'] = [
    //  '#type' => 'textarea',
    //  '#title' => $this->t('Your message'),
    //  '#default_value' => $config->get('your_message'),
    //];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //$values = $form_state->getValues();
    $this->config('gbls_blocks.settings')
      ->set('poverty_base', $form_state->getValue('poverty_base'))
      ->save();
    $this->config('gbls_blocks.settings')
      ->set('poverty_increment', $form_state->getValue('poverty_increment'))
      ->save();
    $this->config('gbls_blocks.settings')
      ->set('checker_title', $form_state->getValue('checker_title'))
      ->save();
    $this->config('gbls_blocks.settings')
      ->set('checker_description', $form_state->getValue('checker_description'))
      ->save();
    $this->config('gbls_blocks.settings')
      ->set('checker_qualifies', $form_state->getValue('checker_qualifies'))
      ->save();
    $this->config('gbls_blocks.settings')
      ->set('checker_disqualifies', $form_state->getValue('checker_disqualifies'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}