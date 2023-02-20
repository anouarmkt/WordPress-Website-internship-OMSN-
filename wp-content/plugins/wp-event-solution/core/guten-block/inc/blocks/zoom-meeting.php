<?php

use \Etn\Utils\Helper as Helper;

//register zoom meeting block
function etn_register_zoom_meeting_block() {
    register_block_type(
        'etn/zoom-meeting',
        [
            // Enqueue blocks.style.build.css on both frontend & backend.
            'style'           => 'eventin-block-style-css',
            // Enqueue blocks.editor.build.css in the editor only.
            'editor_style'    => 'eventin-block-editor-style-css',
            // Enqueue blocks.build.js in the editor only.
            'editor_script'   => 'eventin-block-js',
            'render_callback' => 'etn_zoom_meeting_callback',
            'attributes'      => [
                'zoom_id'   => [
                    'type' => 'string',
                ],
                'link_only' => [
                    'type'    => 'string',
                    'default' => 'no',

                ],

            ],
        ]
    );
}
add_action( 'init', 'etn_register_zoom_meeting_block' );

// zoom block callback
function etn_zoom_meeting_callback( $settings ) {
    $zoom_id    = !empty( $settings["zoom_id"] ) ?  $settings["zoom_id"] : '';
    $link_only  = !empty( $settings["link_only"] ) ?  $settings["link_only"] : '';
    $meeting_id = get_post_meta( $zoom_id, 'zoom_meeting_id', true );

    ob_start();
    ?>
	<div class="guten-zoom-blocks">
		<?php echo do_shortcode( "[etn_zoom_api_link meeting_id ={$meeting_id} link_only={$link_only}]" );?>
	</div>
	<?php

    return ob_get_clean();
}
