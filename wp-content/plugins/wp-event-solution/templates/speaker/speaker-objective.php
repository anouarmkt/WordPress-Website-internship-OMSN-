<?php
defined( 'ABSPATH' ) || exit;

use \Etn\Utils\Helper;

if ( !empty( $objective ) ) {
    ?>		
    <p><?php echo Helper::render(trim( $objective )); ?></p>
    <?php
}