<?php

namespace Drupal\gbls_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'FormEmbed' block.
 *
 * @Block(
 *  id = "form_embed",
 *  admin_label = @Translation("Eligibility Checker"),
 * )
 */
class FormEmbed extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $config = $this->getConfiguration();

    $poverty_base = isset($config['poverty_base']) ? $config['poverty_base'] : '';
    $rules_url = isset($config['rules_url']) ? $config['rules_url'] : '';
    $coverage_zips = isset($config['coverage_zips']) ? $config['coverage_zips'] : '';
    $poverty_increment = isset($config['poverty_increment']) ? $config['poverty_increment'] : '';
    $poverty_multiplier_1 = isset($config['poverty_multiplier_1']) ? $config['poverty_multiplier_1'] : '';
    $poverty_multiplier_2 = isset($config['poverty_multiplier_2']) ? $config['poverty_multiplier_2'] : '';
    $checker_title = isset($config['checker_title']) ? $config['checker_title'] : '';
    $checker_description = isset($config['checker_description']) ? $config['checker_description'] : '';
    $checker_qualifies = isset($config['checker_qualifies']) ? $config['checker_qualifies'] : '';
    $checker_qualifies_level2 = isset($config['checker_qualifies_level2']) ? $config['checker_qualifies_level2'] : '';
    $checker_qualifies_special = isset($config['checker_qualifies_special']) ? $config['checker_qualifies_special'] : '';    
    $checker_disqualifies = isset($config['checker_disqualifies']) ? $config['checker_disqualifies'] : '';

    $build['forms_embed'] = [
     '#theme' => 'gbls_form_embed',
     '#poverty_base' => $poverty_base,
     '#poverty_increment' => $poverty_increment,
     '#poverty_multiplier_1' => $poverty_multiplier_1,
     '#poverty_multiplier_2' => $poverty_multiplier_2,
     '#checker_title' => $checker_title,
     '#checker_description' => $checker_description,
     '#checker_qualifies' => $checker_qualifies,
     '#checker_qualifies_level2' => $checker_qualifies_level2,
     '#checker_qualifies_special' => $checker_qualifies_special,
     '#checker_disqualifies' => $checker_disqualifies,
     '#rules_url' => $rules_url,
     '#coverage_zips' => $coverage_zips,
     '#attached' => ['library' => ['gbls_blocks/forms_embed']],
    ];
    return $build;
  }

/**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();

    // Add a form field to the existing block configuration form.

    $form['checker_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Eligibility Checker Title'),
      '#default_value' => isset($config['checker_title']) ? $config['checker_title'] : '',
    );

    $form['rules_url'] = array(
      '#type' => 'textfield',
      '#title' => t('URL to description of eligibility rules'),
      '#default_value' => isset($config['rules_url']) ? $config['rules_url'] : '',
    );
    
    $form['checker_description'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_description.format'),
      '#format' => 'full_html',
      '#title' => t('Description'),
      '#default_value' => isset($config['checker_description']['value']) ? $config['checker_description']['value'] : '',
    );

    $form['poverty_base'] = array(
      '#type' => 'number',
      '#title' => t('Poverty Guideline (see https://aspe.hhs.gov)'),
      '#default_value' => isset($config['poverty_base']) ? $config['poverty_base'] : '',
    );

    $form['poverty_increment'] = array(
      '#type' => 'number',
      '#title' => t('Increased amount for each additional family member (see bottom number on table)'),
      '#default_value' => isset($config['poverty_increment']) ? $config['poverty_increment'] : '',
    );

    $form['poverty_multiplier_1'] = array(
      '#type' => 'number',
      '#title' => t('First threshold for percentage of poverty level (e.g., 125 for 125%)'),
      '#default_value' => isset($config['poverty_multiplier_1']) ? $config['poverty_multiplier_1'] : '',
    );

    $form['poverty_multiplier_2'] = array(
      '#type' => 'number',
      '#title' => t('Second threshold for percentage of poverty level (e.g., 200 for 200%)'),
      '#default_value' => isset($config['poverty_multiplier_2']) ? $config['poverty_multiplier_2'] : '',
    );
    
    $form['checker_qualifies'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_qualifies.format'),
      '#format' => 'full_html',
      '#title' => t('Message if the client qualifies'),
      '#default_value' => isset($config['checker_qualifies']['value']) ? $config['checker_qualifies']['value'] : '',
    );

    $form['checker_qualifies_level2'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_qualifies.format'),
      '#format' => 'full_html',
      '#title' => t('Message if the client qualifies only at the second threshold'),
      '#default_value' => isset($config['checker_qualifies_level2']['value']) ? $config['checker_qualifies_level2']['value'] : '',
    );    
    $form['checker_qualifies_special'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_qualifies.format'),
      '#format' => 'full_html',
      '#title' => t('Message if the client qualifies only due to special circumstances'),
      '#default_value' => isset($config['checker_qualifies_special']['value']) ? $config['checker_qualifies_special']['value'] : '',
    );    

    $form['checker_disqualifies'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_disqualifies.format'),
      '#format' => 'full_html', 
      '#title' => t("Message if the client doesn't qualify"),
      '#default_value' => isset($config['checker_disqualifies']['value']) ? $config['checker_disqualifies']['value'] : '',
    );    
    
    $form['coverage_zips'] = array(
      '#type' => 'textarea',
      '#title' => t('List of zip codes in your service area, separated by spaces, commas, or punctuation of your choice.'),
      '#default_value' => isset($config['coverage_zips']) ? $config['coverage_zips'] : '',
    );


    return $form;
  }



  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('poverty_base', $form_state->getValue('poverty_base'));
    $this->setConfigurationValue('poverty_increment', $form_state->getValue('poverty_increment'));
    $this->setConfigurationValue('checker_title', $form_state->getValue('checker_title'));
    $this->setConfigurationValue('rules_url', $form_state->getValue('rules_url'));
    $this->setConfigurationValue('poverty_multiplier_1', $form_state->getValue('poverty_multiplier_1'));
    $this->setConfigurationValue('poverty_multiplier_2', $form_state->getValue('poverty_multiplier_2'));
    $this->setConfigurationValue('coverage_zips', $form_state->getValue('coverage_zips'));

    // This is the only way to save array values
    $this->configuration['checker_description']['value'] = $values['checker_description']['value'];
    $this->configuration['checker_qualifies']['value'] = $values['checker_qualifies']['value'];
    $this->configuration['checker_qualifies_level2']['value'] = $values['checker_qualifies_level2']['value'];
    $this->configuration['checker_qualifies_special']['value'] = $values['checker_qualifies_special']['value'];
    $this->configuration['checker_disqualifies']['value'] = $values['checker_disqualifies']['value'];
    
    $this->configuration['checker_description']['format'] = $values['checker_description']['format'];
    $this->configuration['checker_qualifies']['format'] = $values['checker_qualifies']['format'];
    $this->configuration['checker_qualifies_level2']['format'] = $values['checker_qualifies_level2']['format'];
    $this->configuration['checker_disqualifies']['format'] = $values['checker_disqualifies']['format'];
    $this->configuration['checker_qualifies_special']['format'] = $values['checker_qualifies_special']['format'];

  }

}

?>