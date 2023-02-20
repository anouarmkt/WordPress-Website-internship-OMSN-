<?php
/**
 * Ajax Controller Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers;

use RT\Team\Controllers\Admin\Ajax as AdminAjax;
use RT\Team\Controllers\Frontend\Ajax as FrontendAjax;
use RT\Team\Abstracts\Controller;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Ajax Controller Class.
 */
class AjaxController extends Controller {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Ajax.
	 *
	 * @var array
	 */
	private $ajax = [];

	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	public function classes() {
		$this->admin_ajax()->frontend_ajax();

		return $this->ajax;
	}

	/**
	 * Admin Ajax
	 *
	 * @return Object
	 */
	private function admin_ajax() {
		$this->ajax[] = AdminAjax\Skill::class;
		$this->ajax[] = AdminAjax\Social::class;
		$this->ajax[] = AdminAjax\Preview::class;
		$this->ajax[] = AdminAjax\Settings::class;
		$this->ajax[] = AdminAjax\Shortcode::class;
		$this->ajax[] = AdminAjax\ProfileImage::class;
		$this->ajax[] = AdminAjax\DefaultFilter::class;

		return $this;
	}

	/**
	 * Frontend Ajax
	 *
	 * @return Object
	 */
	private function frontend_ajax() {
		$this->ajax[] = FrontendAjax\SmartPopup::class;
		$this->ajax[] = FrontendAjax\MultiPopup::class;
		$this->ajax[] = FrontendAjax\SinglePopup::class;
		$this->ajax[] = FrontendAjax\SpecialLayout::class;

		return $this;
	}
}
