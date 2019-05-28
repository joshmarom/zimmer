<?php
namespace WhiteZimmer\Tags;

use Elementor\Core\DynamicTags\Tag;
use ElementorPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( '\Elementor\Core\DynamicTags\Tag' ) ) {
	return;
}

class Tag_Coupon extends Tag {
	public function get_name() {
		return 'tag-coupon';
	}

	public function get_title() {
		return __( 'Coupon', 'zimmer' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$settings = $this->get_settings();

		$coupon = substr( md5( time() ), 0, $settings['length'] );

		echo wp_kses_post( $coupon );
	}

	protected function _register_controls() {
		$this->add_control(
			'length',
			[
				'label' => __( 'Length', 'zimmer' ),
				'default' => 6,
			]
		);
	}
}
