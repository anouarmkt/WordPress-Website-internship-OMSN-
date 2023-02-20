<?php
/**
 * Admin Shortcode Metabox Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Metabox;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Shortcode Metabox Class.
 */
class ShortcodeMeta {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'add_meta_boxes', [ $this, 'team_sc_meta_boxes' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'save_post', [ $this, 'save_team_sc_meta_data' ], 10, 3 );
		add_action( 'edit_form_after_title', [ $this, 'team_sc_after_title' ] );
		add_action( 'admin_init', [ $this, 'tlp_team_pro_remove_all_meta_box' ] );
		add_action( 'before_delete_post', [ $this, 'before_delete_post' ], 10, 2 );

		if ( ( isset( $_GET['post'] ) && 'team-sc' === get_post_type( sanitize_text_field( wp_unslash( $_GET['post'] ) ) ) ) ||
			( isset( $_GET['post_type'] ) && 'team-sc' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) ) ||
			( isset( $_GET['post'] ) && 'team' === get_post_type( sanitize_text_field( wp_unslash( $_GET['post'] ) ) ) ) ||
			( isset( $_GET['post_type'] ) && 'team' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) ) ||
			( isset( $_GET['page'] ) && 'tlp_team_settings' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) )
		) {
			add_action( 'admin_footer', [ $this, 'pro_alert_html' ] );
		}
	}


	function pro_alert_html() {
		if ( function_exists( 'rttmp' ) ) {
			return;
		}
		$html  = '';
		$html .= '<div class="rttm-document-box rttm-alert rttm-pro-alert">
                <div class="rttm-box-icon"><i class="dashicons dashicons-lock"></i></div>
                <div class="rttm-box-content">
                    <h3 class="rttm-box-title">' . esc_html__( 'Pro field alert!', 'tlp-team' ) . '</h3>
                    <p><span></span>' . esc_html__( 'Sorry! this is a pro field. To use this field, you need to use pro plugin.', 'tlp-team' ) . '</p>
                    <a href="' . esc_url( rttlp_team()->pro_version_link() ) . '" target="_blank" class="rt-admin-btn">' . esc_html__( 'Upgrade to pro', 'tlp-team' ) . '</a>
                    <a href="#" target="_blank" class="rttm-alert-close rttm-pro-alert-close">x</a>
                </div>
            </div>';

		Fns::print_html( $html );
	}

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * @return void
	 */
	public function before_delete_post( $post_id, $post ) {
		if ( rttlp_team()->shortCodePT !== $post->post_type ) {
			return $post_id;
		}
		Fns::removeGeneratorShortcodeCss( $post_id );
	}

	public function team_sc_after_title( $post ) {
		if ( rttlp_team()->shortCodePT !== $post->post_type ) {
			return;
		}

		$html  = null;
		$html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
		$html .= '<p><input type="text" onfocus="this.select();" readonly="readonly" value="[tlpteam id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]" class="large-text code tlp-code-sc">
            <input type="text" onfocus="this.select();" readonly="readonly" value="&#60;&#63;php echo do_shortcode( &#39;[tlpteam id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]&#39; ) &#63;&#62;" class="large-text code tlp-code-sc">
            </p>';
		$html .= '</div></div>';

		Fns::print_html( $html, true );
	}

	public function tlp_team_pro_remove_all_meta_box() {
		if ( is_admin() ) {
			add_filter(
				'get_user_option_meta-box-order_{rttlp_team()->shortCodePT}',
				[ $this, 'remove_all_meta_boxes_team_sc' ]
			);
		}
	}

	public function remove_all_meta_boxes_team_sc() {
		global $wp_meta_boxes;
		$publishBox                                 = $wp_meta_boxes[ rttlp_team()->shortCodePT ]['side']['core']['submitdiv'];
		$scBox                                      = $wp_meta_boxes[ rttlp_team()->shortCodePT ]['normal']['high']['tlp_team_sc_settings_meta'];
		$scPreviewBox                               = $wp_meta_boxes[ rttlp_team()->shortCodePT ]['normal']['high']['tlp_team_sc_preview_meta'];
		$wp_meta_boxes[ rttlp_team()->shortCodePT ] = [
			'side'   => [ 'core' => [ 'submitdiv' => $publishBox ] ],
			'normal' => [
				'high' => [
					'tlp_team_sc_settings_meta' => $scBox,
					'tlp_team_sc_preview_meta'  => $scPreviewBox,
				],
			],
		];

		return [];
	}

	public function admin_enqueue_scripts() {

		global $pagenow, $typenow;
		// validate page
		if ( ! in_array( $pagenow, [ 'post.php', 'post-new.php', 'edit.php' ] ) ) {
			return;
		}
		if ( $typenow != rttlp_team()->shortCodePT ) {
			return;
		}
		wp_dequeue_script( 'autosave' );
		wp_enqueue_media();

		wp_enqueue_script( 'select2' );
		wp_enqueue_style( 'select2' );

		// scripts
		wp_enqueue_script(
			[
				'jquery',
				'wp-color-picker',
				'ace-code-highlighter-js',
				'ace-mode-js',
				'tlp-isotope-js',
				'tlp-image-load-js',
				'tlp-swiper',
				'rt-pagination',
				'tlp-scrollbar',
				'rt-tooltip',
				'tlp-actual-height-js',
				'tlp-sc-preview',
				'tlp-team-admin-js',
			]
		);

		// styles
		wp_enqueue_style(
			[
				'wp-color-picker',
				'tlp-swiper',
				'rt-pagination',
				'tlp-fontawsome',
				'rt-team-css',
				'tlp-team-admin-css',
			]
		);

		// when change dmeo url, change, Carousel 1 url line 177
		$demo_site = 'https://www.radiustheme.com/demo/plugins/team/';

		$layout_group = [
			'grid'    => [
				[
					'name'  => 'Layout 1',
					'value' => 'layout1',
					'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/layouts/layout1.png',
					'demo'  => $demo_site . 'layout-1',
				],
				[
					'name'  => 'Layout 3',
					'value' => 'layout3',
					'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/layouts/layout3.png',
					'demo'  => $demo_site . 'layout-3',
				],
			],
			'list'    => [
				[
					'name'  => 'Layout 2',
					'value' => 'layout2',
					'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/layouts/layout2.png',
					'demo'  => $demo_site . 'layout-2',
				],
			],
			'slider'  => [
				[
					'name'  => 'Carousel 1',
					'value' => 'carousel1',
					'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/layouts/carousel1.png',
					'demo'  => $demo_site . 'slider-layout-1',
				],
			],
			'isotope' => [
				[
					'name'  => 'Isotope Free',
					'value' => 'isotope-free',
					'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/layouts/isotope2.png',
					'demo'  => $demo_site . 'isotope-filter-1',
				],
			],
		];

		$layout_group = apply_filters( 'rttm_layout_groups', $layout_group );

		$layout = get_post_meta( get_the_ID(), 'layout', true );
		if ( ! $layout ) {
			$layout = 'layout1';
		}

		wp_localize_script(
			'tlp-team-admin-js',
			'ttp',
			[
				'nonceID'      => Fns::nonceID(),
				'nonce'        => wp_create_nonce( Fns::nonceText() ),
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'layout_group' => $layout_group,
				'layout'       => $layout,
			]
		);
	}

	public function team_sc_meta_boxes() {

		add_meta_box(
			'tlp_team_sc_settings_meta',
			esc_html__( 'Shortcode Generator', 'tlp-team' ),
			[ $this, 'tlp_team_sc_settings_selection' ],
			rttlp_team()->shortCodePT,
			'normal',
			'high'
		);
		add_meta_box(
			'tlp_team_sc_preview_meta',
			esc_html__( 'Layout Preview', 'tlp-team' ),
			[ $this, 'tlp_team_sc_preview_selection' ],
			rttlp_team()->shortCodePT,
			'normal',
			'high'
		);

		add_meta_box(
			'rt_plugin_team_sc_pro_information',
			esc_html__( 'Pro Documentation', 'tlp-team' ),
			[ $this, 'rt_plugin_team_sc_pro_information' ],
			rttlp_team()->shortCodePT,
			'side'
		);
	}

	public function tlp_team_sc_preview_selection() {
		$html  = null;
		$html .= "<div class='tlp-team-response'><span class='spinner'></span></div>";
		$html .= "<div id='tlp-team-preview-container'></div>";

		Fns::print_html( $html );
	}

	public function rt_plugin_team_sc_pro_information() {

		$html = '<div class="rt-document-box">
                            <div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
                            <div class="rt-box-content">
                                <h3 class="rt-box-title">Documentation</h3>
                                    <p>Get started by spending some time with the documentation we included step by step process with screenshots with video.</p>
                                    <a href="' . esc_url( rttlp_team()->documentation_link() ) . '" target="_blank" class="rt-admin-btn">Documentation</a>
                            </div>
                        </div>';

		$html .= '<div class="rt-document-box">
                            <div class="rt-box-icon"><i class="dashicons dashicons-sos"></i></div>
                            <div class="rt-box-content">
                                <h3 class="rt-box-title">Need Help?</h3>
                                    <p>Stuck with something? Please create a
                        <a href="' . esc_url( rttlp_team()->ticket_link() ) . '">ticket here</a> or post on <a href="' . esc_url( rttlp_team()->fb_link() ) . '">facebook group</a>. For emergency case join our <a href="' . esc_url( rttlp_team()->radius_link() ) . '">live chat</a>.</p>
                                    <a href="' . esc_url( rttlp_team()->ticket_link() ) . '" target="_blank" class="rt-admin-btn">Get Support</a>
                            </div>
                        </div>';

		Fns::print_html( $html );
	}

	public function tlp_team_sc_settings_selection( $post ) {
		wp_nonce_field( Fns::nonceText(), Fns::nonceID() );

		// auto select tab
		$tab = get_post_meta( get_the_ID(), '_rttm_sc_tab', true );
		if ( ! $tab ) {
			$tab = 'layout';
		}
		$layout_tab      = ( $tab == 'layout' ) ? 'active' : '';
		$filtering_tab   = ( $tab == 'filtering' ) ? 'active' : '';
		$field_selection = ( $tab == 'field-selection' ) ? 'active' : '';
		$styling         = ( $tab == 'styling' ) ? 'active' : '';

		$html  = null;
		$html .= '<div id="sc-tabs" class="rt-tab-container">';
		$html .= '<ul class="rt-tab-nav">
					<li class="' . esc_attr( $layout_tab ) . '"><a href="#sc-layout"><i class="dashicons dashicons-layout"></i>' . esc_html__( 'Layout', 'tlp-team' ) . '</a></li>
					<li class="' . esc_attr( $filtering_tab ) . '"><a href="#sc-filtering"><i class="dashicons dashicons-filter"></i>' . esc_html__( 'Filtering', 'tlp-team' ) . '</a></li>
					<li class="' . esc_attr( $field_selection ) . '"><a href="#sc-field-selection"><i class="dashicons dashicons-editor-table"></i>' . esc_html__( 'Field Selection', 'tlp-team' ) . '</a></li>
					<li class="' . esc_attr( $styling ) . '"><a href="#sc-styling"><i class="dashicons dashicons-admin-customizer"></i>' . esc_html__( 'Styling', 'tlp-team' ) . '</a></li>
				</ul>';

		$html           .= '<input type="hidden" id="_rttm_sc_tab" name="_rttm_sc_tab" value="' . esc_attr( $tab ) . '" />';
		$layout_tab      = ( $tab == 'layout' ) ? 'display: block' : '';
		$filtering_tab   = ( $tab == 'filtering' ) ? 'display: block' : '';
		$field_selection = ( $tab == 'field-selection' ) ? 'display: block' : '';
		$styling         = ( $tab == 'styling' ) ? 'display: block' : '';

		$html .= '<div id="sc-layout" class="rt-tab-content" style="' . esc_attr( $layout_tab ) . '">';
		$html .= Fns::rtFieldGenerator( Options::get_sc_layout_settings_meta_fields() );
		$html .= '</div>';
		$html .= '<div id="sc-filtering" class="rt-tab-content" style="' . esc_attr( $filtering_tab ) . '">';
		$html .= Fns::rtFieldGenerator( Options::get_sc_query_filter_meta_fields() );
		$html .= '</div>';

		$html .= '<div id="sc-field-selection" class="rt-tab-content" style="' . esc_attr( $field_selection ) . '">';
		$html .= Fns::rtFieldGenerator( Options::get_sc_field_selection_meta() );
		$html .= '</div>';

		$html .= '<div id="sc-styling" class="rt-tab-content" style="' . esc_attr( $styling ) . '">';
		$html .= Fns::rtFieldGenerator( Options::get_sc_field_style_meta() );
		$html .= '</div>';
		$html .= '</div>';

		Fns::print_html( $html, true );
	}

	/**
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @return integer | void
	 */
	public function save_team_sc_meta_data( $post_id, $post, $update ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( ! Fns::verifyNonce() ) {
			return $post_id;
		}

		if ( rttlp_team()->shortCodePT != $post->post_type ) {
			return $post_id;
		}

		$mates = Fns::getScTeamMetaFields();

		if ( is_array( $mates ) && ! empty( $mates ) ) {
			foreach ( $mates as $metaKey => $field ) {
				$value = ! empty( $_REQUEST[ $metaKey ] ) ? Fns::sanitize( $field, $_REQUEST[ $metaKey ] ) : null;

				if ( empty( $field['multiple'] ) ) {
					update_post_meta( $post_id, $metaKey, $value );
				} else {
					delete_post_meta( $post_id, $metaKey );
					if ( is_array( $value ) && ! empty( $value ) ) {
						foreach ( $value as $item ) {
							add_post_meta( $post_id, $metaKey, $item );
						}
					}
				}
			}
		}

		Fns::generatorShortcodeCss( $post_id );
		// save current tab
		$sc_tab = isset( $_REQUEST['_rttm_sc_tab'] ) ? sanitize_text_field( $_REQUEST['_rttm_sc_tab'] ) : '';
		update_post_meta( $post_id, '_rttm_sc_tab', $sc_tab );
	}
}
