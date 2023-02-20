<?php

use \Etn\Utils\Helper as Helper;

//register speaker block
function etn_register_speaker_list_block() {
    register_block_type(
        'etn/speaker-list',
        [
            // Enqueue blocks.style.build.css on both frontend & backend.
            'style'           => 'eventin-block-style-css',
            // Enqueue blocks.editor.build.css in the editor only.
            'editor_style'    => 'eventin-block-editor-style-css',
            // Enqueue blocks.build.js in the editor only.
            'editor_script'   => 'eventin-block-js',
            'render_callback' => 'etn_speaker_list_callback',
            'attributes'      => [
                'speaker_style'     => [
                    'type'    => 'string',
                    'default' => 'speaker-2',
                ],
                'speaker_id'        => [
                    'type'    => 'string',
                    'default' => '',
                ],
                'speakers_category' => [
                    'type'    => 'array',
                    'default' => []
                ],
                'etn_speaker_count' => [
                    'type'    => 'integer',
                    'default' => 20,
                ],

                'etn_speaker_col'   => [
                    'type'    => 'string',
                    'default' => '4',
                ],
                'etn_speaker_order' => [
                    'type'    => 'string',
                    'default' => 'DESC',
                ],
            ],
        ]
    );
}
add_action( 'init', 'etn_register_speaker_list_block' );

// speaker list block callback
function etn_speaker_list_callback( $settings ) {

    $style              = $settings["speaker_style"];
    $speaker_id         = $settings["speaker_id"];
    $etn_speaker_count  = $settings["etn_speaker_count"];
    $etn_speaker_col    = $settings["etn_speaker_col"];
    $etn_speaker_order  = $settings["etn_speaker_order"];
    $speakers_category  = $settings["speakers_category"];

    $post_attributes    = ['title', 'ID', 'name', 'post_date'];
    $orderby            = !empty( $settings["orderby"] ) ? $settings["orderby"] : 'title';
    $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';

    ob_start();
    ?>
	<div class="guten-speaker-blocks">
		<?php
		if( file_exists(\Wpeventin::plugin_dir() . "widgets/speakers/style/{$style}.php") ){
			include \Wpeventin::plugin_dir() . "widgets/speakers/style/{$style}.php";
		}
    	?>
	</div>
	<?php

    return ob_get_clean();
}
