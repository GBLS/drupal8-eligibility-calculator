<?php

namespace Drupal\gbls_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'FormEmbed' block.
 *
 * @Block(
 *  id = "form_embed",
 *  admin_label = @Translation("Form Embed"),
 * )
 */
class FormEmbed extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['forms_embed'] = [
     '#theme' => 'gbls_form_embed',
     '#attached' => ['library' => ['gbls_blocks/forms_embed']],
    ];
    return $build;
  }

}
