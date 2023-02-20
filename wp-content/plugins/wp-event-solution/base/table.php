<?php
namespace Etn\Base;

defined('ABSPATH') || exit;

if ( ! class_exists( 'WP_List_Table' )){
    require_once ABSPATH . 'wp-admin/inclueds/class-wp-list-table.php';
}

class Table extends \WP_List_Table{

    public $textdomain = 'eventin';
    public $singular_name;
    public $plural_name;
    public $id = '';
    public $columns = [];
    
    /**
     * Show list
     */
    function __construct($all_data_of_table){

        $this->singular_name = $all_data_of_table['singular_name'];
        $this->plural_name   = $all_data_of_table['plural_name'];
        $this->id            = $all_data_of_table['event_id'];
        $this->columns       = $all_data_of_table['columns'];

        parent::__construct( [
            'singular' => $this->singular_name ,
            'plural'   => $this->plural_name ,
            'ajax'     => true ,
        ]);
    }
    
    /**
     * Get column header function
     */
    public function get_columns(){

        return $this->columns;
    }
    

    /**
     * Sortable column function
     */
    public function get_sortable_columns() {

        return $this->columns;
    }

    /**
     * Display all row function
     */
    protected function column_default( $item , $column_name ){
        switch( $column_name ) { 
            case $column_name:
                return  $item[$column_name];
            default:
                isset( $item[$column_name] ) ? $item[$column_name] : '';
            break;
          }
    }

    /**
     * Add action link 
     */
    protected function row_actions( $actions, $always_visible = false ) {
        $action_count = count( $actions );
     
        if ( ! $action_count ) {
            return '';
        }
     
        $mode = get_user_setting( 'posts_list_mode', 'list' );
     
        if ( 'excerpt' === $mode ) {
            $always_visible = true;
        }
     
        $out = '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';
     
        $i = 0;
     
        foreach ( $actions as $action => $link ) {
            ++$i;
     
            $sep = ( $i < $action_count ) ? ' | ' : '';
     
            $out .= "<span class='$action'>$link$sep</span>";
        }
     
        $out .= '</div>';
     
        $out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details' ) . '</span></button>';
     
        return $out;
    }

    /**
    * Method for name column
    *
    * @param array $item an array of DB data
    *
    * @return string
    */
    function column_name( $item ) {

        // create a nonce
        $delete_nonce   = wp_create_nonce( 'sp_delete_customer' );
        $title          = '<strong>' . $item['name'] . '</strong>';
        $url            = admin_url( 'post.php?post=etn&post=' . absint( $item['ID'] ) );
        $parent_url     = admin_url( 'post.php?post=' . absint( $this->id ) );
        $parent_edit_url= add_query_arg( array( 'action' => 'edit' ), $parent_url );

        $recurrence_url     = admin_url( 'post.php?post=' . absint( $item['ID'] ) );
        $recurrence_edit_url= add_query_arg( array( 'action' => 'edit' ), $recurrence_url );

        // Add detach , recurrences button
        $detach_link    = wp_nonce_url( add_query_arg( array( 'action' => 'detach' ), $url ), 'detach_nonce' );

        $actions =  array(
            'edit_recurrence_link'  => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $recurrence_edit_url ), esc_html__('Edit Recurrence','eventin' ) ), 
            'edit_parent_link'      => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $parent_edit_url ), esc_html__('Edit All Recurrences','eventin' ) ), 
            'detach'                => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $detach_link ), esc_html__('Detach','eventin' ) ), 
        );

        return $title . $this->row_actions( $actions );
    }

    /**
     * Main query and show function
     */
    
    public function preparing_items(){
        $per_page = 200;
        $column   = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [ $column , $hidden , $sortable ];
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1) * $per_page;

        if ( isset( $_REQUEST['orderby']) && isset( $_REQUEST['order']) ) 
        {
            $args['orderby']    = $_REQUEST['orderby'];
            $args['order']      = $_REQUEST['order'];
        }

        $args['limit']  = $per_page;
        $args['offset'] = $offset;

        $get_data = \Etn\Utils\Helper::get_all_data( $this->id, $args );

        $this->set_pagination_args( [
            'total_items'   => \Etn\Utils\Helper::total_data($this->id),
            'per_page'      => $per_page,
        ] );

        
        $this->items =  $get_data;
    }

}
