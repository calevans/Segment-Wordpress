<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/public
 * @author     Your Name <email@example.com>
 */
class Segment_For_Wordpress_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $segment_for_wordpress The ID of this plugin.
	 */
	private $segment_for_wordpress;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/*************************************************************
	 * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
	 *
	 * @tutorial access_plugin_admin_public_methodes_from_inside.php
	 */
	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    20180622
	 * @var object      The main class.
	 */
	public $main;
	// END ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $segment_for_wordpress The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	// public function __construct( $segment_for_wordpress, $version ) {

	// 	$this->segment_for_wordpress = $segment_for_wordpress;
	// 	$this->version = $version;

	// }

	/*************************************************************
	 * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
	 *
	 * @tutorial access_plugin_admin_public_methodes_from_inside.php
	 */
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $segment_for_wordpress The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $segment_for_wordpress, $version, $plugin_main ) {

		$this->segment_for_wordpress = $segment_for_wordpress;
		$this->version               = $version;
		$this->main                  = $plugin_main;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * The Segment_For_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 *
		 */

		//wp_enqueue_style( $this->segment_for_wordpress, plugin_dir_url( __FILE__ ) . 'css/segment-for-wordpress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Segment_For_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Segment_For_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'js-cookie', plugin_dir_url( __FILE__ ) . 'js/js.cookie.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->segment_for_wordpress, plugin_dir_url( __FILE__ ) . 'js/segment-for-wordpress-public.js', array( 'jquery' ), $this->version, true );

		$current_user = wp_get_current_user();
		$ajax_url     = admin_url( 'admin-ajax.php' );
		$security     = wp_create_nonce( 'segment-in8-nonce' );
		$post_id      = get_the_ID();
		$cookie_hash  = COOKIEHASH;
		wp_localize_script( $this->segment_for_wordpress, 'segment_for_wp', array(
			'ajax_url' => ( $ajax_url ),
			'_nonce'   => ( $security ),
		) );

	}
}