<?php

namespace Drupal\gbls_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'FooterCopyBlock' block.
 *
 * @Block(
 *  id = "footer_copy_block",
 *  admin_label = @Translation("Footer copy block"),
 * )
 */
class FooterCopyBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
        $build = [];
        $build['footer_info_block']['#markup'] = '<p>&copy;&nbsp;' . date("Y") . ' Greater Boston Legal Services. All rights reserved.</p>';

        return $build;
    }

}
