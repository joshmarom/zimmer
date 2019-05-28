<?php
namespace WhiteZimmer\Integrations;

use Elementor\Controls_Manager;
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

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_review',
			[
				'label' => __( 'Review', 'elementor-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'notification_to',
			[
				'label' => __( 'Notification To', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'owner' => 'Owner',
					'reviewer' => 'Reviewer',
					'site_admin' => 'Site Admin',
				],
				'render_type' => 'none',
			]
		);

		$widget->end_controls_section();
	}

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

		$this->send_notification( $record );
	}

	private function send_notification( $record ) {
		$notification_to = $record->get_form_settings( 'notification_to' );

		if ( ! empty( $notification_to ) ) {
			return;
		}

		$data = $record->get( 'fields' );
		$zimmer_id = $data['zimmer_id']['value'];
	}
}
