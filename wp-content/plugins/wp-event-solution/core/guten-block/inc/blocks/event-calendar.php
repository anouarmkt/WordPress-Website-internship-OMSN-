<?php

use \Etn\Utils\Helper as Helper;

//register event block
function etn_register_event_calendar_block() {
    register_block_type(
        'etn/event-calendar',
        [
            // Enqueue blocks.style.build.css on both frontend & backend.
            'style'           => 'eventin-block-style-css',
            // Enqueue blocks.editor.build.css in the editor only.
            'editor_style'    => 'eventin-block-editor-style-css',
            // Enqueue blocks.build.js in the editor only.
            'editor_script'   => 'eventin-block-js',
            'render_callback' => 'etn_event_calendar_callback',
            'attributes'      => [
                'etn_event_style' => [
                    'type'    => 'string',
                    'default' => 'style-1',
                ],
                'etn_event_cat'   => [
                    'type'    => 'array',
                    'default' => []
                ],
               
                'etn_event_count' => [
                    'type'    => 'integer',
                    'default' => 20,
                ], 
                'display_calendar_view'   => [
                    'type'    => 'string',
                    'default' => 'full_width',
                ], 
                'show_desc'           => [
                    'type'    => 'string',
                    'default' => 'no',
                ],
            ],
        ]
    );
}
add_action( 'init', 'etn_register_event_calendar_block' );

// event list block callback
function etn_event_calendar_callback( $settings ) {

    $style              = $settings["etn_event_style"];
    $event_cat          = $settings["etn_event_cat"];
    $event_count        = $settings["etn_event_count"]; 
    $calendar_view      = $settings["display_calendar_view"]; 
    $show_desc          = $settings["show_desc"]; 
    $event_cats         =  join(", ",$event_cat);
     ob_start();
    ?>
	<div class="guten-event-calendar-blocks">
		<?php
	 
        echo do_shortcode("[events_calendar style ={$style} event_cat_ids='{$event_cats}'  calendar_show={$calendar_view} show_desc={$show_desc} limit = {$event_count}]");
     	?>
        
	</div>
	<?php

    return ob_get_clean();
}
