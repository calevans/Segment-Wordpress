<?php

/**
 *-----------------------------------------
 * Do not delete this line
 * Added for security reasons: http://codex.wordpress.org/Theme_Development#Template_Files
 *-----------------------------------------
 */
defined( 'ABSPATH' ) or die( "error" );
/*-----------------------------------------*/

/**
 * Handle AJAX calls
 */
class Segment_For_Wordpress_AJAX {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// Backend AJAX calls
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'wp_ajax_admin_backend_call', array( $this, 'ajax_backend_call' ) );
		}
		// Frontend AJAX calls
		add_action( 'wp_ajax_admin_ajax_frontend_call', array( $this, 'ajax_frontend_call' ) );
		add_action( 'wp_ajax_nopriv_ajax_frontend_call', array( $this, 'ajax_frontend_call' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 * @since     1.0.0
	 *
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Handle AJAX: Backend Example
	 *
	 * @since    1.0.0
	 */
	public function ajax_backend_call() {
		// Security check
		check_ajax_referer( 'segment-in8-nonce' );
		$response = 'OK';
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Handle AJAX: test
	 *
	 * @since    1.0.0
	 */
	public function ajax_frontend_call() {
		check_ajax_referer( 'segment-in8-nonce' );
		if ( ! check_ajax_referer( 'segment-in8-nonce' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}
		$response = 'OK';
		wp_send_json_success( $response );
		wp_die();
	}
}
