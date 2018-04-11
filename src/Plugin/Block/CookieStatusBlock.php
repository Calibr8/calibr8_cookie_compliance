<?php

namespace Drupal\calibr8_cookie_compliance\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

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

    return [
      '#theme' => 'calibr8_cookie_compliance_status',
      '#status_text' => new TranslatableMarkup($config->get('status_text')['value']),
      '#agree_text' => new TranslatableMarkup($config->get('cookie_agree_status_text')),
      '#disagree_text' => new TranslatableMarkup($config->get('cookie_disagree_status_text')),
      '#agree_link_text' => new TranslatableMarkup($config->get('cookie_agree_link_text')),
      '#disagree_link_text' => new TranslatableMarkup($config->get('cookie_disagree_link_text')),
    ];
  }

}
