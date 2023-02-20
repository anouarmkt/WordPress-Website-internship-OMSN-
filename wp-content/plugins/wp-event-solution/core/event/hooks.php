<?php

namespace Etn\Core\Event;

use \Etn\Core\Event\Pages\Event_single_post;
use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Hooks {

    use \Etn\Traits\Singleton;

    public $cpt;
    public $action;
    public $base;
    public $category;
    public $tags;
    public $event;
    public $settings;

    public $actionPost_type = ['etn'];

    public function Init() {

        $this->cpt      = new Cpt();
        $this->category = new Category();
        $this->tags     = new Tags();
        $this->action   = new Action();

        $this->add_metaboxes();
        $this->add_taxonomy_menu();
        $this->add_single_page_template();
        $this->initialize_template_hooks();
        $this->prepare_post_taxonomy_columns();

        add_filter( "etn_form_submit_visibility", [$this, "form_submit_visibility"], 10, 2 );
        add_filter('template_include', [$this, 'etn_search_template_chooser']); 

        // sorting event by start date
        add_action('restrict_manage_posts', [$this, 'sort_event_by_date']);
        add_filter('parse_query', [$this, 'event_filter_request_query']);
        add_action( 'init', [$this, 'create_taxonomy_pages'], 99999 );

        include_once self::get_dir() . 'api.php';

        // add header in custom post type and taxonomy page
        add_action('admin_notices',[$this,'etn_post_type_add_header']);

    }

    /**
     * add header in speaker,zoom,attendee,schedule,location page
     */
    public function etn_post_type_add_header(){
        $event_post_type = ['etn','etn-schedule','etn-speaker','etn-zoom-meeting','etn-attendee'];
        if ( ( !empty($_GET['post']) && 'etn' == get_post_type( $_GET['post'] )) ||
        ( !empty($_GET['post_type']) && in_array( $_GET['post_type'] , $event_post_type ) ) ) {
            // header start.
            include_once ETN_PLUGIN_TEMPLATE_DIR . "layout/header.php";
            //  header end
        }

    }

    
    /**
     * get user module url
     *
     * @return string
     */
    public static function get_url() {
        return \Wpeventin::core_url() . 'event/';
    }

    /**
     * get user module directory path
     *
     * @return string
     */
    public static function get_dir() {
        return \Wpeventin::core_dir() . 'event/';
    }

    /**
     * Result of query
     */
    public function event_filter_request_query($query){
        if (!(is_admin()) && $query->is_main_query()) {
            return $query;
        }
        $search_value = isset($_GET['event_type']) ? sanitize_text_field($_GET['event_type']) : null;
        if (!isset($query->query['post_type']) || ('etn' !== $query->query['post_type']) || !isset($search_value) ) {
            return $query;
        }

        if ( $search_value !== '') {
            $meta = [];

            if (!isset($query->query_vars['meta_query'])) {
                $query->query_vars['meta_query'] = array();
            }
            if ( $search_value == 'etn_start_date_past' || $search_value == 'etn_start_date_upcoming' ) {
                $query->set( 'meta_key', 'etn_start_date' );
                $query->set( 'order', 'ASC' );
                $query->set( 'orderby', 'meta_value');
                
                if ($search_value == 'etn_start_date_past') {
                    $compare = "<=";
                }
                else if ($search_value == 'etn_start_date_upcoming') {
                    $compare = ">=";
                } 
                
                // setup this functions meta values
                $meta[] = array(
                    'key'           => 'etn_start_date',
                    'meta-value'    => 'ASC',
                    'value'         => date('Y-m-d'),
                    'compare'       => $compare,
                    'type'          => 'CHAR'
                );
            }

            $search_data = ['Past','Ongoing','Upcoming'];
            if ( in_array( $search_value , $search_data)) {
                // pro filter query
                $meta = apply_filters('etn/event_parse_query', $meta , $search_value );
            }

            // append to meta_query array
            $query->query_vars['meta_query'][] = $meta;
        }

        return $query;
    }

    /**
     * sorting event by start date
     */
    public function sort_event_by_date(){
        global $typenow;
        if ($typenow == 'etn') {
            
            $options = array( 'etn_start_date_past'=> esc_html__('Event by start date past' , 'eventin'),
            'etn_start_date_upcoming'=> esc_html__('Event by start date upcoming' , 'eventin') );
            // get pro filter param
            $filter_options = apply_filters('etn/event_filter' , $options) ;

            $selected = '';
            if ((isset($_GET['event_type']))  && isset($_GET['post_type'])
                && !empty(sanitize_text_field($_GET['event_type'])) &&  sanitize_text_field($_GET['post_type']) == 'etn'
            ) {
                $selected = sanitize_text_field($_GET['event_type']);
            }
            ?>
            <select name="event_type">
                <?php
                foreach ( $filter_options as $key=>$value ) :
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

    // Search template redirect to event archive page
    public function etn_search_template_chooser($template)  {
        global $wp_query;

        $post_type  = get_post_type(get_the_ID());
        $post       = get_post( get_the_ID() );
        $post_slug  = !empty( $post ) ? $post->post_name : null;

        if( $wp_query->is_search && $post_type == 'etn' && file_exists( \Wpeventin::core_dir() . 'event/views/event-archive-page.php' ) )   
        {
            return \Wpeventin::core_dir() . 'event/views/event-archive-page.php';
        }

        if (!empty($post_slug ) && ( $post_slug == "etn-tags" || $post_slug == "etn_category" || $post_slug == "etn-speaker-category" ) ) {

            return \Wpeventin::core_dir() . 'event/views/event-taxonomy-page.php';
        }

        return $template;
    }

    public function add_metaboxes() {
        $event_metabox = new \Etn\Core\Metaboxs\Event_meta();
        add_action( 'add_meta_boxes', [$event_metabox, 'register_meta_boxes'] );
        add_action( 'save_post', [$event_metabox, 'save_meta_box_data'] );
    }

    public function initialize_template_hooks() {
        include_once \Wpeventin::plugin_dir() . 'core/event/template-hooks.php';
        include_once \Wpeventin::plugin_dir() . 'core/event/template-functions.php';
    }

    public function prepare_post_taxonomy_columns() {
        //Add column
        add_filter( 'manage_etn_posts_columns', [$this, 'event_column_headers'] );
        add_action( 'manage_etn_posts_custom_column', [$this, 'event_column_data'], 10, 2 );

        add_filter( "manage_edit-etn_category_columns", [$this, 'category_column_header'], 10 );
        add_action( "manage_etn_category_custom_column", [$this, 'category_column_content'], 10, 3 );

        add_filter( "manage_edit-etn_tags_columns", [$this, 'category_column_header'], 10 );
        add_action( "manage_etn_tags_custom_column", [$this, 'category_column_content'], 10, 3 );
    }

    function category_column_header( $columns ) {
        $new_item["id"] = esc_html__( "Id", "eventin" );
        $new_array      = array_slice( $columns, 0, 1, true ) + $new_item + array_slice( $columns, 1, count( $columns ) - 1, true );
        return $new_array;
    }

    function category_column_content( $content, $column_name, $term_id ) {
        return $term_id;
    }

    function add_taxonomy_menu() {
        $this->category->menu();
        $this->tags->menu();
        if( class_exists('Wpeventin_Pro') && class_exists('\Etn_Pro\Core\Event\Event_Location') ) {
            $event_location = \Etn_Pro\Core\Event\Event_Location::instance();
            $event_location->menu();
            $event_location->init();
        }
    }

    function add_single_page_template() {
        $page = new Event_single_post();
    }

    /**
     * Column name
     */
    public function event_column_headers( $columns ) {
        unset( $columns['date'] );
        $new_item["id"]                 = esc_html__( "Id", "eventin" );
        $another_item["is_recurring"]   = esc_html__( "Recurring", "eventin" );
        $new_array                      = array_slice( $columns, 0, 1, true ) + $new_item + array_slice( $columns, 0, 2, true ) + $another_item + array_slice( $columns, 2, count( $columns ) - 1, true );
        $new_array['etn_start_date']    = esc_html__('Event Start Date',  'eventin' );
        return $new_array;
    }

    /**
     * Return row
     */
    public function event_column_data( $column, $post_id ) {

        switch ( $column ) {
            case 'id':
                echo intval( $post_id );
                break;
            case 'is_recurring':
                $is_recurring_parent = Helper::get_child_events( $post_id );

                if(Helper::is_recurrence( $post_id )){
                    ?>
                    <div class="etn-event-dashboard-recurrence etn-event-dashboard-recurrence-child"><?php echo esc_html__('Yes - Recurrence', 'eventin');?></div>
                    <?php
                }else{
                    if( !$is_recurring_parent ){
                        ?>
                        <div class="etn-event-dashboard-recurrence etn-event-dashboard-recurrence-no"><?php echo esc_html__('No', 'eventin');?></div>
                        <?php
                    } elseif( is_array( $is_recurring_parent ) && !empty( $is_recurring_parent ) ) {
                        ?>
                        <div class="etn-event-dashboard-recurrence etn-event-dashboard-recurrence-parent "><?php echo esc_html__('Yes - Parent', 'eventin');?></div>
                        <?php
                    }
                }
                break;
            case 'etn_start_date':
                echo esc_html( get_post_meta($post_id,'etn_start_date',true).' '.get_post_meta($post_id,'etn_start_time',true) );
                break;
        }

    }

    
    /**
     * set form submission button visibility
     *
     * @param [type] $visible
     * @param [type] $post_id
     * @return void
     */
    public function form_submit_visibility( $visible, $post_id ) {

        //get disable option setting from db
        $settings                        = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $disable_registration_if_expired = isset( $settings['disable_registration_if_expired'] ) ? true : false;
        $is_visible                      = true;

        //get expiry date condition from db
        $selected_expiry_point  = (isset($settings['expiry_point']) && "" != $settings['expiry_point']) ? $settings['expiry_point'] : "start";
        $event_expire_date_time = "";

        if ( $selected_expiry_point == "start" ) {
            //event start date-time
            $event_expire_date      = !empty( get_post_meta( $post_id, "etn_start_date", true ) ) && !is_null( get_post_meta( $post_id, "etn_start_date", true ) ) ? get_post_meta( $post_id, "etn_start_date", true ) : "";
            $event_expire_time      = !empty( get_post_meta( $post_id, "etn_start_time", true ) ) && !is_null( get_post_meta( $post_id, "etn_start_time", true ) ) ? get_post_meta( $post_id, "etn_start_time", true ) : "";
            $event_expire_date_time = trim( $event_expire_date . " " . $event_expire_time );
        } elseif ( $selected_expiry_point == "end" ) {
            //event end date-time
            $event_expire_date      = !empty( get_post_meta( $post_id, "etn_end_date", true ) ) && !is_null( get_post_meta( $post_id, "etn_end_date", true ) ) ? get_post_meta( $post_id, "etn_end_date", true ) : "";
            $event_expire_time      = !empty( get_post_meta( $post_id, "etn_end_time", true ) ) && !is_null( get_post_meta( $post_id, "etn_end_time", true ) ) ? get_post_meta( $post_id, "etn_end_time", true ) : "";
            $event_expire_date_time = trim( $event_expire_date . " " . $event_expire_time );
        }

        // if disable option is on and expire date has passed
        //  then do not show form submit button
        if ( !$disable_registration_if_expired || ( "" == $event_expire_date_time ) ) {
            $is_visible = true;
        } else {
            $current_time = time();
            $expire_time  = strtotime( $event_expire_date_time );
            if ( $current_time > $expire_time ) {
                $is_visible = false;
            } else {
                $is_visible = true;
            }
        }

        return $is_visible;

    }

    public function create_taxonomy_pages(){
        $this->category->create_page();
        $this->tags->create_page();
    }

}
