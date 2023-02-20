<?php
/**
 * Class Portfolio Settings Page
 *
 * @since   1.0.5
 * @package WPZOOM_Portfolio
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for settings page.
 */
class WPZOOM_Portfolio_Settings {
	/**
	 * Option name
	 */
	public static $option = 'wpzoom-portfolio-settings';

	/**
	 * Store all default settings options.
	 *
	 * @static
	 */
	public static $defaults = array();

	/**
	 * Store all settings options.
	 *
	 * @static
	 */
	public static $settings = array();

	/**
	 * Active Tab.
	 */
	public static $active_tab;

	/**
	 * Class WPZOOM_Portfolio_Settings_Fields instance.
	 */
	public $_fields;

	/**
	 * Store Settings options.
	 */
	public static $options = array();

	/**
	 * License key
	 */
	public static $license_key = false;

	/**
	 * License status
	 */
	public static $license_status = false;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		global $pagenow;

		self::$options = get_option( self::$option );

		// Check what page we are on.
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_action( 'admin_init', array( $this, 'set_defaults' ) );

			// Include admin scripts & styles
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

			// Do ajax request
			add_action( 'wp_ajax_wpzoom_reset_settings', array( $this, 'reset_settings' ) );

			// Only load if we are actually on the settings page.
			if ( WPZOOM_PORTFOLIO_SETTINGS_PAGE === $page ) {
				add_action( 'wpzoom_portfolio_admin_page', array( $this, 'settings_page' ) );
			}

			$this->_fields = new WPZOOM_Portfolio_Settings_Fields();
		}
	}

	/**
	 * Set default values for setting options.
	 */
	public function set_defaults() {
		// Set active tab
		self::$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'tab-general';

		self::$defaults = self::get_defaults();

		if ( empty( self::$defaults ) ) {
			return false;
		}

		// If 'wpzoom-portfolio-settings' is empty update option with defaults values
		if ( empty( self::$options ) ) {
			self::update_option( self::$defaults );
		}

		// If new setting is added, update 'wpzoom-portfolio-settings' option
		if ( ! empty( self::$options ) ) {
			$new_settings = array_diff_key( self::$defaults, self::$options );
			if ( ! empty( $new_settings ) ) {
				self::update_option( array_merge( self::$options, $new_settings ) );
			}
		}

		return apply_filters( 'wpzoom_pb_set_settings_defaults', self::$defaults );
	}

	/**
	 * Update option value
	 *
	 * @param string|array $value
	 * @param string       $option
	 */
	public static function update_option( $value, $option = '', $autoload = null ) {
		if ( empty( $option ) ) {
			$option = self::$option;
		}

		if ( self::$options !== false ) {
			// The option already exists, so we just update it.
			update_option( $option, $value, $autoload );
		} else {
			// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
			$deprecated = null;
			$autoload   = 'no';
			add_option( $option, $value, $deprecated, $autoload );
		}
	}

	/**
	 * Get default values of setting options.
	 *
	 * @static
	 */
	public static function get_defaults() {
		$defaults = array();

		foreach ( self::$settings as $key => $setting ) {
			if ( isset( $setting['sections'] ) && is_array( $setting['sections'] ) ) {
				foreach ( $setting['sections'] as $section ) {
					if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							if ( isset( $field['args']['default'] ) ) {
								$defaults[ $field['id'] ] = (string) $field['args']['default'];
							}
						}
					}
				}
			}
		}

		return $defaults;
	}

	/**
	 * Get default value by option name
	 *
	 * @param string $option_name
	 * @static
	 * @return boolean
	 */
	public static function get_default_option_value( $option_name ) {
		return isset( self::$defaults[ $option_name ] ) ? self::$defaults[ $option_name ] : false;
	}

	/**
	 * Get license key
	 *
	 * @since 1.2.0
	 * @return string The License key
	 */
	public static function get_license_key() {
		return self::$license_key;
	}

	/**
	 * Get license status
	 *
	 * @since 1.2.0
	 * @return string The License status
	 */
	public static function get_license_status() {
		return self::$license_status;
	}

	/**
	 * Get setting options
	 *
	 * @since 1.2.0
	 * @return array
	 */
	public static function get_settings() {
		return apply_filters( 'wpzoom_pb_get_settings', self::$options );
	}

	/**
	 * Get setting option value
	 *
	 * @since 1.2.0
	 * @param string $option  Option name
	 * @return string|boolean
	 */
	public static function get( $option ) {
		return isset( self::$options[ $option ] ) ? self::$options[ $option ] : false;
	}

	/**
	 * Initilize all settings
	 */
	public function settings_init() {
		$premium_badge = '<span class="wpzoom-pb-badge wpzoom-pb-field-is_premium">' . __( 'Premium', 'wpzoom-portfolio' ) . '</span>';
		$soon_badge    = '<span class="wpzoom-pb-badge wpzoom-pb-field-is_coming_soon">' . __( 'Coming Soon', 'wpzoom-portfolio' ) . '</span>';

		self::$settings = array(
			'general'     => array(
				'tab_id'       => 'tab-general',
				'tab_title'    => __( 'General', 'wpzoom-portfolio' ),
				'option_group' => 'wpzoom-portfolio-settings-general',
				'option_name'  => self::$option,
				'sections'     => array(
					array(
						'id'       => 'wpzoom_section_general',
						'title'    => __( 'Portfolio Settings', 'wpzoom-portfolio' ),
						'page'     => 'wpzoom-portfolio-settings-general',
						'callback' => array( $this, 'section_general_cb' ),
						'fields'   => array(
							array(
								'id'    => 'wpzoom_portfolio_root',
								'title' => esc_html__( 'Slug', 'wpzoom-portfolio' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_root',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'The slug name cannot be the same name as your portfolio page or the layout will break. This option changes the permalink when you use the permalink type as %postname%. Visit the Settings - Permalinks screen after changing this setting', 'wpzoom-portfolio' ),
									'default'     => '',
									'type'        => 'text',
									'id_only'     => true
								),
							),
							array(
								'id'    => 'wpzoom_portfolio_base',
								'title' => esc_html__( 'Taxonomy Slug', 'wpzoom-portfolio' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_base',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'The taxonomy slug name cannot be the same name as your portfolio page or the layout will break. This option changes the permalink when you use the permalink type as %postname%. Visit the Settings - Permalinks screen after changing this setting', 'wpzoom-portfolio' ),
									'default'     => '',
									'type'        => 'text',
									'id_only'     => true
								),
							),
						),
					),
					array(
						'id'       => 'wpzoom_section_recipe_miscellaneous',
						'title'    => __( 'Miscellaneous', 'wpzoom-portfolio' ),
						'page'     => 'wpzoom-portfolio-settings-general',
						'callback' => '__return_false',
						'fields'   => array(
							array(
								'id'    => 'wpzoom_portfolio_settings_sections_expanded',
								'title' => __( 'Show Block Sections Expanded?', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_sections_expanded',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Expand the portfolio block settings on initial load', 'wpzoom-portfolio' ),
									'default'     => false,
									'preview'     => false,
								),
							),
						),
					),
				),
			),
			'portfolio-taxonomy' => array(
				'tab_id'       => 'portfolio-taxonomy',
				'tab_title'    => __( 'Taxonomy', 'wpzoom-portfolio' ),
				'option_group' => 'wpzoom-portfolio-settings-taxonomy',
				'option_name'  => self::$option,
				'sections'     => array(
					array(
						'id'       => 'wpzoom_section_taxonomy_template',
						'title'    => __( 'Taxonomy Template', 'wpzoom-portfolio' ),
						'page'     => 'wpzoom-portfolio-settings-taxonomy',
						'callback' => array( $this, 'section_taxonomy_template_cb' ),
						'fields'   => array(
							array(
								'id' => 'wpzoom_portfolio_settings_use_template',
								'title' => __( 'Use the template provided by the plugin', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_use_template',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Use the template provided by the plugin', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id'    => 'wpzoom_portfolio_settings_taxonomy_layout',
								'title' => esc_html__( 'Layout', 'wpzoom-portfolio' ),
								'type'  => 'select',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_taxonomy_layout',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Portfolio Layout', 'wpzoom-portfolio' ),
									'default'     => 'grid',
									'options'     => array(
										'list'    => esc_html__( 'Columns', 'wpzoom-portfolio' ),
										'grid'    => esc_html__( 'Overlay', 'wpzoom-portfolio' ),
                                        'masonry'    => esc_html__( 'Masonry', 'wpzoom-portfolio' ),
									),
								),
							),
                            array(
                                'id'    => 'wpzoom_portfolio_settings_number_posts',
                                'title' => __( 'Number of posts', 'wpzoom-portfolio' ),
                                'type'  => 'input',
                                'args'  => array(
                                    'label_for'   => 'wpzoom_portfolio_settings_number_posts',
                                    'class'       => 'wpzoom-pb-field',
                                    'description' => esc_html__( 'Number of posts per page', 'wpzoom-portfolio' ),
                                    'default'     => 9,
                                    'type'        => 'number',
                                ),
                            ),
							array(
								'id'    => 'wpzoom_portfolio_settings_number_columns',
								'title' => __( 'Columns', 'wpzoom-portfolio' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_number_columns',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Amount of Columns', 'wpzoom-portfolio' ),
									'default'     => 3,
									'type'        => 'number',
								),
							),
							array(
								'id'    => 'wpzoom_portfolio_settings_columns_gap',
								'title' => __( 'Columns Gap', 'wpzoom-portfolio' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_columns_gap',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Set Columns Gap', 'wpzoom-portfolio' ),
									'default'     => 0,
									'type'        => 'number',
								),
							),
						),
					),
					array(
						'id'       => 'wpzoom_section_taxonomy_colors',
						'title'    => __( 'Colors', 'wpzoom-portfolio' ),
						'page'     => 'wpzoom-portfolio-settings-taxonomy',
						'callback' => '__return_false',
						'fields'   => array(
							array(
								'id'    => 'wpzoom_portfolio_settings_primary_color',
								'title' => __( 'Primary Color', 'wpzoom-portfolio' ),
								'type'  => 'colorpicker',
								'args'  => array(
									'label_for' => 'wpzoom_portfolio_settings_primary_color',
									'class'     => 'wpzoom-pb-field',
									'default'   => '#0BB4AA',
								),
							),
							array(
								'id'    => 'wpzoom_portfolio_settings_secondary_color',
								'title' => __( 'Secondary Color', 'wpzoom-portfolio' ),
								'type'  => 'colorpicker',
								'args'  => array(
									'label_for' => 'wpzoom_portfolio_settings_secondary_color',
									'class'     => 'wpzoom-pb-field',
									'default'   => '#000',
								),
							),
						),
					),
					array(
						'id'       => 'wpzoom_section_taxonomy_fields',
						'title'    => __( 'Fields', 'wpzoom-portfolio' ),
						'page'     => 'wpzoom-portfolio-settings-taxonomy',
						'callback' => '__return_false',
						'fields'   => array(
							array(
								'id' => 'wpzoom_portfolio_settings_show_thumbnail',
								'title' => esc_html__( 'Show Thumbnail', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_show_thumbnail',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Show Featured Image of the porfolio item', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id'    => 'wpzoom_portfolio_settings_taxonomy_img_size',
								'title' => esc_html__( 'Thumbnail Size', 'wpzoom-portfolio' ),
								'type'  => 'select',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_taxonomy_img_size',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Select size for the featured image', 'wpzoom-portfolio' ),
									'default'     => 'portfolio_item-thumbnail',
									'options'     => self::get_image_sizes(),
								),
							),
							array(
								'id' => 'wpzoom_portfolio_settings_show_author',
								'title' => esc_html__( 'Show Author', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_show_author',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Show author of the portfolio item', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id' => 'wpzoom_portfolio_settings_show_date',
								'title' => esc_html__( 'Show Date', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_show_date',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Show date of the portfolio item', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id' => 'wpzoom_portfolio_settings_show_excerpt',
								'title' => esc_html__( 'Show Excerpt', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_show_excerpt',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Show excerpt of the portfolio item', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id' => 'wpzoom_portfolio_settings_show_read_more',
								'title' => esc_html__( 'Show Read More Button', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_show_read_more',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Show read more button', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id'    => 'wpzoom_portfolio_settings_readmore_label',
								'title' => __( 'Read More Button Label', 'wpzoom-portfolio' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_readmore_label',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Read More button label to display', 'wpzoom-portfolio' ),
									'default'     => '',
									'type'        => 'text',
								),
							),
						),
					),
					array(
						'id'       => 'wpzoom_section_taxonomy_other',
						'title'    => esc_html__( 'Lightbox', 'wpzoom-portfolio' ),
						'page'     => 'wpzoom-portfolio-settings-taxonomy',
						'callback' => '__return_false',
						'fields'   => array(
							array(
								'id' => 'wpzoom_portfolio_settings_lightbox',
								'title' => esc_html__( 'Open Portfolio Items in a Lightbox', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_lightbox',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Enable lightbox', 'wpzoom-portfolio' ),
									'default'     => true,
									'preview'     => false,
								),
							),
							array(
								'id' => 'wpzoom_portfolio_settings_lightbox_caption',
								'title' => esc_html__( 'Show Lightbox Caption', 'wpzoom-portfolio' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzoom_portfolio_settings_lightbox_caption',
									'class'       => 'wpzoom-pb-field',
									'description' => esc_html__( 'Show Lightbox Caption', 'wpzoom-portfolio' ),
									'default'     => false,
									'preview'     => false,
								),
							),
						),
					),
				),
			),
		);

		$this->register_settings();
	}

	/**
	 * Register all Setting options
	 *
	 * @since 1.1.0
	 * @return boolean
	 */
	public function register_settings() {
		// filter hook
		self::$settings = apply_filters( 'wpzoom_pb_before_register_settings', self::$settings );

		if ( empty( self::$settings ) ) {
			return;
		}

		foreach ( self::$settings as $key => $setting ) {
			$this->register_setting( $setting );
		}

		return true;
	}

	/**
	 * Register Setting
	 *
	 * @since 2.3.0
	 * @param array $setting
	 * @return void
	 */
	public function register_setting( $setting ) {
		$setting['sanitize_callback'] = isset( $setting['sanitize_callback'] ) ? $setting['sanitize_callback'] : array();
		register_setting( $setting['option_group'], $setting['option_name'], $setting['sanitize_callback'] );

		if ( isset( $setting['sections'] ) && is_array( $setting['sections'] ) ) {
			foreach ( $setting['sections'] as $section ) {
				if ( ! isset( $section['id'] ) ) {
					return;
				}
				add_settings_section( $section['id'], $section['title'], $section['callback'], $section['page'] );

				if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						if ( ! isset( $field['id'] ) ) {
							return;
						}

						if ( method_exists( $this->_fields, $field['type'] ) ) {
							$field['callback'] = array( $this->_fields, $field['type'] );
						} else {
							$field['callback'] = '__return_false';
						}

						add_settings_field( $field['id'], $field['title'], $field['callback'], $section['page'], $section['id'], $field['args'] );
					}
				}
			}
		}
	}

	/**
	 * HTML output for Setting page
	 */
	public function settings_page() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$reset_settings   = isset( $_GET['wpzoom_reset_settings'] ) ? sanitize_text_field( $_GET['wpzoom_reset_settings'] ) : false;
		$settings_updated = isset( $_GET['settings-updated'] ) ? sanitize_text_field( $_GET['settings-updated'] ) : false;
		?>
		<div class="wrap">

			<h1 style="margin-bottom: 15px"><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<?php settings_errors(); ?>

			<?php if ( $reset_settings && ! $settings_updated ) : ?>
				<div class="updated settings-error notice is-dismissible">
					<p><strong>Settings have been successfully reset.</strong></p>
				</div>
			<?php endif; ?>

			<form id="wpzoom-portfolio-settings" action="options.php" method="post">
				<ul class="wp-tab-bar">
					<?php foreach ( self::$settings as $setting ) : ?>
						<?php if ( self::$active_tab === $setting['tab_id'] ) : ?>
							<li class="wp-tab-active"><a href="?post_type=portfolio_item&page=wpzoom-portfolio-settings&tab=<?php echo esc_attr( $setting['tab_id'] ); ?>"><?php echo esc_html( $setting['tab_title'] ); ?></a></li>
						<?php else : ?>
							<li><a href="?post_type=portfolio_item&page=wpzoom-portfolio-settings&tab=<?php echo esc_attr( $setting['tab_id'] ); ?>"><?php echo esc_html( $setting['tab_title'] ); ?></a></li>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
				<?php foreach ( self::$settings as $setting ) : ?>
					<?php if ( self::$active_tab === $setting['tab_id'] ) : ?>
						<div class="wp-tab-panel" id="<?php echo esc_attr( $setting['tab_id'] ); ?>">
							<?php
								settings_fields( $setting['option_group'] );
								do_settings_sections( $setting['option_group'] );
							?>
						</div>
					<?php else : ?>
						<div class="wp-tab-panel" id="<?php echo esc_attr( $setting['tab_id'] ); ?>" style="display: none;">
							<?php
								settings_fields( $setting['option_group'] );
								do_settings_sections( $setting['option_group'] );
							?>
						</div>
					<?php endif ?>
				<?php endforeach ?>
				<span class="wpzoom_pb_settings_save"><?php submit_button( 'Save Settings', 'primary', 'wpzoom_pb_settings_save', false ); ?></span>
				<span class="wpzoom_pb_reset_settings"><input type="button" class="button button-secondary" name="wpzoom_pb_reset_settings" id="wpzoom_pb_reset_settings" value="Reset Settings"></span>

			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @param string $hook
	 */
	public function scripts( $hook ) {
		$pos = strpos( $hook, WPZOOM_PORTFOLIO_SETTINGS_PAGE );

		wp_enqueue_style(
			'wpzoom-portfolio-admin-css',
			untrailingslashit( WPZOOM_PORTFOLIO_URL ) . '/assets/admin/css/admin.css',
			array(),
			WPZOOM_PORTFOLIO_VERSION
		);

		if ( $pos !== false ) {
			// Add the color picker css file
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_style(
				'wpzoom-portfolio-admin-style',
				untrailingslashit( WPZOOM_PORTFOLIO_URL ) . '/assets/admin/css/style.css',
				array(),
				WPZOOM_PORTFOLIO_VERSION
			);

			wp_enqueue_script(
				'wpzoom-portfolio-admin-script',
				untrailingslashit( WPZOOM_PORTFOLIO_URL ) . '/assets/admin/js/script.js',
				array( 'jquery', 'wp-color-picker' ),
				WPZOOM_PORTFOLIO_VERSION
			);

			wp_localize_script(
				'wpzoom-portfolio-admin-script',
				'WPZOOM_Settings',
				array(
					'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'wpzoom-reset-settings-nonce' ),
				)
			);
		}
	}

	/**
	 * Reset settings to default values
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function reset_settings() {
		check_ajax_referer( 'wpzoom-reset-settings-nonce', 'security' );

		$defaults = self::get_defaults();

		if ( empty( $defaults ) ) {
			$response = array(
				'status'  => '304',
				'message' => 'NOT',
			);

			wp_send_json_error( $response );
		}

		$response = array(
			'status'  => '200',
			'message' => 'OK',
		);

		self::update_option( $defaults );

		wp_send_json_success( $response );
	}

	public function get_image_sizes() {

		global $_wp_additional_image_sizes;

		$sizes = array();
		$sizes['full'] = 'Full';

		$wp_image_sizes = get_intermediate_image_sizes();

		foreach( $wp_image_sizes as $size ) {
			$sizes[$size] = $size;
		}

		return $sizes;

	}

	// section callbacks can accept an $args parameter, which is an array.
	// $args have the following keys defined: title, id, callback.
	// the values are defined at the add_settings_section() function.
	public function section_general_cb( $args ) {
		?>
		 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'General configurations for portfolio plugin', 'wpzoom-portfolio' ); ?></p>
		<?php
	}
	public function section_taxonomy_template_cb( $args ) {
		?>
		 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Style the portfolio taxonomy archive page', 'wpzoom-portfolio' ); ?></p>
		<?php
	}
}

new WPZOOM_Portfolio_Settings();