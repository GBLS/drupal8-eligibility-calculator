<?php

namespace Drupal\gbls_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'footer_socialmedia' block.
 *
 * @Block(
 *  id = "footer_socialmedia",
 *  admin_label = @Translation("Social media logos"),
 * )
 */
class footer_socialmedia_logo extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
        $build = [];
        $build['footer_socialmedia_logo']['#markup'] =
            '<a href="https://www.facebook.com/BostonLegalAid/" target="_blank"><img class="connect-image" src="' . '/themes/gbls/assets/facebook-icon-transparent.svg" alt="Facebook logo"></a>
            <a href="https://twitter.com/BostonLegalAid" target="_blank"><img class="connect-image" src="' . '/themes/gbls/assets/twitter-icon-transparent.svg" alt="Twitter logo"></a>';

        return $build;
    }

}
