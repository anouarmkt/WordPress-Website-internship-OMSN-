<?php
namespace Etn\Base;

Abstract class Cron{

    public function __construct(){
        $this->config();
    }

    /**
     * Register event
     */
    public  function config(){
        $hook_name      = $this->hook_name ;
        $recurrence     = $this->recurrence_action();

        // bind new corn event
        add_action( 'init', function() use ( $hook_name , $recurrence ) {
            if ( !wp_next_scheduled( $hook_name ) ) {
                wp_schedule_event( time() , $recurrence  , $hook_name );
            }
        } );
        // remove attendee of failed status 
        add_action( $hook_name ,[ $this , 'action_name' ] );
    }

    /**
     * run  in every corn schedule
     */
    public abstract function action_name();

    /**
     * corn schedule time
     */
    public abstract function recurrence_action();
}
