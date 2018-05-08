<?php
/**
 * @file
 * This file provides administration form for the module.
 */

/**
 * Provides form for cookie control popup.
 */
function calibr8_cookie_compliance_admin_form($form_state) {
  $popup_settings = calibr8_cookie_compliance_get_settings();

  $form['calibr8_cookie_compliance'] = array (
    '#type'  => 'item',
    '#tree'   => TRUE,
  );

  $form['calibr8_cookie_compliance']['popup_agree_button_message'] = array(
    '#type' => 'textfield',
    '#title' => t('Agree button message'),
    '#default_value' => isset($popup_settings['popup_agree_button_message']) ? $popup_settings['popup_agree_button_message'] : t('OK'),
    '#size' => 30,
    '#required' => TRUE,
  );

  $form['calibr8_cookie_compliance']['popup_disagree_button_message'] = array(
    '#type' => 'textfield',
    '#title' => t('Disagree button message'),
    '#default_value' => isset($popup_settings['popup_disagree_button_message']) ? $popup_settings['popup_disagree_button_message'] : t('No, i do not agree'),
    '#size' => 30,
    '#required' => TRUE,
  );

  $form['calibr8_cookie_compliance']['popup_info'] = array(
    '#type' => 'text_format',
    '#title' => t('Popup message - requests consent'),
    '#default_value' => isset($popup_settings['popup_info']['value']) ? $popup_settings['popup_info']['value'] : '',
    '#required' => TRUE,
    '#format' => isset($popup_settings['popup_info']['format']) ? $popup_settings['popup_info']['format'] : filter_default_format(),
  );

  $form['calibr8_cookie_compliance']['status_text'] = [
    '#type' => 'text_format',
    '#title' => t('Status message'),
    '#description' => t('Used in the block where the user can see the cookie status and alter it.
     Use [[status]] where the current user status should be printed out.'),
    '#default_value' => isset($popup_settings['status_text']['value']) ? $popup_settings['status_text']['value'] : '',
    '#required' => TRUE,
    '#format' => isset($popup_settings['status_text']['format']) ? $popup_settings['status_text']['format'] : filter_default_format(),
  ];

  $form['calibr8_cookie_compliance']['cookie_agree'] = [
    '#type' => 'fieldset',
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
    '#title' => t('Cookie agree'),
  ];
  $form['calibr8_cookie_compliance']['cookie_agree']['cookie_agree_value'] = array(
    '#type' => 'textfield',
    '#title' => t('Cookie agree value'),
    '#default_value' => isset($popup_settings['cookie_agree']['cookie_agree_value']) ? $popup_settings['cookie_agree']['cookie_agree_value'] : 2,
    '#required' => TRUE,
  );
  $form['calibr8_cookie_compliance']['cookie_agree']['cookie_agree_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Cookie agree text'),
    '#description' => t('Will be shown to the users to let them know if the are opted in or not.'),
    '#default_value' => isset($popup_settings['cookie_agree']['cookie_agree_text']) ? $popup_settings['cookie_agree']['cookie_agree_text'] : 'agreed',
    '#required' => TRUE,
  );
  $form['calibr8_cookie_compliance']['cookie_agree']['cookie_agree_link_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Cookie agree link text'),
    '#description' => t('The text for the link that will allow users to give their consent.'),
    '#default_value' => isset($popup_settings['cookie_agree']['cookie_agree_link_text']) ? $popup_settings['cookie_agree']['cookie_agree_link_text'] : 'agreed',
    '#required' => TRUE,
  );

  $form['calibr8_cookie_compliance']['cookie_disagree'] = [
    '#type' => 'fieldset',
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
    '#title' => t('Cookie disagree'),
  ];
  $form['calibr8_cookie_compliance']['cookie_disagree']['cookie_disagree_value'] = array(
    '#type' => 'textfield',
    '#title' => t('Cookie disagree value'),
    '#default_value' => isset($popup_settings['cookie_disagree']['cookie_disagree_value']) ? $popup_settings['cookie_disagree']['cookie_disagree_value'] : 1,
    '#required' => TRUE,
  );
  $form['calibr8_cookie_compliance']['cookie_disagree']['cookie_disagree_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Cookie disagree text'),
    '#description' => t('Will be shown to the users to let them know if the are opted in or not.'),
    '#default_value' => isset($popup_settings['cookie_disagree']['cookie_disagree_text']) ? $popup_settings['cookie_disagree']['cookie_disagree_text'] : 'disagreed',
    '#required' => TRUE,
  );
  $form['calibr8_cookie_compliance']['cookie_disagree']['cookie_disagree_link_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Cookie disagree link text'),
    '#description' => t('The text for the link that will allow users to withdraw their consent.'),
    '#default_value' => isset($popup_settings['cookie_disagree']['cookie_disagree_link_text']) ? $popup_settings['cookie_disagree']['cookie_disagree_link_text'] : 'agreed',
    '#required' => TRUE,
  );

  $form['#submit'][] = '_calibr8_cookie_compliance_admin_form_submit';

  return system_settings_form($form);
}

/**
 * Form submit handler to update our i18n strings.
 */
function _calibr8_cookie_compliance_admin_form_submit($form, $form_state) {
  $lang = language_default('language');

  $popupInfoValue = filter_xss($form_state['values']['calibr8_cookie_compliance']['popup_info']['value']);
  _calibr8_cookie_compliance_translate_update('popup_info', $popupInfoValue, $lang);

  $statusTextValue = filter_xss($form_state['values']['calibr8_cookie_compliance']['status_text']['value']);
  _calibr8_cookie_compliance_translate_update('status_text', $statusTextValue, $lang);

  $cookieAgreeTextValue = filter_xss($form_state['values']['calibr8_cookie_compliance']['cookie_agree']['cookie_agree_text']);
  _calibr8_cookie_compliance_translate_update('cookie_agree_text', $cookieAgreeTextValue, $lang);

  $cookieAgreeLinkTextValue = filter_xss($form_state['values']['calibr8_cookie_compliance']['cookie_agree']['cookie_agree_link_text']);
  _calibr8_cookie_compliance_translate_update('cookie_agree_link_text', $cookieAgreeLinkTextValue, $lang);

  $cookieDisagreeTextValue = filter_xss($form_state['values']['calibr8_cookie_compliance']['cookie_disagree']['cookie_disagree_text']);
  _calibr8_cookie_compliance_translate_update('cookie_disagree_text', $cookieDisagreeTextValue, $lang);

  $cookieDisagreeLinkTextValue = filter_xss($form_state['values']['calibr8_cookie_compliance']['cookie_disagree']['cookie_disagree_link_text']);
  _calibr8_cookie_compliance_translate_update('cookie_disagree_link_text', $cookieDisagreeLinkTextValue, $lang);

  $popupAgreeButtonMessage = filter_xss($form_state['values']['calibr8_cookie_compliance']['popup_agree_button_message']);
  _calibr8_cookie_compliance_translate_update('popup_agree_button_message', $popupAgreeButtonMessage, $lang);

  $popupDisagreeButtonMessage = filter_xss($form_state['values']['calibr8_cookie_compliance']['popup_disagree_button_message']);
  _calibr8_cookie_compliance_translate_update('popup_disagree_button_message', $popupDisagreeButtonMessage, $lang);
}
