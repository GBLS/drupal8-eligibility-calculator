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
    $poverty_increment = isset($config['poverty_increment']) ? $config['poverty_increment'] : '';
    $checker_title = isset($config['checker_title']) ? $config['checker_title'] : '';
    $checker_description = isset($config['checker_description']) ? $config['checker_description'] : '';
    $checker_qualifies = isset($config['checker_qualifies']) ? $config['checker_qualifies'] : '';
    $checker_disqualifies = isset($config['checker_disqualifies']) ? $config['checker_disqualifies'] : '';

    $build['forms_embed'] = [
     '#theme' => 'gbls_form_embed',
     '#poverty_base' => $poverty_base,
     '#poverty_increment' => $poverty_increment,
     '#checker_title' => $checker_title,
     '#checker_description' => $checker_description,
     '#checker_qualifies' => $checker_qualifies,
     '#checker_disqualifies' => $checker_disqualifies,
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
    $form['poverty_base'] = array(
      '#type' => 'number',
      '#title' => t('Poverty Base Number'),
      '#default_value' => isset($config['poverty_base']) ? $config['poverty_base'] : '',
    );

    $form['poverty_increment'] = array(
      '#type' => 'number',
      '#title' => t('Amount for each additional family member'),
      '#default_value' => isset($config['poverty_increment']) ? $config['poverty_increment'] : '',
    );

    $form['checker_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => isset($config['checker_title']) ? $config['checker_title'] : '',
    );
    
    $form['checker_description'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_description.format'),
      '#format' => 'full_html',
      '#title' => t('Description'),
      '#default_value' => isset($config['checker_description']['value']) ? $config['checker_description']['value'] : '',
    );
    
    $form['checker_qualifies'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_qualifies.format'),
      '#format' => 'full_html',
      '#title' => t('Message if the client qualifies'),
      '#default_value' => isset($config['checker_qualifies']['value']) ? $config['checker_qualifies']['value'] : '',
    );
    
    $form['checker_disqualifies'] = array(
      '#type' => 'text_format',
      //'#base_type' => 'textarea',
      //'#format' => $config->get('checker_disqualifies.format'),
      '#format' => 'full_html', 
      '#title' => t("Message if the client doesn't qualify"),
      '#default_value' => isset($config['checker_disqualifies']['value']) ? $config['checker_disqualifies']['value'] : '',
    );    

    
    return $form;
  }



  /**
   * {@inheritdoc}
   */
/*
  public function blockSubmit($form, FormStateInterface $form_state) {

    $config = $this->getConfiguration();
    $values = $form_state->getValues();

    $config->set('poverty_base',$values['poverty_base'])
    ->set('poverty_increment',$values['poverty_increment'])
    ->set('checker_title',$values['checker_title'])
    ->set('checker_description.value',$values['checker_title']['value'])
    ->set('checker_description.format',$values['checker_title']['format'])
    ->set('checker_qualifies.value',$values['checker_title']['value'])
    ->set('checker_qualifies.format',$values['checker_title']['format'])
    ->set('checker_disqualifies.value',$values['checker_title']['value'])
    ->set('checker_disqualifies.format',$values['checker_title']['format'])
    ->save();
  }
*/
  ///*
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('poverty_base', $form_state->getValue('poverty_base'));
    $this->setConfigurationValue('poverty_increment', $form_state->getValue('poverty_increment'));
    $this->setConfigurationValue('checker_title', $form_state->getValue('checker_title'));
//    $this->setConfigurationValue('checker_description', $form_state->getValue('checker_description'));
//    $this->setConfigurationValue('checker_qualifies', $form_state->getValue('checker_qualifies'));
//    $this->setConfigurationValue('checker_disqualifies', $form_state->getValue('checker_disqualifies'));
    $this->configuration['checker_description']['value'] = $values['checker_description']['value'];
    $this->configuration['checker_qualifies']['value'] = $values['checker_qualifies']['value'];
    $this->configuration['checker_disqualifies']['value'] = $values['checker_disqualifies']['value'];
    $this->configuration['checker_description']['format'] = $values['checker_description']['format'];
    $this->configuration['checker_qualifies']['format'] = $values['checker_qualifies']['format'];
    $this->configuration['checker_disqualifies']['format'] = $values['checker_disqualifies']['format'];
/*    
    $this->setConfigurationValue('checker_description.value', $values['checker_description']['value']);
    $this->setConfigurationValue('checker_qualifies.value', $values['checker_qualifies']['value']);
    $this->setConfigurationValue('checker_disqualifies.value', $values['checker_disqualifies']['value']);    
    $this->setConfigurationValue('checker_description.format', $values['checker_description']['format']);
    $this->setConfigurationValue('checker_qualifies.format', $values['checker_qualifies']['format']);
    $this->setConfigurationValue('checker_disqualifies.format', $values['checker_disqualifies']['format']);    
    */
  }
//*/

}

?>