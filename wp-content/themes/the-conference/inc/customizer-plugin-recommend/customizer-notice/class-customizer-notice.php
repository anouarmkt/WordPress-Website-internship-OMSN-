<?php
/**
 * The Conference Customizer Notification System.
 *
 * @package The Conference
 */

/**
 * TI Customizer Notify Class
 */
class The_Conference_Customizer_Notice {

	/**
	 * Recommended plugins
	 *
	 * @var array $recommended_plugins Recommended plugins displayed in customize notification system.
	 */
	private $recommended_plugins;

	/**
	 * The single instance of The_Conference_Customizer_Notice
	 *
	 * @var The_Conference_Customizer_Notice $instance The The_Conference_Customizer_Notice instance.
	 */
	private static $instance;

	/**
	 * Title of Recommended plugins section in customize
	 *
	 * @var string $recommended_plugins_title Title of Recommended plugins section displayed in customize notification system.
	 */
	private $recommended_plugins_title;

	/**
	 * Dismiss button label
	 *
	 * @var string $dismiss_button Dismiss button label displayed in customize notification system.
	 */
	private $dismiss_button;

	/**
	 * Install button label for plugins
	 *
	 * @var string $install_button_label Label of install button for plugins displayed in customize notification system.
	 */
	private $install_button_label;

	/**
	 * Activate button label for plugins
	 *
	 * @var string $activate_button_label Label of activate button for plugins displayed in customize notification system.
	 */
	private $activate_button_label;

	/**
	 * Deactivate button label for plugins
	 *
	 * @var string $deactivate_button_label Label of deactivate button for plugins displayed in customize notification system.
	 */
	private $deactivate_button_label;

	/**
	 * The Main The_Conference_Customizer_Notice instance.
	 *
	 * We make sure that only one instance of The_Conference_Customizer_Notice exists in the memory at one time.
	 *
	 * @param array $config The configuration array.
	 */
	public static function init( $config ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof The_Conference_Customizer_Notice ) ) {
			self::$instance = new The_Conference_Customizer_Notice;
			if ( ! empty( $config ) && is_array( $config ) ) {
				self::$instance->config = $config;
				self::$instance->setup_config();
				self::$instance->setup_actions();
			}
		}
	}

	/**
	 * Setup the class props based on the config array.
	 */
	public function setup_config() {
		// global arrays for recommended plugins/actions
		global $the_conference_recommended_plugins;
		global $install_button_label;
		global $activate_button_label;
		global $deactivate_button_label;

		$this->recommended_plugins = isset( $this->config['recommended_plugins'] ) ? $this->config['recommended_plugins'] : array();
        $this->recommended_plugins_title = isset( $this->config['recommended_plugins_title'] ) ? $this->config['recommended_plugins_title'] : '';
		$this->dismiss_button = isset( $this->config['dismiss_button'] ) ? $this->config['dismiss_button'] : '';

		$the_conference_recommended_plugins = array();
		
		if ( isset( $this->recommended_plugins ) ) {
			$the_conference_recommended_plugins = $this->recommended_plugins;
		}

		$install_button_label = isset( $this->config['install_button_label'] ) ? $this->config['install_button_label'] : '';
		$activate_button_label = isset( $this->config['activate_button_label'] ) ? $this->config['activate_button_label'] : '';
		$deactivate_button_label = isset( $this->config['deactivate_button_label'] ) ? $this->config['deactivate_button_label'] : '';
	}

	/**
	 * Setup the actions used for this class.
	 */
	public function setup_actions() {

		// Register the section
		add_action( 'customize_register', array( $this, 'customize_register_notice' ) );

		// Enqueue scripts and styles
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts_for_customizer' ), 0 );

		/* ajax callback for dismissable recommended plugins */
		add_action( 'wp_ajax_dismiss_recommended_plugins', array( $this, 'dismiss_recommended_plugins_callback' ) );
	}

	/**
	 * Scripts and styles used in the Themeisle_Customizer_Notify class
	 */
	public function scripts_for_customizer() {

		wp_enqueue_style( 'the-conference-customizer-notice', get_template_directory_uri() . '/inc/customizer-plugin-recommend/customizer-notice/css/customizer-notice.css', null );

		wp_enqueue_style( 'plugin-install' );
		wp_enqueue_script( 'plugin-install' );
		wp_add_inline_script( 'plugin-install', 'var pagenow = "customizer";' );

		wp_enqueue_script( 'updates' );

		wp_enqueue_script( 'the-conference-customizer-notice', get_template_directory_uri() . '/inc/customizer-plugin-recommend/customizer-notice/js/customizer-notice.js', array( 'jquery', 'customize-controls' ), THE_CONFERENCE_THEME_VERSION, true );
		wp_localize_script(
			'the-conference-customizer-notice', 
            'customizer_notice_data', 
            array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'template_directory' => get_template_directory_uri(),
				'base_path'          => admin_url(),
				'activating_string'  => __( 'Activating', 'the-conference' ),
			)
		);
	}

	/**
	 * Register the section for the recommended actions/plugins in customize
	 *
	 * @param object $wp_customize The customizer object.
	 */
	public function customize_register_notice( $wp_customize ) {
		/**
		 * Include the The_Conference_Customizer_Notice_Section class.
		 */
		require_once get_template_directory() . '/inc/customizer-plugin-recommend/customizer-notice/class-customizer-notice-section.php';

		$wp_customize->register_section_type( 'The_Conference_Customizer_Notice_Section' );

		$wp_customize->add_section(
			new The_Conference_Customizer_Notice_Section(
				$wp_customize,
				'customizer-plugin-notice-section',
				array(
					'plugin_text'    => $this->recommended_plugins_title,
					'dismiss_button' => $this->dismiss_button,
					'priority'       => 0,
				)
			)
		);                                
	}

	/**
	 * Dismiss recommended plugins
	 */
	public function dismiss_recommended_plugins_callback() {

		$action_id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : 0;

		echo esc_html( $action_id ); /* this is needed and it's the id of the dismissable required action */

		if ( ! empty( $action_id ) ) {
			/* if the option exists, update the record for the specified id */
			$show_recommended_plugins = get_option( 'the_conference_show_recommended_plugins' );

			switch ( $_GET['todo'] ) {
				case 'add':
					$show_recommended_plugins[ $action_id ] = false;
					break;
				case 'dismiss':
					$show_recommended_plugins[ $action_id ] = true;
					break;
			}
			update_option( 'the_conference_show_recommended_plugins', $show_recommended_plugins );
		}
		die(); // this is required to return a proper result
	}
}