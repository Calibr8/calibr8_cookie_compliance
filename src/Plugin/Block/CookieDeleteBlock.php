<?php

namespace Drupal\calibr8_cookie_compliance\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a Cookie delete block where the user can delete his cookies.
 *
 * @Block(
 *   id = "cookie_delete_block",
 *   admin_label = @Translation("Cookie delete block"),
 *   category = @Translation("Calibr8"),
 * )
 */
class CookieDeleteBlock extends BlockBase  {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('calibr8_cookie_compliance.settings');

    $delete_text = [
      '#type' => 'processed_text',
      '#text' => $config->get('delete_text')['value'],
      '#format' => $config->get('delete_text')['format'],
    ];

    return [
      '#theme' => 'calibr8_cookie_compliance_delete',
      '#delete_text' => $delete_text,
      '#delete_button_text' => $config->get('delete_button_text'),
      '#delete_cookie_successful_text' => $config->get('delete_cookie_successful_text'),
    ];
  }

}
