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
      '#default_value' => !empty($config->get('cookie_expiration')) ? $config->get('cookie_expiration') : 100,
      '#description' => $this->t('Days before approval cookie expires.'),
      '#required' => TRUE,
    ];
    $form['settings']['cookie_noconsent_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie no consent value, default 1'),
      '#default_value' => !empty($config->get('cookie_noconsent_value')) ? $config->get('cookie_noconsent_value') : 1,
      '#required' => TRUE,
    );
    $form['settings']['cookie_consent_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie consent value, default 2'),
      '#default_value' => !empty($config->get('cookie_consent_value')) ? $config->get('cookie_consent_value') : 2,
      '#required' => TRUE,
    );

    // Info block.

    $form['info_block'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Info block'),
    ];
    $form['info_block']['notification_message'] = [
      '#type' => 'text_format',
      '#format' => !empty($config->get('notification_message')['format']) ? $config->get('notification_message')['format']: 'editor',
      '#title' => $this->t('Notification message'),
      '#default_value' => !empty($config->get('notification_message')['value']) ? $config->get('notification_message')['value']: '<p>This website makes use of necessary cookies. In order to optimize your user experience, the website makes use of optional cookies for which we ask your permission. <a href="#">Click for more information</a></p>',
      '#description' => $this->t('Short description of the cookie purpose, should include a link to the complete cookie statement.'),
    ];
    $form['info_block']['consent_button']['consent_button_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consent button label'),
      '#default_value' => !empty($config->get('consent_button_label')) ? $config->get('consent_button_label') : 'Yes, I agree',
      '#description' => $this->t('Label for the button to give consent.'),
      '#required' => TRUE,
    ];
    $form['info_block']['noconsent_button']['noconsent_button_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('No consent button label'),
      '#default_value' => !empty($config->get('noconsent_button_label')) ? $config->get('noconsent_button_label') : 'No thanks',
      '#description' => $this->t('Label for the button to decline consent.'),
      '#required' => TRUE,
    ];

    // Status block.

    $form['status_block'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Status block'),
    ];
    $form['status_block']['status_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Status message'),
      '#default_value' => !empty($config->get('status_text')['value']) ? $config->get('status_text')['value']: 'Currently you have given [[status]] for optional cookies.',
      '#description' => $this->t('Used in the block where the user can see the consent status and alter it. Use the [[status]] placeholder where the current consent status should be printed out.'),
      '#required' => TRUE,
      '#format' => !empty($config->get('status_text')['format']) ? $config->get('status_text')['format']: 'editor',
    ];
    $form['status_block']['cookie_consent_status_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie consent status text'),
      '#default_value' => !empty($config->get('cookie_consent_status_text')) ? $config->get('cookie_consent_status_text') : 'consent',
      '#description' => $this->t('Label for the consent status text, this gets inserted in the [[status]] placeholder in the text above.'),
      '#required' => TRUE,
    );
    $form['status_block']['cookie_noconsent_status_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie noconsent status text'),
      '#default_value' => !empty($config->get('cookie_noconsent_status_text')) ? $config->get('cookie_noconsent_status_text') : 'no consent',
      '#description' => $this->t('Label for the no consent status text, this gets inserted in the [[status]] placeholder in the text above.'),
      '#required' => TRUE,
    );
    $form['status_block']['cookie_consent_link_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie consent link text'),
      '#default_value' => !empty($config->get('cookie_consent_link_text')) ? $config->get('cookie_consent_link_text') : 'Give my consent',
      '#description' => $this->t('Label for the link that allows users to give their consent.'),
      '#required' => TRUE,
    );
    $form['status_block']['cookie_noconsent_link_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cookie noconsent link text'),
      '#default_value' => !empty($config->get('cookie_noconsent_link_text')) ? $config->get('cookie_noconsent_link_text') : 'Withdraw my consent',
      '#description' => $this->t('Label for the link that allows users to withdraw their consent.'),
      '#required' => TRUE,
    );

    // Delete block.

    $form['delete_block'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Delete block'),
    ];
    $form['delete_block']['delete_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Delete message'),
      '#default_value' => !empty($config->get('delete_text')['value']) ? $config->get('delete_text')['value']: 'Delete all cookies from this domain',
      '#description' => $this->t('Used in the block where the user can delete all cookies.'),
      '#format' => !empty($config->get('delete_text')['format']) ? $config->get('delete_text')['format']: 'editor',
    ];
    $form['delete_block']['delete_button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cookie delete button text'),
      '#default_value' => !empty($config->get('delete_button_text')) ? $config->get('delete_button_text') : 'Delete these cookies',
      '#description' => $this->t('Label for the button that deletes all cookies.'),
      '#required' => TRUE,
    ];
    $form['delete_block']['delete_cookie_successful_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cookie delete success message'),
      '#default_value' => !empty($config->get('delete_cookie_successful_text')) ? $config->get('delete_cookie_successful_text') : 'Cookies successfully deleted',
      '#description' => $this->t('Notification for the user that all cookies are successfully deleted.'),
      '#required' => TRUE,
    ];

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
      ->set('consent_button_label', $form_state->getValue('consent_button_label'))
      ->set('cookie_consent_value', $form_state->getValue('cookie_consent_value'))
      ->set('noconsent_button_label', $form_state->getValue('noconsent_button_label'))
      ->set('cookie_noconsent_value', $form_state->getValue('cookie_noconsent_value'))
      ->set('status_text', $form_state->getValue('status_text'))
      ->set('cookie_consent_link_text', $form_state->getValue('cookie_consent_link_text'))
      ->set('cookie_noconsent_link_text', $form_state->getValue('cookie_noconsent_link_text'))
      ->set('cookie_consent_status_text', $form_state->getValue('cookie_consent_status_text'))
      ->set('cookie_noconsent_status_text', $form_state->getValue('cookie_noconsent_status_text'))
      ->set('delete_text', $form_state->getValue('delete_text'))
      ->set('delete_button_text', $form_state->getValue('delete_button_text'))
      ->set('delete_cookie_successful_text', $form_state->getValue('delete_cookie_successful_text'))
      ->save();
  }

}
