<?php

use \Etn\Utils\Helper as Helper;

//register schedule block
function etn_register_schedule_tab_block() {
    register_block_type(
        'etn/schedule-tab',
        [
            // Enqueue blocks.style.build.css on both frontend & backend.
            'style'           => 'eventin-block-style-css',
            // Enqueue blocks.editor.build.css in the editor only.
            'editor_style'    => 'eventin-block-editor-style-css',
            // Enqueue blocks.build.js in the editor only.
            'editor_script'   => 'eventin-block-js',
            'render_callback' => 'etn_schedule_tab_callback',
            'attributes'      => [
                'schedule_style'     => [
                    'type'    => 'string',
                    'default' => 'schedule-1',
                ],
                'schedule_id'        => [
                    'type'    => 'array',
                    'default' => []
                ],

                'etn_schedule_order' => [
                    'type'    => 'string',
                    'default' => 'DESC',
                ],
            ],
        ]
    );
}
add_action( 'init', 'etn_register_schedule_tab_block' );

// schedule block callback
function etn_schedule_tab_callback( $settings ) {

    $style              = $settings["schedule_style"];
    $etn_schedule_order = $settings["etn_schedule_order"];
    $etn_schedule_ids   = $settings["schedule_id"];
    $order              = isset( $etn_schedule_order ) ? $etn_schedule_order : 'ASC';

    ob_start();
    ?>
	<div class="guten-schedule-blocks">
		<?php
		if( file_exists(\Wpeventin::plugin_dir() . "widgets/schedule/style/{$style}.php") ){
			include \Wpeventin::plugin_dir() . "widgets/schedule/style/{$style}.php";
		}
    	?>
	</div>
	<?php

    return ob_get_clean();
}
