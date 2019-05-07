<?php
namespace WhiteZimmer;

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
	/*
	public function widget_scripts() {
		wp_register_script( 'elementor-hello-world', plugins_url( '/assets/js/hello-world.js', __FILE__ ), [ 'jquery' ], false, true );
	}
	*/

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/hello-world.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Hello_World() );
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
		// add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		add_action( 'init', [ $this, 'register_post_types' ], 0 );

		add_action( 'init', [ $this, 'register_taxonomies' ], 0 );

	}
}

// Instantiate Plugin Class
Plugin::instance();
