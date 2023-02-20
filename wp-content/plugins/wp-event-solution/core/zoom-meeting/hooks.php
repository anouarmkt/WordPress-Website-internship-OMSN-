<?php

namespace Etn\Core\Zoom_Meeting;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Hooks {

    use \Etn\Traits\Singleton;

    public $cpt;
    public $base;
    public $category;
    public $tags;
    public $settings;
    public $event_action;
    public $post_type = 'etn-zoom-meeting';

    /**
     * Main hook function
     *
     * @return void
     */
    public function init() {

        $settings    = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $zoom_module = ! empty( $settings['etn_zoom_api'] ) ? true : false;
        
        if( $zoom_module ) {
            // working Zoom module
            \Etn\Core\Zoom_Meeting\Cpt::instance();

              // category
            \Etn\Core\Zoom_Meeting\Category::instance();
            // tag
            \Etn\Core\Zoom_Meeting\Tags::instance();

            // call ajax submit
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                \Etn\Core\Zoom_Meeting\Ajax_Action::instance()->init();
            }
                  
            // custom post meta
            $_metabox = new \Etn\Core\Zoom_Meeting\Zoom_Meeting_Meta();
            add_action( 'add_meta_boxes', [$_metabox, 'register_meta_boxes'] );
            add_action( 'save_post', [$_metabox, 'save_meta_box_data'] );
            add_filter( 'wp_insert_post_data', [$_metabox, 'save_zoom_meta_data'], 500, 2 );

            if ( Helper::is_webinar_user() ) {
                add_action( 'restrict_manage_posts', [$this, 'sort_zoom_by_type'] );
            }
            
            add_filter( 'parse_query', [$this, 'zoom_filter_request_query'] );

            add_action( 'save_post', [$this, 'update_zoom_duration_password'], 20, 2 );
            add_action( 'admin_notices', [$_metabox, 'admin_notices'] );
            
            // before_delete_post 
            add_action( 'before_delete_post', [ $_metabox, 'delete_zoom_meeting' ], 10, 2 );

            //Add column
            add_filter( 'manage_etn-zoom-meeting_posts_columns', [$this, 'zoom_column_headers'] );
            add_action( 'manage_etn-zoom-meeting_posts_custom_column', [$this, 'zoom_column_data'], 10, 2 );

            // Disable gutenberg
            add_filter('use_block_editor_for_post_type', [$_metabox, 'disable_gutenberg'],10,2);

            add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_assets' ] );
        }
    }

    public function admin_enqueue_assets() {

        // get screen id
        $screen    = get_current_screen();
        $screen_id = $screen->id;

        $allowed_screen_ids = [
            'etn-zoom-meeting',
            'edit-etn-zoom-meeting',
        ];

        if( in_array($screen_id, $allowed_screen_ids) ){
            wp_enqueue_script( 'etn-zoom', \Wpeventin::core_url() . 'zoom-meeting/assets/js/script.js', ['jquery'], \Wpeventin::version(), false );

            $localized_data                      = [];
            $localized_data['ajax_url']          = admin_url( 'admin-ajax.php' );
            $localized_data['zoom_sync_nonce']   = wp_create_nonce( 'zoom_sync_nonce' );
            $localized_data['sync_with_zoom']    = esc_html__( 'Synchronization with Zoom', 'eventin' );
            $localized_data['sync_confirmation'] = esc_html__( 'Are you sure you want to synchronize Zoom?', 'eventin' );
    
            wp_localize_script( 'etn-zoom', 'zoom_localized_data', $localized_data );
        }
    }

      /**
     * update zoom duration and password meta data
     *
     * @param [type] $post_id
     * @param [type] $post
     * @return void
     */
    public function update_zoom_duration_password( $post_id, $post ) {

        if ( $post->post_type == 'etn-zoom-meeting' ) {
            $meeting_id   = get_post_meta( $post_id, 'zoom_meeting_id', true );
            $meeting_type = get_post_meta( $post_id, 'zoom_meeting_type', true );

            $sync_type = 'meetings';
            if ( $meeting_type == '5' ) {
                $sync_type = 'webinars';
            }

            if ( !empty( $meeting_id ) ) {
                $meeting_details = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $meeting_id, $sync_type ) );
                
                if ( is_object( $meeting_details ) ) {
                    $duration = isset( $meeting_details->duration ) ? $meeting_details->duration : 60;
                    $password = isset( $meeting_details->password ) ? $meeting_details->password : $post_id;

                    update_post_meta( $post_id , 'zoom_duration' , $duration );
                    update_post_meta( $post_id , 'zoom_password' , $password );
                } 
            }
        }
    }

    /**
     * sorting zoom by type: meeting/webinar
     */
    public function sort_zoom_by_type(){
        global $typenow;
        if ($typenow == 'etn-zoom-meeting') {
            
            $zoom_options = [ 
                ''  => esc_html__( 'All types' , 'eventin' ),
                '2' => esc_html__( 'Meeting' , 'eventin' ),
                '5' => esc_html__( 'Webinar' , 'eventin' ) 
            ];

            $selected = '';
            if ((isset($_GET['zoom_type']))  && isset($_GET['post_type'])
                && !empty(sanitize_text_field($_GET['zoom_type'])) &&  sanitize_text_field($_GET['post_type']) == 'etn-zoom-meeting'
            ) {
                $selected = sanitize_text_field($_GET['zoom_type']);
            }
            ?>
            <select name="zoom_type">
                <?php
                foreach ( $zoom_options as $key=>$value ) :
                    $select = ( $key == $selected ) ? ' selected="selected"' : '';
                    ?>
                    <option value="<?php echo esc_html( $key ); ?>" 
                        <?php echo esc_html($select) ?>><?php echo sprintf('%s',$value); ?>
                    </option>
                    <?php
                endforeach;
                ?>
            </select>
            <?php
        } 
    }

    /**
     * Result of query
     */
    public function zoom_filter_request_query($query){
        if (!(is_admin()) && $query->is_main_query()) {
            return $query;
        }
        $search_value = isset($_GET['zoom_type']) ? sanitize_text_field($_GET['zoom_type']) : null;
        if (!isset($query->query['post_type']) || ('etn-zoom-meeting' !== $query->query['post_type']) || !isset($search_value) ) {
            return $query;
        }

        if ( $search_value !== '') {
            $meta = [];

            if (!isset($query->query_vars['meta_query'])) {
                $query->query_vars['meta_query'] = array();
            }

            if ( $search_value == '2' || $search_value == '5' ) {
                $query->set( 'meta_key', 'zoom_meeting_type' );
                $query->set( 'order', 'ASC' );
                $query->set( 'orderby', 'meta_value');
                
                $compare = '=';
                
                // setup this functions meta values
                $meta[] = array(
                    'key'           => 'zoom_meeting_type',
                    'meta-value'    => 'ASC',
                    'value'         => $search_value,
                    'compare'       => $compare,
                    'type'          => 'STRING'
                );
            }

            // append to meta_query array
            $query->query_vars['meta_query'][] = $meta;
        }

        return $query;
    }

    /**
     * Column name
     */
    public function zoom_column_headers( $columns ) {
        $id_item["id"]                  = esc_html__( "Id", "eventin" );
        
        $new_item["type"]               = esc_html__( "Type", "eventin" );
        $new_item["zoom_id"]            = esc_html__( "Zoom ID", "eventin" );
        // $new_item["status"]             = esc_html__( "Status", "eventin" );
        $new_item["duration"]           = esc_html__( "Duration (minutes)", "eventin" );
        $new_item["start_time"]         = esc_html__( "Start Time", "eventin" );
        $new_item["start_url"]          = esc_html__( "Action", "eventin" );

        $new_array = array_slice( $columns, 0, 1, true ) + $id_item + 
                    array_slice( $columns, 1, 1, true ) + $new_item;
                    // + array_slice( $columns, 2, count( $columns ) - 2, true );
        return $new_array;
    }

    /**
     * Return row
     */
    public function zoom_column_data( $column, $post_id ) {
        $zoom_info = [];
        $post      = get_post( $post_id );
        if ( 'etn-zoom-meeting' == $post->post_type ) {
            $meeting_id = get_post_meta( $post_id, 'zoom_meeting_id', true );
            if ( !empty( $meeting_id ) ) {
                // $zoom_info = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $meeting_id ), true );
            }
        }

        switch ( $column ) {
            case 'id':
                echo intval( $post_id );
                break;
        }

        $zoom_meeting_id = get_post_meta( $post_id, 'zoom_meeting_id', true );

        if ( !empty( $zoom_meeting_id ) ) {
            switch ( $column ) {
                case 'zoom_id':
                    echo esc_html( $zoom_meeting_id );
                    break;
                case 'type':
                    $type = '';
    
                    $saved_type = get_post_meta( $post_id, 'zoom_meeting_type', true );
                    if ( !empty( $saved_type ) ) {
                        if ( $saved_type == '2' ) {
                            $type = esc_html__( 'Meeting', 'eventin' );
                        } else if ( $saved_type == '5' ) {
                            $type = esc_html__( 'Webinar', 'eventin' );
                        }
                    }
    
                    echo esc_html( $type );
                    break;
                case 'status':
                    echo ucfirst( get_post_meta( $post_id, 'zoom_meeting_status', true ) );
                    break;
                case 'duration':
                    echo get_post_meta( $post_id, 'zoom_duration', true );
                    break;
                case 'start_url':
                    $zoom_start_url = get_post_meta( $post_id, 'zoom_start_url', true );
                    if (!empty($zoom_start_url)) {
                        $zoom_start_url = '<a href="' . esc_url( $zoom_start_url ) . '" target="_blank" rel="noopener">'. esc_html__( 'Start', 'eventin' ) .'</a>';
                    } 
                    echo esc_url($zoom_start_url);
                    break;
                case 'start_time':
                    $converted_start_time = '';
    
                    $zoom_start_time = get_post_meta( $post_id, 'zoom_start_time', true );
                    if ( !empty( $zoom_start_time ) ) {
                        $converted_start_time = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->convert_meeting_date_time( $zoom_start_time );
                    }
                    
                    echo esc_html($converted_start_time);
                    break;
            }
        }

    }

}
