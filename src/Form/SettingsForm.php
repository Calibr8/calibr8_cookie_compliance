<?php

namespace Drupal\calibr8_cookie_compliance\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

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
      '#type' => 'textfield',
      '#title' => $this->t('Notification message'),
      '#default_value' => $config->get('notification_message'),
    ];

    $form['info_link'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Info link'),
    ];
    $form['info_link']['info_link_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $config->get('info_link_label'),
    ];

    $info_link_url = $config->get('info_link_url');
    $url = '';
    if (isset($info_link_url['route_name'])) {
      $url_object = Url::fromRoute($info_link_url['route_name'], $info_link_url['route_parameters']);
      $url = $url_object->toString();
    }

    $form['info_link']['info_link_url'] = [
      '#type' => 'path',
      '#title' => $this->t('URL'),
      '#description' => $this->t('Usually links to a disclaimer page.'),
      '#default_value' => $url,
    ];

    $form['accept_link'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Accept link'),
    ];
    $form['accept_link']['accept_link_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Link style'),
      '#options' => [
        'close_icon' => $this->t('Close icon'),
        'button' => $this->t('Button'),
      ],
      '#default_value' => $config->get('accept_link_style'),
      '#required' => TRUE,
    ];
    $form['accept_link']['accept_link_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $config->get('accept_link_label'),
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
      ->set('info_link_label', $form_state->getValue('info_link_label'))
      ->set('info_link_url', $form_state->getValue('info_link_url'))
      ->set('accept_link_style', $form_state->getValue('accept_link_style'))
      ->set('accept_link_label', $form_state->getValue('accept_link_label'))
      ->save();
  }

}
