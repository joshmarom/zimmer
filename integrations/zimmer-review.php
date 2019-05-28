<?php
namespace WhiteZimmer\Integrations;

use ElementorPro\Modules\Forms\Classes\Action_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Zimmer_Review extends Action_Base {

	public function get_name() {
		return 'zimmer-review';
	}

	public function get_label() {
		return 'Zimmer Review';
	}

	public function register_settings_section( $widget ) {}

	public function on_export( $element ) {}

	public function run( $record, $ajax_handler ) {
		$data = $record->get( 'fields' );

		$review_id = wp_insert_post( [
			'post_type' => 'review',
			'post_title' => $data['title']['value'],
			'post_parent' => $data['zimmer_id']['value'],
			'post_content' => $data['review']['value'],
		] );

		unset( $data['title'], $data['zimmer_id'], $data['review'] );

		foreach ( $data as $key => $field ) {
			add_post_meta( $review_id, $key, $field['value'] );
		}
	}
}
