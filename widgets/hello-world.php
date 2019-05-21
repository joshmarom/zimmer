<?php
namespace WhiteZimmer\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Heading;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Alt_Heading extends Widget_Heading {

	public function get_name() {
		// `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
		return 'alt-heading';
	}

	public function get_title() {
		return __( 'Alternative Heading', 'zimmer' );
	}

	public function get_icon() {
		return 'eicon-post-title';
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->remove_control( 'size' );

		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'link',
			]
		);

		$this->add_control(
			'btn_style_class',
			[
				'label' => 'Button Style',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fancy' => 'Fancy',
					'stylish' => 'Stylish',
					'rounded' => 'Rounded',
					'square' => 'Square',
				],
				'prefix_class' => 'btn-style-',
			]
		);

		$this->end_injection();
	}

	public function get_common_args() {
		return [
			'_css_classes' => [
				'default' => 'entry-title',
			],
		];
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-alt-heading elementor-widget-' . parent::get_name();
	}
}

