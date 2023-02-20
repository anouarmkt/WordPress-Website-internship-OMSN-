<?php

namespace Wpmet\Libs;
use Etn\Utils\Helper;
defined('ABSPATH') || exit;

if(!class_exists('\Wpmet\Libs\Pro_Awareness')) :

	class Pro_Awareness
    {

		private static $instance;

		private $text_domain;
		private $plugin_file;
		private $parent_menu_slug;
		private $menu_slug = '_get_help';
		private $default_grid_link  = 'https://themewinter.com/support/';
		private $default_grid_title = 'Documentation';
		private $default_grid_thumbnail = '';
		private $default_grid_desc  = '';
		private $pro_link_conf      = [];

		private $grids = [];
		private $action_links = [];
		private $row_meta_links = [];
		private $parent_menu_text = 'Get Help';


		protected $script_version = '1.0.3';

		/**
		 * Get version of this script
		 *
		 * @return string Version name
		 */
		public function get_version() {
			return $this->script_version;
		}

		/**
		 * Get current directory path
		 *
		 * @return string
		 */
		public function get_script_location() {
			return __FILE__;
		}


		public static function instance($text_domain) {

			self::$instance = new self();

			return self::$instance->set_text_domain($text_domain);
		}

		protected function set_text_domain($val) {

			$this->text_domain = $val;

			return $this;
		}

		private function default_grid() {

			return [
				'url'         => $this->default_grid_link,
				'title'       => $this->default_grid_title,
				'thumbnail'   => $this->default_grid_thumbnail,
				'description' => $this->default_grid_desc,
			];
		}

		public function set_parent_menu_text($text) {

		    $this->parent_menu_text = $text;

		    return $this;
        }

		public function set_default_grid_link($url) {

			$this->default_grid_link = $url;

			return $this;
		}

		public function set_default_grid_title($title) {

			$this->default_grid_title = $title;

			return $this;
		}

		public function set_default_grid_desc($title) {

			$this->default_grid_desc = $title;

			return $this;
		}

		public function set_default_grid_thumbnail($thumbnail) {

			$this->default_grid_thumbnail = $thumbnail;

			return $this;
		}

		public function set_parent_menu_slug($slug) {

			$this->parent_menu_slug = $slug;

			return $this;
		}


		public function set_menu_slug($slug) {

			$this->menu_slug = $slug;

			return $this;
		}

		public function set_plugin_file($plugin_file) {

			$this->plugin_file = $plugin_file;

			return $this;
		}

		public function set_pro_link($url, $conf = []) {

			if($url == '') {
				return $this;
			}

			$this->pro_link_conf[] = [
				'url'        => $url,
				'anchor'     => empty($conf['anchor']) ? '<span style="color: #FCB214;" class="pro_aware pro">Go Pro</span>' : $conf['anchor'],
				'permission' => empty($conf['permission']) ? 'manage_options' : $conf['permission'],
			];

			return $this;
		}

		/**
		 * Set page grid
		 */
		public function set_page_grid($conf = []) {

			if(!empty($conf['url'])) {

				$this->grids[] = [
					'url'         => $conf['url'],
					'title'       => empty($conf['title']) ? esc_html__('Default Title', 'eventin') : $conf['title'],
					'thumbnail'   => empty($conf['thumbnail']) ? '' : esc_url($conf['thumbnail']),
					'description' => empty($conf['description']) ? '' : $conf['description'],
				];
			}

			return $this;
		}

		/**
		 * @deprecated This method will be removed
		 */
		public function set_grid($conf = []) {
			$this->set_page_grid($conf);

			return $this;
		}

		protected function prepare_pro_links() {

			if(!empty($this->pro_link_conf)) {

				foreach($this->pro_link_conf as $conf) {

					add_submenu_page($this->parent_menu_slug, $conf['anchor'], $conf['anchor'], $conf['permission'], $conf['url'], '');
				}
			}
		}

		protected function prepare_grid_links() {

			if(!empty($this->grids)) {

				add_submenu_page($this->parent_menu_slug, $this->parent_menu_text, $this->parent_menu_text, 'manage_options', $this->text_domain . $this->menu_slug, [$this, 'generate_grids']);
			}
		}


		public function generate_grids() {

			// Include plugin page header
			include_once( \Wpeventin::plugin_dir() . "templates/layout/header.php" );

			/**
			 * Adding default grid at first position
			 */
			array_unshift($this->grids, $this->default_grid());
			?>
			<!-- Welcome section -->
			<div class="tw-help-section">
				<div class="tw-help-hero">
					<h2><?php echo esc_html__( 'Help Center', 'eventin' );?></h2>
					<p><?php echo esc_html__( 'To help you to get started, we put together the documentation, support link, videos and FAQs here.', 'eventin' );?></p>
				</div>
			</div>
			<!-- Welcome section end -->
			<!-- Box grid section start -->
            <div class="pro_aware grid_container tw-help-section">
	            <?php do_action($this->text_domain.'/pro_awareness/before_grid_contents'); ?>
                <div class="tw-grid-row">
					<?php
					foreach($this->grids as $grid) {
						?>
                        <div class="grid tw-grid-item">
                            <div class="wpmet_pro_a-grid-inner">
                                <a target="_blank" href="<?php echo esc_url($grid['url']); ?>"
                                   class="wpmet_pro_a_wrapper tw-grid-item-inner" title="<?php echo esc_attr($grid['title']); ?>"
                                   title="<?php echo esc_attr($grid['title']); ?>">
                                    <div class="wpmet_pro_a_thumb">
                                        <img src="<?php echo esc_attr($grid['thumbnail']); ?>" alt="Thumbnail">
                                    </div>
                                    <!-- // thumbnail -->

                                    <h4 class="wpmet_pro_a_grid_title tw-grid-title"><?php echo esc_attr($grid['title']); ?></h4>
									<?php if(!empty($grid['description'])) { ?>
                                        <p class="wpmet_pro_a_description tw-grid-button"><?php echo esc_html($grid['description']); ?></p>
                                        <!-- // description -->
									<?php } ?>
                                    <!-- // title -->
                                </a>
                            </div>
                        </div>
						<?php
					} ?>
                </div>

	            <?php do_action($this->text_domain.'/pro_awareness/after_grid_contents'); ?>
            </div>
			<!-- Box grid section end -->
			<!-- Commmon FAQ wrapper start -->
			<div class="tw-help-section">
				<div class="tw-faq-wrapper">
					<h3 class="tw-section-title"><?php echo esc_html('Common FAQs', 'eventin');?></h3>
					<div class="tw-accordion-wrapper">
						<div class="tw-accordion-item">
							<div class="tw-accordion-content-wrapper">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 2L8 8L2 2" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<div class="tw-accordion-content">
									<h3 class="tw-accordion-title">
										<?php echo esc_html__('How to create zoom event?', 'eventin'); ?>
									</h3>
									<div class="tw-accordion-cotent">
										<p><?php echo esc_html__('You have to give valid connection details in Eventin=>Settings=>User data.', 'eventin'); ?></p>
										<p><?php echo esc_html__('After fill up “Api key”, “Secret key” enter “Save changes” and then check connection. After successful connection navigate to Eventin=>Zoom to create a meeting.', 'eventin'); ?></p>
										<p><?php echo esc_html__('Note: You must have successful connection to zoom api).You will get meeting id from zoom. Place your meeting id to shortcode (For instance: [etn_zoom_api_link meeting_id =’123456789′ link_only=’no’]', 'eventin'); ?></p>
										<p><?php echo esc_html__('You can also create meeting and display from elementor widget.', 'eventin'); ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="tw-accordion-item">
							<div class="tw-accordion-content-wrapper">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 2L8 8L2 2" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<div class="tw-accordion-content">
									<h3 class="tw-accordion-title">
										<?php echo esc_html__('How can I translate event content into my own language?', 'eventin'); ?>
									</h3>
									<div class="tw-accordion-cotent">
										<p><?php echo  Helper::kses('If you want to translate in a single language then you can use the <a href="https://wordpress.org/plugins/loco-translate/" target="_blank">Loco Translate</a> plugin. For multi-language translation, you can use the <a href="https://wpml.org/" target="_blank">WPML</a> translator plugin. Simply install the plugin and activate it. After that, please complete the basic settings and you are good to go.', 'eventin'); ?></p>
										<p><?php echo  Helper::kses('For “Loco Translate”, you can check this <a href="https://youtu.be/6qLkdl97PU0" target="_blank">video tutorial</a> and <a href="https://localise.biz/wordpress/plugin/beginners" target="_blank">documentation</a>. Also, for the WPML multi-language translation, here is the <a href="https://www.youtube.com/watch?v=O7zrroWtrpQ&list=PLs3PkRpIy7BwP5uGV7H2uX34E3MWgdAsH" target="_blank">video</a>  and <a href="https://wpml.org/documentation/" target="_blank">documentation</a>  link.', 'eventin'); ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="tw-accordion-item">
							<div class="tw-accordion-content-wrapper">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 2L8 8L2 2" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<div class="tw-accordion-content">
									<h3 class="tw-accordion-title">
										<?php echo esc_html__('How to upgrade to Eventin PRO?', 'eventin'); ?>
									</h3>
									<div class="tw-accordion-cotent">
										<p><?php echo  Helper::kses('If you buy the plugin from CodeCanyon then you do not need license key activation. Just use the Envato Marketplace plugin to get regular updates. If you buy the plugin from <a href="https://themewinter.com/eventin/" target="_blank">Themewinter website</a> then follow our <a href="https://support.themewinter.com/docs/plugins/plugin-docs/general-settings-eventin/license/" target="_blank">documentation here</a> for activating the PRO license.', 'eventin'); ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="tw-accordion-item">
							<div class="tw-accordion-content-wrapper">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 2L8 8L2 2" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<div class="tw-accordion-content">
									<h3 class="tw-accordion-title">
										<?php echo esc_html__('How can I skip adding the price for the ticket?', 'eventin'); ?>
									</h3>
									<div class="tw-accordion-cotent">
										<p><?php echo esc_html__('If you want to host an event without selling tickets, you can arrange it with WPEventin. Woocommerce is not mandatory in this scenario. In that case, simply skip enabling the “Sell on Woocommerce” option. On the other hand, you can enable selling tickets and put the event ticket price as zero(0), then it will act as a free event and your customers don\'t have to pay for that event. In this way, you can skip the price of the tickets.', 'eventin'); ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="tw-accordion-item">
							<div class="tw-accordion-content-wrapper">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 2L8 8L2 2" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<div class="tw-accordion-content">
									<h3 class="tw-accordion-title">
										<?php echo esc_html__('I am getting a 404 error. What to do now?', 'eventin'); ?>
									</h3>
									<div class="tw-accordion-cotent">
										<p><?php echo esc_html__('You can get a 404 Not Found error for many reasons in WordPress. However, the most common reason for the 404 error is page permalink. In order to solve the permalink error, please login to your WordPress dashboard and click on settings. Then from permalink, select “ Post name” as permalink structure and save the settings. Solution key wp-admin -> settings -> permalink -> Post name -> Save Changes.', 'eventin'); ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="tw-accordion-item">
							<div class="tw-accordion-content-wrapper">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 2L8 8L2 2" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<div class="tw-accordion-content">
									<h3 class="tw-accordion-title">
										<?php echo esc_html__('Does Eventin support different page builders?', 'eventin'); ?>
									</h3>
									<div class="tw-accordion-cotent">
										<p><?php echo esc_html__('Yes. Eventin supports different page builders like DIVI builder, Visual Composer and Elementor page builder. You can use a shortcode for DIVI builder and Visual Composer. And the plugin has 15+ dedicated widgets for the Elementor page builder. Most of the widgets are FREE to use. However, <a href="https://themewinter.com/eventin/" target="_blank">Eventin PRO</a> will be required for a few specific widgets.', 'eventin'); ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Commmon FAQ wrapper end -->
			<?php
		}

		public static function enqueue_scripts() {
			$tw_welcome_image = \Wpeventin::plugin_url() . 'utils/pro-awareness/assets/welcome_image.png';
			echo "
			<script>

            </script>

            <style>
            .wpmet_pro_a-grid-container {
                max-width: 1140px;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
                box-sizing: border-box;
                margin-top: 50px;
            }
            .wpmet_pro_a-row {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
                box-sizing: border-box;
            }
        
            .wpmet_pro_a-grid {
                padding-right: 15px;
                padding-left: 15px;
                position: relative;
                width: 100%;
                min-height: 1px;
                box-sizing: border-box;
            }
        
			.wpmet_pro_a_thumb {
				min-height: unset;
				margin-bottom: 35px;
				display: block;
				border-radius: inherit;
			}
        

            .wp-submenu > li > a{
                position: relative;
            }
        

			/* Themewinter sections style */
			.tw-grid-row {
				display: grid;
				grid-template-columns: 1fr;
				gap: 30px;
			}
			.tw-help-section {
				max-width: 100%;
				padding: 0 40px 0 20px;
			}
			.tw-help-hero {
				padding: 30px 60% 30px 40px;
				background-color: #fff;
				background-image: none;
				border-radius: 6px;
				margin: 30px 0;
				background-size: 60%;
				background-repeat: no-repeat;
				background-position: 100% 100%;
			}

			.tw-help-hero h2 {
				font-size: 30px;
				line-height: 1;
				color: #000000;
				margin: 0;
			}
			.tw-help-hero p {
				font-size: 1rem;
				color: #71787C;
			}
			.tw-grid-item-inner {
				padding: 40px;
				display: block;
				text-align: left;
				background-color: #fff;
				border-radius: 8px;
				text-decoration: none;
				min-height: 270px;
				box-sizing: border-box;
			}
			.tw-grid-title {
				font-size: 20px;
				margin: 0;
				color: #000;
				display: block;
				line-height: 24px;
			}
			.tw-grid-button {
				margin-bottom: 0;
				display: inline-block;
				border: 2px solid #E3106E;
				border-radius: 6px;
				padding: 9px 16px;
				font-size: 15px;
				font-weight: 600;
				color: #E3106E;
				margin-top: 20px;
				line-height: 1;
				transition: .3s ease;
			}
			.tw-grid-button:hover {
				transform: translateY(-5px);
				background-color: #E3106E;
				color: #ffffff;
			}
			.tw-faq-wrapper {
				background: #fff;
				padding: 40px;
				margin-top: 30px;
				border-radius: 8px;
			}
			.tw-accordion-content-wrapper {
				border: 1px solid #E4E6EB;
				border-radius: 8px;
				padding: 20px;
				display: flex;
				align-items: baseline;
			}
			.tw-accordion-content {
				padding-left: 20px;
			}
			.tw-accordion-content-wrapper svg {
				transition: all .3s ease;
				flex: 0 0 20px;
			}
			.tw-accordion-content-wrapper.item-active {
				background-color: #F7F8FB;
			}
			.tw-accordion-content-wrapper.item-active svg {
				transform: rotate(180deg);
			}
			.tw-section-title {
				margin: 0 0 30px;
				font-size: 24px;
				font-weight: 600;
				line-height: 28px;
			}
			.tw-accordion-title {
				margin: 0;
				cursor: pointer;
				font-size: 18px;
				font-weight: 700;
			}
			
			.tw-accordion-wrapper {
				display: grid;
				grid-template-columns: 1fr;
				gap: 20px;
			}
			.tw-accordion-cotent {
				display: none;
				padding-top: 10px;
			}
			.tw-accordion-cotent p {
				margin: 0;
				font-size: 1rem;
				color: #44464B;
			}
			.tw-accordion-cotent p:not(:last-child) {
				margin-bottom: 10px;
			}
			@media (min-width: 991px) {
				.tw-grid-row {
					grid-template-columns: repeat(3, 1fr);
				}
				.tw-help-hero {
					background-image: url($tw_welcome_image);
				}
				.tw-accordion-content-wrapper {
					padding: 25px 30px;
				}
				.tw-help-hero h2 {
					font-size: 36px;
				}
				
            }
			@media (min-width: 1366px) {
				.tw-grid-row {
					grid-template-columns: repeat(4, 1fr);
				}
				.tw-help-hero {
					background-position: 95% 100%;
					background-size: 35%;
				}
				.tw-accordion-wrapper {
					grid-template-columns: repeat(2, 1fr);
				}
            }
            @media (max-width: 1024px) and (min-width: 768px) {
				.tw-grid-row {
					grid-template-columns: repeat(2, 1fr);
				}
				.tw-help-hero {
					background-position: 154% 0px;
					background-size: 74%;
				}
            }
			@media (max-width: 991px) {
                .tw-help-hero {
                    padding: 30px;
                }
				.tw-faq-wrapper {
					padding: 30px;
				}
            }
        </style>
		";
		}

		public function insert_plugin_links($links) {

			foreach($this->action_links as $action_link) {

				if(!empty($action_link['link']) && !empty($action_link['text'])) {

					$attributes = '';

					if(!empty($action_link['attr'])) {

						foreach($action_link['attr'] as $key => $val) {

							$attributes .= $key.'="'.esc_attr($val).'" ';
					    }
					}

					$links[] = sprintf('<a href="%s" ' . $attributes . ' > %s </a>', $action_link['link'], esc_html($action_link['text']));
				}
			}


			return $links;
		}

		public function insert_plugin_row_meta($links, $file) {
			if($file == $this->plugin_file) {

				foreach($this->row_meta_links as $meta) {

					if(!empty($meta['link']) && !empty($meta['text'])) {

						$attributes = '';

						if(!empty($meta['attr'])) {

							foreach($meta['attr'] as $key => $val) {

								$attributes .= $key.'="'.esc_attr($val).'" ';
							}
						}

						$links[] = sprintf('<a href="%s" %s > %s </a>', $meta['link'], $attributes, esc_html($meta['text']));
					}
				}

			}

			return $links;
		}

		public function set_plugin_action_link($text, $link, $attr = []) {

			$this->action_links[] = [
				'text' => $text,
				'link' => $link,
				'attr'  => $attr,
			];

			return $this;
		}

		public function set_plugin_row_meta($text, $link, $attr = []) {

			$this->row_meta_links[] = [
				'text' => $text,
				'link' => $link,
				'attr'  => $attr,
			];

			return $this;
		}

		public function generate_menus() {
			add_filter('plugin_action_links_' . $this->plugin_file, [$this, 'insert_plugin_links']);
			add_filter('plugin_row_meta', [$this, 'insert_plugin_row_meta'], 10, 2);

			if(!empty($this->parent_menu_slug)) {
				$this->prepare_grid_links();
				$this->prepare_pro_links();
			}
		}

		public static function init() {
			add_action('admin_head', [__CLASS__, 'enqueue_scripts']);
		}

		public function call() {
			add_action('admin_menu', [$this, 'generate_menus'], 99999);
		}
	}

endif;
