<?php

namespace Etn\Core\Attendee;

defined( 'ABSPATH' ) || exit;

use Etn\Base\Cron as Base_Cron;
use Etn\Utils\Helper;

class Cron extends Base_Cron{

    use \Etn\Traits\Singleton;

    public $hook_name = 'draft_attendee_remove_hook';

    /**
     * Register event and chanage schedule
     */
    public function init(){
        // if schedule change , re-register event        
        $interval           = Helper::get_schedule_days();
        $existing_interval  = (array) wp_get_scheduled_event('draft_attendee_remove_hook');
        if ( isset( $existing_interval['interval'] ) && $interval !== $existing_interval['interval'] ) {
            $this->remove_cron_job();
        }
        // register event
        $this->config();
    }

    /**
     * Do the task.
     * Register cron schedule to remove faild status attendee
     */
    public function action_name(){
        if ( !current_user_can('manage_options') ) {
           return;
        }

        return Helper::remove_attendee_data();
    }

    /**
     * recurrence action name
     */
    public function recurrence_action(){
        // register a schedule
        add_filter( 'cron_schedules' , array( $this, 'cron_schedules' ) );

        return 'attendee-removed-schedule';
    }

    /**
	 * Filters cron_schedules to add a new schedule
	 */
	public function cron_schedules( $schedules ) {

        $interval = Helper::get_schedule_days();

		$schedules['attendee-removed-schedule'] = array(
            'interval' => $interval ,
			'display'  => esc_html__( 'Attendee remove schedule', 'eventin' ),
        );
        
		return $schedules;
    }
    
    /**
     * Remove existing job
     */
    public function remove_cron_job() {
        wp_clear_scheduled_hook("draft_attendee_remove_hook"); 
    } 

}