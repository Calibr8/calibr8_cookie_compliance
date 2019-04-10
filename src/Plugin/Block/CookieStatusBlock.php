<?php

namespace Drupal\calibr8_cookie_compliance\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Component\Utility\Xss;

/**
 * Provides a Cookie status block where the user can see his status and alter it.
 *
 * @Block(
 *   id = "cookie_status_block",
 *   admin_label = @Translation("Cookie status block"),
 *   category = @Translation("Calibr8"),
 * )
 */
class CookieStatusBlock extends BlockBase  {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('calibr8_cookie_compliance.settings');

    $status_text = [
      '#type' => 'processed_text',
      '#text' => str_replace('[[status]]',
        '<span id="calibr8-cookie-compliance-status-text-anchor" class="calibr8-cookie-compliance-status__label"></span>',
        $config->get('status_text')['value']),
      '#format' => $config->get('status_text')['format'],
    ];

    return [
      '#theme' => 'calibr8_cookie_compliance_status',
      '#status_text' => $status_text,
      '#consent_text' => $config->get('cookie_consent_status_text'),
      '#noconsent_text' => $config->get('cookie_noconsent_status_text'),
      '#consent_link_text' => $config->get('cookie_consent_link_text'),
      '#noconsent_link_text' => $config->get('cookie_noconsent_link_text'),
    ];
  }

}
