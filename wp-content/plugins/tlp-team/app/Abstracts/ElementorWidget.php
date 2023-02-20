<?php
/**
 * Abstract Class for ElementorWidget.
 *
 * @package RT_Team
 */

namespace RT\Team\Abstracts;

use RT\Team\Helpers\Fns;
use Elementor\Widget_Base as Elementor;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Abstract Class for ElementorWidget.
 */
abstract class ElementorWidget extends Elementor {

	/**
	 * Widget Title.
	 *
	 * @var String
	 */
	protected $elName;

	/**
	 * Widget name.
	 *
	 * @var String
	 */
	protected $elBase;

	/**
	 * Widget categories.
	 *
	 * @var String
	 */
	protected $elCategory;

	/**
	 * Widget icon class.
	 *
	 * @var String
	 */
	protected $elIcon;

	/**
	 * Widget prefix.
	 *
	 * @var String
	 */
	public $elPrefix;

	/**
	 * Widget controls.
	 *
	 * @var array
	 */
	public $elControls = [];

	/**
	 * PRO Label HTML.
	 *
	 * @var String
	 */
	public $proLabel = '';

	/**
	 * Class constructor.
	 *
	 * @param array $data default data.
	 * @param array $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->elCategory = 'rttm-elementor-widgets';
		$this->elPrefix   = 'rttm_el_';
		$this->proLabel   = __( '<span class="rttm-pro-label">Pro</span>', 'tlp-team' );

		parent::__construct( $data, $args );
	}

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->elBase;
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->elName;
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return $this->elIcon;
	}

	/**
	 * Widget Category
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ $this->elCategory ];
	}

	/**
	 * Elementor Promotional section controls.
	 *
	 * @param array $fields Elementor Controls.
	 * @return array
	 *
	 * @access public
	 */
	public function promoContent( $fields ) {
		if ( rttlp_team()->has_pro() ) {
			return $fields;
		}

		$promoFields = [];

		$promoFields[] = [
			'mode'  => 'section_start',
			'id'    => 'rttm_el_pro_alert',
			'label' => sprintf(
				'<span style="color: #f54">%s</span>',
				esc_html__( 'Go Premium for More Features', 'tlp-team' )
			),
			'tab'   => \Elementor\Controls_Manager::TAB_LAYOUT,
		];

		$promoFields[] = [
			'type' => \Elementor\Controls_Manager::RAW_HTML,
			'id'   => 'rttm_el_get_pro',
			'raw'  => '<div class="elementor-nerd-box"><div class="elementor-nerd-box-title" style="margin-top: 0; margin-bottom: 20px;">Unlock more possibilities</div><div class="elementor-nerd-box-message"><span class="pro-feature" style="font-size: 13px;"> Get the <a href="' . esc_url( rttlp_team()->pro_version_link() ) . '" target="_blank" style="color: #f54">Pro version</a> for more stunning layouts and customization options.</span></div><a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="' . esc_url( rttlp_team()->pro_version_link() ) . '" target="_blank">Get Pro</a></div>',
		];

		$promoFields[] = [
			'mode' => 'section_end',
		];

		return array_merge( $fields, $promoFields );
	}

	/**
	 * Widget Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->layoutTab()->settingsTab()->styleTab();

		if ( empty( $this->elControls ) ) {
			return;
		}

		$this->elControls = $this->promoContent( $this->elControls );

		Fns::addElControls( $this->elControls, $this );
	}

	/**
	 * Starts an Elementor Section
	 *
	 * @param string $id Section ID.
	 * @param string $label Section label.
	 * @param object $tab Tab ID.
	 * @param array  $conditions Section Condition.
	 * @param array  $condition Section Conditions.
	 * @return void
	 */
	public function startSection( $id, $label, $tab, $conditions = [], $condition = [] ) {
		$this->elControls[] = [
			'mode'       => 'section_start',
			'id'         => $this->elPrefix . $id,
			'label'      => $label,
			'tab'        => $tab,
			'condition'  => $condition,
			'conditions' => $conditions,
		];
	}

	/**
	 * Ends an Elementor Section
	 *
	 * @return void
	 */
	public function endSection() {
		$this->elControls[] = [
			'mode' => 'section_end',
		];
	}

	/**
	 * Starts an Elementor tab group.
	 *
	 * @param string $id Tab ID.
	 * @param array  $conditions Tab condition.
	 * @return void
	 */
	public function startTabGroup( $id, $conditions = [], $condition = [] ) {
		$this->elControls[] = [
			'mode'       => 'tabs_start',
			'id'         => $this->elPrefix . $id,
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Ends an Elementor tab group.
	 *
	 * @param array $conditions Tab condition.
	 * @return void
	 */
	public function endTabGroup( $conditions = [], $condition = [] ) {
		$this->elControls[] = [
			'mode'       => 'tabs_end',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Starts an Elementor tab
	 *
	 * @param string $id Section ID.
	 * @param string $label Section label.
	 * @param array  $conditions Tab condition.
	 * @return void
	 */
	public function startTab( $id, $label, $conditions = [], $condition = [] ) {
		$this->elControls[] = [
			'mode'       => 'tab_start',
			'id'         => $this->elPrefix . $id,
			'label'      => $label,
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Ends an Elementor tab.
	 *
	 * @param array $conditions Tab condition.
	 * @return void
	 */
	public function endTab( $conditions = [], $condition = [] ) {
		$this->elControls[] = [
			'mode'       => 'tab_end',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Starts an Elementor tab
	 *
	 * @param string $id Heading ID.
	 * @param string $label Heading label.
	 * @param string $separator Section separator.
	 * @param array  $conditions Section Condition.
	 * @param array  $condition Section Conditions.
	 * @return void
	 */
	public function elHeading( $id, $label, $separator = null, $conditions = [], $condition = [] ) {
		$this->elControls[] = [
			'type'            => 'html',
			'id'              => $id,
			'raw'             => sprintf(
				'<h3 class="rttm-elementor-group-heading">%s</h3>',
				$label
			),
			'separator'       => $separator,
			'content_classes' => 'elementor-panel-heading-title',
			'conditions'      => $conditions,
			'condition'       => $condition,
		];
	}

	/**
	 * Checks for preview mode.
	 *
	 * @return boolean
	 */
	public function isPreview() {
		return \Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->editor->is_edit_mode();
	}

	/**
	 * Prints a control notification.
	 *
	 * @param string $label Field label.
	 * @return string
	 */
	public function isProControl( $label ) {
		if ( rttlp_team()->has_pro() ) {
			return $label;
		}

		return $label . $this->proLabel;
	}

	/**
	 * Elementor Edit mode need some extra js for isotop reinitialize
	 *
	 * @return mixed
	 */
	public function edit_mode_script() {
		if ( ! $this->isPreview() ) {
			return;
		}

		$ajaxurl = '';

		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
			$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else {
			$ajaxurl .= admin_url( 'admin-ajax.php' );
		}
		?>

		<script>
			var ttp = {
				ajaxurl: '<?php echo esc_url( $ajaxurl ); ?>',
				nonceID: '<?php echo esc_attr( Fns::nonceID() ); ?>',
				nonce  : '<?php echo esc_attr( wp_create_nonce( Fns::nonceText() ) ); ?>',
				is_pro : '<?php echo esc_attr( rttlp_team()->has_pro() ? 'true' : 'false' ); ?>'
			};

			initTlpElTeam();

			var isIsotope     = jQuery('.tlp-team-isotope');
			var isGridIsotope = jQuery('.rt-elementor-container[data-layout*="layout"] .masonry-grid-item');

			if(isIsotope.length > 0) {
				isIsotope.isotope();
			}

			if(isGridIsotope.length > 0) {
				isGridIsotope.isotope();
			}

			if('true' === ttp.is_pro) {
				rtAjaxSkillAnimation();
			}
		</script>

		<?php
	}

	/**
	 * Controls for layout tab
	 *
	 * @return object
	 */
	abstract protected function layoutTab();

	/**
	 * Controls for settings tab
	 *
	 * @return object
	 */
	abstract protected function settingsTab();

	/**
	 * Controls for style tab
	 *
	 * @return object
	 */
	abstract protected function styleTab();
}
