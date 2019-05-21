<?php
namespace WhiteZimmer;

use Elementor\Controls_Manager;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0
	 * @access public
	 */

	public function widget_assets() {
		wp_register_script( 'jquery-mosaic', plugins_url( '/assets/js/lib/jquery-mosaic/jquery.mosaic.js', __FILE__ ), [ 'jquery' ], '0.15.3', true );
		wp_register_style( 'jquery-mosaic', plugins_url( '/assets/js/lib/jquery-mosaic/jquery.mosaic.css', __FILE__ ), [], '0.15.3' );

		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style( 'jquery-mosaic' );
		}
		wp_register_script( 'posts-gallery', plugins_url( '/assets/js/frontend/posts-gallery.js', __FILE__ ), [ 'jquery-mosaic' ], \Elementor_White_Zimmer::VERSION, true );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once __DIR__ . '/widgets/hello-world.php';
		require_once __DIR__ . '/widgets/posts-gallery.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 *
	 * @throws \Exception
	 */
	public function register_widgets( $widgets_manager ) {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		$widgets_manager->register_widget_type( new Widgets\Alt_Heading() );
		$widgets_manager->register_widget_type( new Widgets\Posts_Gallery() );
	}

	public function register_taxonomies() {
		require_once( __DIR__ . '/taxonomies.php' );
		foreach ( $taxonomies as $taxonomy_name => $taxonomy_args ) {
			register_taxonomy( $taxonomy_name, array( 'zimmer' ), $taxonomy_args );
		}
	}

	public function register_post_types() {
		require_once( __DIR__ . '/post_types.php' );
		foreach ( $post_types as $post_type_name => $post_type_args ) {
			register_post_type( $post_type_name, $post_type_args );
		}
	}

	public function change_rating_widget( $control_stack ) {
		// Update the control
		$control_stack->update_control( 'rating', [
			'type' => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		]

		 , [
			'recursive' => true, // To update an array by merging
		]


		);
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		// Register widget scripts
		 add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_assets' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		add_action( 'init', [ $this, 'register_post_types' ], 0 );

		add_action( 'init', [ $this, 'register_taxonomies' ], 0 );

		add_action( 'elementor/element/star-rating/section_rating/before_section_end', [ $this, 'change_rating_widget' ], 10, 2 );

		//add_filter( 'elementor/widget/render_content',

			/**
			 * @param string $widget_content
			 * @param \Elementor\Widget_Base $widget
			 *//*
			function ( $widget_content, $widget ) {
				if ( 'heading' !== $widget->get_name() ) {
					return $widget_content;
				}

				$settings = $widget->get_settings_for_display();

				$widget->add_render_attribute( 'heading', 'class', 'cool-heading' );

				$widget_content = '<' . $settings['html_tag'] . $widget->get_render_attribute_string( 'heading' ) . '>';
				$widget_content .= $settings['title'];
				$widget_content .= '</' . $settings['html_tag'] . '>';

			return $widget_content;

		}, 10, 2 );

		*/


		add_filter( 'elementor/frontend/widget/should_render', function( $should_render, $widget ) {
			// check if current widget is our desired widget, in this example we use login widget
			if ( 'login' !== $widget->get_name() ) {
				return $should_render;
			}
			// If we got here then its our widget so check if te user is logged in and if he is
			// then we want to stop the render so we return false
			return ! is_user_logged_in();
		}, 10, 2 );


		// render for logged in users only
		add_filter( 'elementor/frontend/widget/should_render', function( $should_render, $widget ) {
			// check if current widget is our desired widget, in this example we use form widget
			if ( 'form' !== $widget->get_name() ) {
				return $should_render;
			}
			// If we got here then its our widget so check if te user is logged in
			return is_user_logged_in();
		}, 10, 2 );
	}
}

// Instantiate Plugin Class
Plugin::instance();
