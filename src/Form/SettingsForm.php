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
      '#default_value' => !empty($config->get('notification_message')['value']) ? $config->get('notification_message')['value']: '<p>This website makes use of necessary cookies. In order to optimize your user experience, the website makes use of optional cookies for which we ask your permission. <a href="#">Click for more information</a></p>',
    ];
    $form['notification']['status_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Status message'),
      '#description' => $this->t('Used in the block where the user can see the cookie status and alter it.
        Use [[status]] where the current user status should be printed out.'),
      '#default_value' => !empty($config->get('status_text')['value']) ? $config->get('status_text')['value']: 'Currently you have given [[status]] for optional cookies. You can change this here:',
      '#required' => TRUE,
      '#format' => !empty($config->get('status_text')['format']) ? $config->get('status_text')['format']: 'basic_html',
    ];

    $form['agree_button'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Agree Button'),
    ];
    $form['agree_button']['agree_button_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => !empty($config->get('agree_button_label')) ? $config->get('agree_button_label') : 'Consent',
      '#required' => TRUE,
    ];
    $form['agree_button']['cookie_agree_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Value'),
      '#default_value' => !empty($config->get('cookie_agree_value')) ? $config->get('cookie_agree_value') : 2,
      '#required' => TRUE,
    );
    $form['agree_button']['cookie_agree_status_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie agree status text'),
      '#default_value' => !empty($config->get('cookie_agree_status_text')) ? $config->get('cookie_agree_status_text') : 'consent',
      '#required' => TRUE,
    );
    $form['agree_button']['cookie_agree_link_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie agree link text'),
      '#description' => $this->t('The text for the link that will allow users to give their consent.'),
      '#default_value' => !empty($config->get('cookie_agree_link_text')) ? $config->get('cookie_agree_link_text') : 'Give my consent',
      '#required' => TRUE,
    );


    $form['disagree_button'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Disagree Button'),
    ];
    $form['disagree_button']['disagree_button_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => !empty($config->get('disagree_button_label')) ? $config->get('disagree_button_label') : 'No consent',
      '#required' => TRUE,
    ];
    $form['disagree_button']['cookie_disagree_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Value'),
      '#default_value' => !empty($config->get('cookie_disagree_value')) ? $config->get('cookie_disagree_value') : 1,
      '#required' => TRUE,
    );
    $form['disagree_button']['cookie_disagree_status_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie disagree status text'),
      '#default_value' => !empty($config->get('cookie_disagree_status_text')) ? $config->get('cookie_disagree_status_text') : 'no consent',
      '#required' => TRUE,
    );
    $form['disagree_button']['cookie_disagree_link_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie disagree link text'),
      '#description' => $this->t('The text for the link that will allow users to withdraw their consent.'),
      '#default_value' => !empty($config->get('cookie_disagree_link_text')) ? $config->get('cookie_disagree_link_text') : 'Withdraw my consent',
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
      ->set('status_text', $form_state->getValue('status_text'))
      ->set('cookie_agree_link_text', $form_state->getValue('cookie_agree_link_text'))
      ->set('cookie_disagree_link_text', $form_state->getValue('cookie_disagree_link_text'))
      ->set('cookie_agree_status_text', $form_state->getValue('cookie_agree_status_text'))
      ->set('cookie_disagree_status_text', $form_state->getValue('cookie_disagree_status_text'))
      ->save();
  }

}
