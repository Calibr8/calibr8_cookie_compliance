<?php

namespace Drupal\calibr8_cookie_compliance\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\calibr8_cookie_compliance\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'calibr8_cookie_compliance.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'calibr8_cookie_compliance_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('calibr8_cookie_compliance.settings');

    // Settings.
    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Settings'),
    ];
    $form['settings']['cookie_expiration'] = [
      '#type' => 'number',
      '#title' => $this->t('Cookie expiration'),
      '#description' => $this->t('Days before approval cookie expires.'),
      '#default_value' => $config->get('cookie_expiration'),
      '#required' => TRUE,
    ];

    $form['notification'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Notification'),
    ];
    $form['notification']['notification_message'] = [
      '#type' => 'text_format',
      '#format' => !empty($config->get('notification_message')['format']) ? $config->get('notification_message')['format']: 'basic_html',
      '#title' => $this->t('Notification message'),
      '#default_value' => !empty($config->get('notification_message')['value']) ? $config->get('notification_message')['value']: '',
    ];

    $form['agree_button'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Agree Button'),
    ];
    $form['agree_button']['agree_button_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $config->get('agree_button_label'),
      '#required' => TRUE,
    ];
    $form['agree_button']['cookie_agree_value'] = array(
      '#type' => 'textfield',
      '#title' => t('Value'),
      '#default_value' => !empty($config->get('cookie_agree_value')) ? $config->get('cookie_agree_value') : 2,
      '#required' => TRUE,
    );


    $form['disagree_button'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Disagree Button'),
    ];
    $form['disagree_button']['disagree_button_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $config->get('disagree_button_label'),
      '#required' => TRUE,
    ];
    $form['disagree_button']['cookie_disagree_value'] = array(
      '#type' => 'textfield',
      '#title' => t('Value'),
      '#default_value' => !empty($config->get('cookie_disagree_value')) ? $config->get('cookie_disagree_value') : 1,
      '#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('calibr8_cookie_compliance.settings')
      ->set('cookie_expiration', $form_state->getValue('cookie_expiration'))
      ->set('notification_message', $form_state->getValue('notification_message'))
      ->set('agree_button_label', $form_state->getValue('agree_button_label'))
      ->set('cookie_agree_value', $form_state->getValue('cookie_agree_value'))
      ->set('disagree_button_label', $form_state->getValue('disagree_button_label'))
      ->set('cookie_disagree_value', $form_state->getValue('cookie_disagree_value'))
      ->save();
  }

}
