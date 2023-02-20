<?php

namespace Etn\Widgets;

defined( 'ABSPATH' ) || exit;

use Etn\Utils\Helper as Helper;

class Manifest {
    use \Etn\Traits\Singleton;

    private $categories = ['event' => 'Eventin event'];

    public function init() {
        add_action( 'elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories'] );
        add_action( 'elementor/widgets/widgets_registered', [$this, 'register_widgets'] );
    }

    public function get_input_widgets() {
        return [
            'events',
            'schedule',
            'zoom',
            'speakers',
            'schedule-list',
            'events-tab',
            'advanced-search',
            'upcoming-event-tab',
            'event-calendar'
        ];
    }

    /**
     * Register all elementor widgets dynamically
     */
    public function register_widgets() {

        foreach ( $this->get_input_widgets() as $v ):
            $files = plugin_dir_path( __FILE__ ) . $v . '/' . $v . '.php';

            if ( file_exists( $files ) ) {
                require_once $files;
                $class_name = '\Elementor\Etn_' . Helper::make_classname( $v );
                \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name() );
            }

        endforeach;
    }

    public function add_elementor_widget_categories( $elements_manager ) {

        foreach ( $this->categories as $k => $v ) {
            $elements_manager->add_category(
                'etn-' . $k,
                [
                    'title' => esc_html__( $v, 'eventin' ),
                    'icon'  => 'fa fa-plug',
                ]
            );
        }

    }

}
