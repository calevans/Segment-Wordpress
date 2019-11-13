<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://in8.io
 * @since      1.0.0
 *
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/includes
 * @author    Juan <hi@in8.io>
 */
class Segment_For_Wordpress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Segment_For_Wordpress_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $segment_for_wordpress The string used to uniquely identify this plugin.
	 */
	protected $segment_for_wordpress;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Store plugin admin class to allow public access.
	 * @var object      The admin class.
	 */
	public $admin;

	/**
	 * Store plugin public class to allow public access.
	 * @var object      The admin class.
	 */
	public $public;

	/**
	 * Store plugin main class to allow public access.
	 * @var object      The main class.
	 */
	public $main;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */

	public function __construct() {
		$this->segment_for_wordpress = 'segment-for-wordpress';
		$this->version               = '1.0.0';
		$this->main                  = $this; //ACCESS PLUGIN ADMIN PUBLIC METHODS	
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_login_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-loader.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-encrypt.php';

		/**
		 * The class for cookies
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-cookie.php';

		/**
		 * The class for the Segment js snippet
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-snippet.php';

		/**
		 * The class for identify calls
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-identify.php';

		/**
		 * The class for track calls
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-track.php';

		/**
		 * The class for HTTP track calls
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-http-track.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-segment-for-wordpress-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-segment-for-wordpress-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'login/class-segment-for-wordpress-login.php';

		/**
		 * The class responsible for defining all actions for AJAX
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-segment-for-wordpress-ajax.php';

		/**************************************
		 * EXOPITE SIMPLE OPTIONS FRAMEWORK
		 *
		 * Get Exopite Simple Options Framework
		 *
		 * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
		 * @link https://www.joeszalai.org/exopite/exopite-simple-options-framework/
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/exopite-simple-options/exopite-simple-options-framework-class.php';

		$this->loader = new Segment_For_Wordpress_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Segment_For_Wordpress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Segment_For_Wordpress_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->admin = new Segment_For_Wordpress_Admin( $this->get_segment_for_wordpress(), $this->get_version(), $this->main );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts', 25 );

		/***********************************
		 * EXOPITE SIMPLE OPTIONS FRAMEWORK
		 *
		 * Save/Update our plugin options
		 *
		 * @tutorial app_option_page_for_plugin_with_options_framework.php
		 */
		$this->loader->add_action( 'init', $this->admin, 'create_menu' );

		/********************************************
		 * RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE
		 * This function runs when WordPress completes its upgrade process
		 * It iterates through each plugin updated to see if ours is included
		 *
		 * @param $upgrader_object Array
		 * @param $options Array
		 */
		$this->loader->add_action( 'upgrader_process_complete', $this->admin, 'upgrader_process_complete', 10, 2 );

		/**
		 * Show a notice to anyone who has just updated this plugin
		 * This notice shouldn't display to anyone who has just installed the plugin for the first time
		 */
		//	$this->loader->add_action( 'admin_notices', $this->admin, 'display_update_notice' );
		// RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->public = new Segment_For_Wordpress_Public( $this->get_segment_for_wordpress(), $this->get_version(), $this->main );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_scripts', 25 );
		$this->snippet = new Segment_Snippet();
		$this->loader->add_action( 'wp_head', $this->snippet, 'render_segment_snippet' );
		$this->loader->add_action( 'admin_head', $this->snippet, 'render_segment_snippet' );
		$this->loader->add_action( 'login_head', $this->snippet, 'render_segment_snippet' );
		$this->identify = new Segment_Identify();
		$this->loader->add_action( 'wp_head', $this->identify, 'render_segment_identify' );
		$this->loader->add_action( 'admin_head', $this->identify, 'render_segment_identify' );
		$this->loader->add_action( 'login_head', $this->identify, 'render_segment_identify' );
		$this->track = new Segment_Track();
		$this->loader->add_action( 'wp_footer', $this->track, 'render_segment_track' );
		$this->loader->add_action( 'admin_footer', $this->track, 'render_segment_track' );
		$this->loader->add_action( 'login_footer', $this->track, 'render_segment_track' );

		$settings = get_exopite_sof_option( 'segment-for-wordpress' );

		// check we're tracking server side HTTP events and load hooks if so, and also check whether each hook should be loaded.
		if ( $settings['http_api_key'] ) { //double check http_api_key is set			
			$this->http_track = new Segment_HTTP_Track();

			if ( $settings['track_logins_fieldset']['track_logins'] === 'yes' ) {
				$this->loader->add_action( 'wp_login', $this->http_track, 'render_segment_HTTP_track', 9, 2 );
			}
			if ( $settings['track_signups_fieldset']['track_signups'] === 'yes' ) {
				$this->loader->add_action( 'user_register', $this->http_track, 'render_segment_HTTP_track', 9, 1 );
			}
			if ( $settings['track_comments_fieldset']['track_comments'] === 'yes' ) {
				$this->loader->add_action( 'wp_insert_comment', $this->http_track, 'render_segment_HTTP_track', 9, 2 );
			}
			if ( $settings['track_ninja_forms_fieldset']['track_ninja_forms'] === 'yes' ) {
				$this->loader->add_action( 'ninja_forms_after_submission', $this->http_track, 'render_segment_http_track', 9, 1 );
			}
			if ( $settings['track_gravity_forms_fieldset']['track_gravity_forms'] === 'yes' ) {
				$this->loader->add_action( 'gform_after_submission', $this->http_track, 'render_segment_http_track', 9, 2 );
			}
			if ( $settings['track_woocommerce_fieldset']['track_woocommerce'] === 'yes' ) {
				$this->loader->add_action( 'woocommerce_before_single_product', $this->http_track, 'render_segment_ecommerce_http_track', 9 );
				$this->loader->add_action( 'woocommerce_add_to_cart', $this->http_track, 'render_segment_ecommerce_http_track', 5, 6 );
				$this->loader->add_action( 'woocommerce_remove_cart_item', $this->http_track, 'render_segment_ecommerce_http_track', 9, 2 );
				$this->loader->add_action( 'woocommerce_cart_item_restored', $this->http_track, 'render_segment_ecommerce_http_track', 5, 2 );
				$this->loader->add_action( 'woocommerce_before_cart', $this->http_track, 'render_segment_ecommerce_http_track', 9 );
				$this->loader->add_action( 'woocommerce_before_checkout_form', $this->http_track, 'render_segment_ecommerce_http_track', 9 );
				$this->loader->add_action( 'woocommerce_order_status_pending', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_order_status_failed', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_order_status_on-hold', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_order_status_processing', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_order_status_completed', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_payment_complete', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_order_status_refunded', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_order_status_cancelled', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
				$this->loader->add_action( 'woocommerce_applied_coupon', $this->http_track, 'render_segment_ecommerce_http_track', 9, 1 );
			}
		}
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_login_hooks() {

		$this->login = new Segment_For_Wordpress_Login( $this->get_segment_for_wordpress(), $this->get_version(), $this->main );
		$this->loader->add_action( 'login_enqueue_scripts', $this->login, 'enqueue_scripts', 9 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_segment_for_wordpress() {
		return $this->segment_for_wordpress;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Segment_For_Wordpress_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}

class Segment_Analytics {
	/**
	 *
	 * Singleton instance of Segment_Analytics.http_api
	 *
	 * @access private
	 * @var Segment_Analytics
	 */
	private static $instance;

	/**
	 * Retrieves the one true instance of Segment_Analytics
	 *
	 * @return object Singleton instance of Segment_Analytics
	 * @since  1.0.0
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Segment_Analytics;
		}

		return self::$instance;
	}
}

class Segment_Analytics_WordPress {

	/**
	 * Singleton instance of Segment_Analytics_WordPress.
	 *
	 * @access private
	 * @var Segment_Analytics_WordPress
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Singleton instance of Segment_Analytics
	 *
	 * @access private
	 * @var Segment_Analytics
	 * @since 1.0.0
	 */
	private $analytics;

	/**
	 * Retrieves the one true instance of Segment_Analytics_WordPress
	 *
	 * @return object Singleton instance of Segment_Analytics_WordPress
	 * @since  1.0.0
	 */

	public static function get_instance() {

		$settings = get_exopite_sof_option( 'segment-for-wordpress' );

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Segment_Analytics_WordPress ) ) {
			self::$instance = new Segment_Analytics_WordPress;
			self::$instance->load_textdomain();
			self::$instance->admin_hooks();
			self::$instance->frontend_hooks();
			self::$instance->analytics = Segment_Analytics::get_instance();
			self::$instance->include_files();
			if ( $settings['track_woocommerce_fieldset']['track_woocommerce'] === 'yes' ) {
				self::$instance->ecommerce_hooks();
			}
		}

		return self::$instance;
	}

	/**
	 * Returns Settings option name.
	 *
	 * @return string Settings option name
	 * @since  1.0.0
	 *
	 */
	public function get_option_name() {
		return $this->option;
	}

	public function plugin_action_links( $links, $file ) {
		return $links;
	}

	/**
	 * Hooks into actions and filters that affect the administration areas.
	 *
	 * @since  1.0.0
	 */
	public function admin_hooks() {
		//add_action( 'admin_menu'         , array( self::$instance, 'admin_menu' ) );
		add_filter( 'plugin_action_links', array( self::$instance, 'plugin_action_links' ), 10, 2 );
		//add_filter( 'plugin_row_meta'    , array( self::$instance, 'plugin_row_meta' )    , 10, 2 );
		//add_action( 'admin_init'         , array( self::$instance, 'register_settings' ) );
	}

	/**
	 * Includes extra files
	 *
	 * @uses  do_action() Allows other plugins to hook in before or after everything is bootstrapped.
	 *
	 * @since  1.0.0
	 */
	public function include_files() {

		do_action( 'segment_pre_include_files', self::$instance );
		do_action( 'segment_include_files', self::$instance );
	}

	/**
	 * Hooks into actions and filters that affect the front-end.
	 * That is to say, this is where the magic happens.
	 *
	 * @since  1.0.0
	 */

	public function frontend_hooks() {

		//	add_action( 'wp_head' , array( self::$instance, 'wp_head' )       , 9    );
		//	add_action( 'admin_head' , array( self::$instance, 'wp_head' )       , 9    );
			//add_action( 'login_head' , array( self::$instance, 'wp_head' )       , 9    );
		add_action( 'wp_footer', array( self::$instance, 'wp_footer' ), 9 );
		add_action( 'login_footer', array( self::$instance, 'wp_footer' ), 9 );
		add_action( 'admin_footer', array( self::$instance, 'wp_footer' ), 9 );
		add_action( 'wp_insert_comment', array( self::$instance, 'left_comment' ), 9, 2 );
		//add_action( 'wp_login', array( self::$instance, 'login_event' ), 9, 2 );
		add_action( 'ninja_forms_after_submission', array( self::$instance, 'completed_form' ), 9, 1 );
		add_action( 'gform_after_submission', array( self::$instance, 'completed_form' ), 9, 2 );
		add_action( 'user_register', array( self::$instance, 'signed_up' ), 9, 1 );

	}

	/**
	 * Hooks into actions and filters to do with ecommerce actions
	 *
	 */
	public function ecommerce_hooks() {
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );

		if ( $settings['track_woocommerce_fieldset']['track_woocommerce'] === 'yes' ) {
			add_action( 'woocommerce_before_single_product', array( self::$instance, 'viewed_product' ), 9 );
			add_action( 'woocommerce_add_to_cart', array( self::$instance, 'product_added' ), 9, 6 );
			add_action( 'woocommerce_remove_cart_item', array( self::$instance, 'product_removed' ), 9, 2 );
			add_action( 'woocommerce_cart_item_restored', array( self::$instance, 'product_readded' ), 5, 2 );
			add_action( 'woocommerce_before_cart', array( self::$instance, 'viewed_cart' ), 5 );
			add_action( 'woocommerce_before_checkout_form', array( self::$instance, 'initiated_checkout' ), 5 );
			add_action( 'woocommerce_order_status_pending', array( self::$instance, 'order_pending' ), 5, 1 );
			add_action( 'woocommerce_order_status_processing', array( self::$instance, 'order_processing' ), 5, 1 );
			add_action( 'woocommerce_order_status_completed', array( self::$instance, 'order_completed' ), 9, 1 );
			add_action( 'woocommerce_payment_complete', array( self::$instance, 'order_paid' ), 9, 1 );
			add_action( 'woocommerce_order_status_cancelled', array( self::$instance, 'order_cancelled' ), 9, 1 );
			add_action( 'woocommerce_applied_coupon', array( self::$instance, 'coupon_added' ), 9, 1 );
		}
	}

	/**
	 * Empty constructor, as we prefer to get_instance().
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {
	}

	/**
	 * Loads the properly localized PO/MO files
	 *
	 * @since  1.0.0
	 */
	public function load_textdomain() {
		// Set filter for plugin's languages directory
		$segment_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$segment_lang_dir = apply_filters( 'segment_languages_directory', $segment_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'segment' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'segment', $locale );

		// Setup paths to current locale file
		$mofile_local  = $segment_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/segment/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/segment folder
			load_textdomain( 'segment', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/analytics-wordpress/languages/ folder
			load_textdomain( 'segment', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'segment', false, $segment_lang_dir );
		}
	}

	/**
	 *
	 * Retrieves settings array.
	 *
	 * @return array Array of settings.
	 */
	public static function get_settings() {
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );

		return $settings;
	}

	/**
	 *
	 * Returns the name of the current server-side event
	 *
	 * @return event name
	 */

	public static function get_event_name( $action ) {

		$settings = get_exopite_sof_option( 'segment-for-wordpress' );

		$event_name_array = array(
			"wp_login"                            => "Logged in",
			"wp_insert_comment"                   => "Commented",
			"user_register"                       => "Signed up",
			"ninja_forms_after_submission"        => "Completed Form",
			"gform_after_submission"              => "Completed Form",
			"woocommerce_before_single_product"   => "Product Viewed",
			"woocommerce_add_to_cart"             => "Product Added",
			"woocommerce_remove_cart_item"        => "Product Removed",
			"woocommerce_cart_item_restored"      => "Product Added",
			"woocommerce_before_cart"             => "Cart Viewed",
			"woocommerce_before_checkout_form"    => "Checkout Started",
			"woocommerce_order_status_completed"  => "Order Completed",
			"woocommerce_payment_complete"        => "Order Paid",
			"woocommerce_order_status_pending"    => "Order Pending",
			"woocommerce_order_status_failed"     => "Order Failed",
			"woocommerce_order_status_on-hold"    => "Order On-Hold",
			"woocommerce_order_status_processing" => "Order Processing",
			"woocommerce_order_status_refunded"   => "Order Refunded",
			"woocommerce_order_status_cancelled"  => "Order Cancelled",
			"woocommerce_applied_coupon"          => "Coupon Applied",
			//"wp_head" => "debug",
		);


		$event_name = $event_name_array[ $action ];

		if ( $event_name == "Logged in" && $settings['track_logins_fieldset']['track_logins_custom_event_label'] !== "" ) {
			$event_name = $settings['track_logins_fieldset']['track_logins_custom_event_label'];
		}
		if ( $event_name == "Signed up" && $settings['track_signups_fieldset']['track_signups_custom_event_label'] !== "" ) {
			$event_name = $settings['track_signups_fieldset']['track_signups_custom_event_label'];
		}
		if ( $event_name == "Commented" && $settings['track_comments_fieldset']['track_comments_custom_event_label'] !== "" ) {
			$event_name = $settings['track_comments_fieldset']['track_comments_custom_event_label'];
		}
		if ( $event_name == "Completed Form" ) {
			$event_name = "Completed Form";
		}
		if ( $event_name == "Product Viewed" ) {
			$event_name = "Product Viewed";
		}
		if ( $event_name == "Product Added" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"];
		}

		if ( $event_name == "Product Removed" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_removed"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_removed"];
		}

		if ( $event_name == "Cart Viewed" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_cart_viewed"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_cart_viewed"];
		}
		if ( $event_name == "Checkout Started" ) {
			$event_name = "Checkout Started";
		}

		if ( $event_name == "Order Completed" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"];
		}

		if ( $event_name == "Order Paid" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_paid"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_paid"];
		}

		if ( $event_name == "Order Pending" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_pending"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_pending"];
		}

		if ( $event_name == "Order Failed" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_failed"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_failed"];
		}

		if ( $event_name == "Order Processing" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_processing"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_processing"];
		}

		if ( $event_name == "Order Refunded" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_refunded"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_refunded"];
		}

		if ( $event_name == "Order Cancelled" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_cancelled"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_cancelled"];
		}

		if ( $event_name == "Order On-Hold" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_on_hold"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_on_hold"];
		}
		if ( $event_name == "Coupon Applied" && $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_coupon_applied"] !== "" ) {
			$event_name = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_coupon_applied"];
		}

		if ( $event_name == "debug" ) {
			//$event_name == "debug";
		}

		return sanitize_text_field( $event_name );
	}

	/**
	 * Returns the user id for server side events
	 *
	 */

	public static function get_event_user_id( $event_name, $args ) {
		$action   = current_action();
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );
		if ( $event_name == "Logged in" || $event_name === $settings['track_logins_fieldset']['track_logins_custom_event_label'] ) { //args[1]=$login, args[2]=$user
			$user_id = $args[1]->ID;

			return $user_id;
		}
		if ( $event_name == "Signed up" || $event_name === $settings['track_signups_fieldset']['track_signups_custom_event_label'] ) { //args[0]=$user_id	
			$user_id = $args[0];

			return $user_id;
		}
		if ( $event_name == "Commented" || $event_name === $settings['track_comments_fieldset']['track_comments_custom_event_label'] ) { //args[0]=$comment_id, args[1]=$comment
			$user_email = get_comment_author_email( $args[0] );
			$user       = get_user_by( 'email', $user_email );
			$user_id    = $user->ID;

			return $user_id;
		}

		if ( in_array( $event_name, array(
				'Order Completed',
				'Order Pending',
				'Order Failed',
				'Order On-Hold',
				'Order Processing',
				'Order Paid',
				'Order Refunded',
				'Order Cancelled'
			) ) ||
		     in_array( $event_name, array(
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_pending"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_failed"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_on_hold"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_processing"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_paid"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_refunded"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_cancelled"],
			     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"]
		     ) ) ) {
			$order_id    = $args[0];
			$order = new WC_Order( $order_id );
			$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
			$track['userId'] = $user_id;
			$order_email = $order->get_billing_email();
			if ( isset($track['userId']) && $settings['woocommerce_customer_id_as_user_id'] === 'yes' ) {
				// perform guest user actions for woocommerce
				$random_password = wp_generate_password();
				$user_id         = wp_create_user( $order_email, $random_password, $order_email );
			}
			wc_update_new_customer_past_orders( $user_id ); //attaches any previous orders


			return $user_id;
		}
	}

	/**
	 *
	 * Returns the properties for an event
	 *
	 * @return array encoded array of event properties
	 */

	public static function get_event_properties( $event_name, $user_id, $args ) {
		$settings   = Segment_Analytics_WordPress::get_settings();
		$properties = array();
		$user       = get_user_by( 'ID', $user_id );

		if ( $settings["include_user_ids"] === 'yes' ) { // based on user settings	
			$properties["userId"] = sanitize_text_field( $user_id );
			$properties["email"]  = sanitize_email( $user->user_email );
		}

		if ( $event_name == "Logged in" || $event_name === $settings['track_logins_fieldset']['track_logins_custom_event_label'] ) { //arg1=$login, arg2=$user
			//no custom properties 
		}

		if ( $event_name == "Signed up" || $event_name === $settings['track_signups_fieldset']['track_signups_custom_event_label'] ) { //arg1=$user_id
			$properties["createdAt"] = gmdate( "Y-m-d\TH:i:s\Z" ); //timestamp
		}

		if ( $event_name == "Commented" || $event_name === $settings['track_comments_fieldset']['track_comments_custom_event_label'] ) { //arg1=$comment_id, arg2=$comment
			$properties["commentId"] = $args[0];
		}

		if ( $event_name == "Completed Form" ) { //arg1=$form_data object	
			//FOR NINJA FORMS
			$action = current_action();
			if ( $settings['track_ninja_forms_fieldset']['track_ninja_forms'] === 'yes' && $action === 'ninja_forms_after_submission' ) {
				//extract values from each active field type and include key => value pairs in the track call		
				$fields = $args[0]["fields"];
				$keys   = array_keys( $fields );
				foreach ( $keys as $key ) {
					if ( $fields[ $key ]["type"] !== "" && $fields[ $key ]["value"] !== "" && $fields[ $key ]["value"] !== null ) {
						//if not emptty or NULL
						$field_type  = $fields[ $key ]["type"];
						$field_value = $fields[ $key ]["value"];

						if ( $field_type === "firstname" ) {
							$field_type = "firstName";
						}

						if ( $field_type === "lastname" ) {
							$field_type = "lastName";
						}

						if ( $field_type === "email" ) {
							$field_type = "email";
						}

						$properties[ $field_type ] = sanitize_text_field( $field_value );
					}
				}
				//remember to add form title here	
				$properties = array_merge_recursive( $properties );
			}

			//FOR GRAVITY FORMS
			if ( $settings['track_gravity_forms_fieldset']['track_gravity_forms'] === 'yes' && $action === 'gform_after_submission' ) { // arg1=$entry $arg2= $form 
				$entry_id                 = $args[0]['id'];
				$form_id                  = $args[0];
				$form_id                  = $form_id['form_id'];
				$form                     = GFAPI::get_form( $form_id ); //since it's not passed as Obj correctly in $args				
				$entry                    = GFAPI::get_entry( $entry_id ); //since it's not passed as Obj correctly in $args 
				$properties["form_id"]    = $form_id;
				$properties['source_url'] = $entry['source_url'];
				$fields                   = $form['fields'];
				foreach ( $fields as $field ) {
					$field_type = $field['type'];
					$field_id   = $field['id'];
					$inputs     = rgar( $entry, $field_id );
					foreach ( $inputs as $input ) {
						$value = rgar( $entry, $field_id );
						if ( $input['id'] === '2.3' ) { //these ids arbritary set by GF https://docs.gravityforms.com/name/
							$input['label'] = 'First Name';
						}
						if ( $input['id'] === '2.4' ) {
							$input['label'] = 'Middle Name';
						}
						if ( $input['id'] === '2.6' ) {
							$input['label'] = 'Last Name';
						}
						if ( $input['id'] === '2.2' ) {
							$input['label'] = 'Title';
						}
						if ( $input['type'] === 'email' ) {
							$input['label'] = 'email';
						}
					}

					if ( $field['type'] === 'email' ) {
						$field['label'] = 'email';
					}

					if ( $field['type'] === 'phone' ) {
						$field['label'] = 'phone';
					}

					$properties[ $field['label'] ] = rgar( $entry, $field['id'] );
				}
			}
		}

		$properties = array_filter( $properties );
		$properties = json_encode( array( $properties ) );

		return $properties;
	}

	/**
	 *
	 * Returns the properties for an ecommerce event
	 *
	 * @return json encoded array of ecommerce event properties
	 */

	public static function get_ecommerce_event_properties( $event_name, $user_id, $args ) {
		$settings   = Segment_Analytics_WordPress::get_settings();
		$properties = array();
		$user       = get_user_by( 'ID', $user_id );
		if ( $settings["include_user_ids"] === 'yes' ) { // based on user settings	
			$properties["userId"] = sanitize_text_field( $user_id );
			$properties["email"]  = sanitize_email( $user->user_email );
		}

		if ( $event_name == 'Product Viewed' ) {
			$product_id = get_the_ID();
			$image_url  = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
			$_product   = wc_get_product( $product_id );
			$_product->get_meta_data();
			$properties['product_id'] = $_product->id;
			$properties['sku']        = $_product->sku;
			$properties['category']   = $_product->category_ids; //array
			$properties['name']       = $_product->name;
			$properties['price']      = $_product->price;
			$properties['url']        = get_permalink( $product_id );
			$properties['image_url']  = $image_url[0];
			//brand
			//coupon
			//position
		}

		if ( $event_name == 'Product Added' || $event_name == $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"] ) {
			$action = current_action();
			if ( $action === 'woocommerce_add_to_cart' ) {
				$cart_id        = $args[0];
				$product_id     = $args[1];
				$quantity       = $args[2];
				$variation_id   = $args[3];
				$variation      = $args[4];
				$cart_item_data = $args[5];
				//$arg1=$cart_item_key,$arg2=$product_id, $arg3=$quantity, $arg4=$variation_id,$arg5=$variation, $arg6=$cart_item_data
				$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
				$_product  = wc_get_product( $product_id );
				$_product->get_meta_data();
				$properties['product_id']   = $_product->id;
				$properties['sku']          = $_product->sku;
				$properties['category']     = $_product->category_ids; //array
				$properties['name']         = $_product->name;
				$properties['price']        = $_product->price;
				$properties['quantity']     = $quantity;
				$properties['cart_id']      = $cart_id;
				$properties['variation_id'] = $variation_id;
				$properties['variation']    = $variation;
				$properties['url']          = get_permalink( $product_id );
				$properties['image_url']    = $image_url[0];
			}
			if ( $action === 'woocommerce_cart_item_restored' ) {
				$removed_cart_item_key      = $args[0];
				$cart                       = $args[1];
				$product_id                 = $cart->cart_contents[ $removed_cart_item_key ]['product_id'];
				$quantity                   = $cart->cart_contents[ $removed_cart_item_key ]['quantity'];
				$variation_id               = $cart->cart_contents[ $removed_cart_item_key ]['variation_id'];
				$variation                  = $cart->cart_contents[ $removed_cart_item_key ]['variation'];
				$properties['product_id']   = $product_id;
				$properties['quantity']     = $quantity;
				$properties['variation_id'] = $variation_id;
				$properties['variation']    = $variation;
			}
		}

		if ( $event_name == 'Product Removed' || $event_name == $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_removed"] ) { // args $removed_cart_item_key, $cart
			$removed_cart_item_key      = $args[0];
			$cart                       = $args[1];
			$product_id                 = $cart->cart_contents[ $removed_cart_item_key ]['product_id'];
			$quantity                   = $cart->cart_contents[ $removed_cart_item_key ]['quantity'];
			$variation_id               = $cart->cart_contents[ $removed_cart_item_key ]['variation_id'];
			$variation                  = $cart->cart_contents[ $removed_cart_item_key ]['variation'];
			$properties['product_id']   = $product_id;
			$properties['quantity']     = $quantity;
			$properties['variation_id'] = $variation_id;
			$properties['variation']    = $variation;
		}

		if ( $event_name == 'Cart Viewed' || $event_name == $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_cart_viewed"] ) {
			//nothing for now
		}

		if ( $event_name == 'Checkout Started' ) {
			//nothing for now
		}

		if ( $event_name == 'Coupon Applied' || $event_name == $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_coupon_applied"] ) {
			$coupon                      = $args[0];
			$coupon_data                 = new WC_Coupon( $coupon );
			$properties["coupon_id"]     = $coupon_data->get_id();
			$properties["coupon_name"]   = $coupon_data->get_code();
			$properties["coupon_type"]   = $coupon_data->get_discount_type();
			$properties["coupon_amount"] = wc_format_decimal( $coupon_data->get_amount(), 2 );
			$properties["discount"]      = wc_format_decimal( $coupon_data->get_amount(), 2 );
		}

		if ( in_array( $event_name, array(
				'Order Completed',
				'Order Pending',
				'Order Failed',
				'Order On-Hold',
				'Order Processing',
				'Order Paid',
				'Order Refunded',
				'Order Cancelled'
			) )
		     || in_array( $event_name, array(
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_pending"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_failed"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_on_hold"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_processing"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_paid"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_refunded"],
				$settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_cancelled"]
			) ) ) { //args is orderid
			$order_id = $args[0];

			$order = new WC_Order ( $order_id );

			//$_order   = wc_get_order( $order_id );
			$total    = $order->get_total();
			//$total = (double) $total;
			$tax = $order->get_total_tax();
			//$tax = (double) $tax;
			$shipping = $order->get_shipping_total();
			//$shipping = (double) $shipping;
			$revenue                   = $total - $shipping;
			$properties['order_id']    = $order_id;
			$properties['customer_id'] = $order->get_customer_id();
			//might work the customer id in as an identify method
			$properties['order_email']          = $order->get_billing_email();

			$properties['status']               = $order->get_status();
			$properties['currency']             = $order->get_currency();
			$properties['total']                = $total;
			$properties['tax']                  = $tax;
			$properties['shipping']             = $shipping;
			$properties['revenue']              = $revenue;
			$properties['value']                = $total;
			$properties['shipping_tax']         = $order->get_shipping_tax();
			$properties['discount']             = $order->get_discount_total();
			$properties['discount_tax']         = $order->get_discount_tax();
			$properties['cart_tax']             = $order->get_cart_tax();
			$properties['payment_method']       = $order->get_payment_method();
			$properties['payment_method_title'] = $order->get_payment_method_title();
			$properties['transaction_id']       = $order->get_transaction_id();
			$properties['created_via']          = $order->get_created_via();ed;
			$properties['date_paid']            = $order->get_date_paid();
			$properties['date_created']         = $order->get_date_created();
			$properties['date_updated']         = $order->get_date_modified();
			//order complete at payment or at order switch
			$items_data = array();
			foreach ( $order->get_items() as $item_id => $item ) {
				// Get an instance of corresponding the WC_Product object
				$product                = $item->get_product();
				$items_data[ $item_id ] = array(
					'product_id'   => $product->get_id(),
					'name'         => $product->get_name(),
					'sku'          => $product->get_sku(),
					'variation_id' => $product->get_variation_id(),
					'price'        => $product->get_price(),
					'quantity'     => $item->get_quantity(),
					'item_total'   => number_format( $item->get_total(), 2 )
				);
			}
			$items_data             = array_values( $items_data );
			$properties['products'] = $items_data;
		}
		$properties = array_filter( $properties );
		$properties = json_encode( $properties );

		return $properties;
	}

	/**
	 *
	 * Check if user is trackable
	 *
	 * @return true if trackable
	 *
	 */

	public static function check_trackable_user( $user ) {
		if ( is_user_logged_in( $user ) ) {
			$user_roles     = ( array ) $user->roles;
			$current_role   = $user_roles[0];
			$settings       = get_exopite_sof_option( 'segment-for-wordpress' );
			$excluded_roles = $settings['ignored_users'];
			if ( $excluded_roles !== null ) {
				if ( ! in_array( $current_role, $excluded_roles ) ) {
					return true;
				} else { //not trackable
					return false;
				}
			}
		} else { //logged out			
			return true;
		}
	}

	/**
	 *
	 * Check if Post is trackable Post Type
	 *
	 * @return true if trackable
	 *
	 */

	public static function check_trackable_post( $current_post ) {
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );

		if ( $settings["track_wp_admin"] === "no" && is_admin() === true ) {
			return false;
		} else {
			$post_type           = get_post_type( $current_post );
			$excluded_post_types = $settings['ignored_post_types'];
			if ( $excluded_post_types !== null ) {
				if ( in_array( $post_type, $excluded_post_types ) ) {
					return false;
				} else {
					return true;
				}
			}
		}
	}

	/**
	 *
	 *    Get current traits
	 * @return array of traits
	 *
	 */
	public static function get_current_user_traits( $user ) {
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );
		if ( $user->ID !== 0 ) {
			$traits           = array();
			$traits['userId'] = $user->ID;
			$selected_traits  = $settings["included_user_traits"];
			$traits_menu      = array(
				'1' => 'firstName',
				'2' => 'lastName',
				'3' => 'email',
				'4' => 'username',
				'5' => 'nickname',
				'6' => 'createdAt',
				'7' => 'website',
				'8' => 'description',
				'9' => 'wordpressId',
			);
			$trait_values     = array(
				'firstName'   => $user->user_firstname,
				'lastName'    => $user->user_lastname,
				'email'       => $user->user_email,
				'username'    => $user->user_login,
				'nickname'    => $user->display_name,
				'createdAt'   => $user->date_registered,
				'website'     => $user->user_url,
				'description' => $user->description,
				'wordpressID' => $user->ID,
			);

			foreach ( $selected_traits as $key ) {
				$value            = $traits_menu[ $key ];
				$traits[ $value ] = sanitize_text_field( $trait_values[ $value ] );
			}
		}

		if ( $traits ) {
			// Clean out empty traits before sending it back.
			$traits = array_filter( $traits );
		}

		return apply_filters( 'filter_user_traits', $traits, $user );
		//you can use this filter to modify identify call
	}

	/**
	 * Outputs analytics.page()/ snippet in head for admin, login page and wp_footer.
	 */
	public function wp_footer() {
		$page = $this->get_current_page();
		if ( $page ) {
			self::$instance->analytics->page( $page['page'], $page['properties'] );
		}
	}

	/**
	 * Uses Segment_Cookie::set_cookie() to notify Segment that a comment has been left.
	 *
	 * @param int $id Comment ID. Unused.
	 * @param object $comment WP_Comment object Unused.
	 *
	 */

	public function left_comment( $comment_id, $comment ) {
		//args[0]=$comment_id, args[1]=$comment
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );
		if ( $settings['track_comments_fieldset']['track_comments'] == "yes" ) {
			$comment_id = Segment_Encrypt::encrypt_decrypt( $comment_id, 'e' );
			Segment_Cookie::set_cookie( 'left_comment', base64_encode( $comment_id ) );
		}
	}

	/**
	 * Uses Segment_Cookie::set_cookie() to notify Segment that a user has logged in.
	 *
	 * @param string $login Username of logged in user.
	 * @param WP_User $user User object of logged in user.
	 *
	 */
	public function login_event( $login, $user ) {
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );
		if ( $settings['track_logins_fieldset']['track_logins'] == "yes" ) {
			$user = base64_encode( json_encode( $user ) );
			$user = Segment_Encrypt::encrypt_decrypt( $user, 'e' );
			Segment_Cookie::set_cookie( 'logged_in', $user );
		}
	}

	/**
	 * Uses Segment_Cookie::set_cookie() to notify Segment that a user has signed up.
	 *     *
	 *
	 * @param int $user_id Username of new user.
	 *
	 */
	public function signed_up( $user_id ) {
		$user = base64_encode( json_encode( $user_id ) );
		$user = Segment_Encrypt::encrypt_decrypt( $user_id, 'e' );
		Segment_Cookie::set_cookie( 'signed_up', $user_id );
	}

	/**
	 * Uses Segment_Cookie::set_cookie() to notify Segment that a user has completed a form.
	 *
	 * @param int $user_id Username of new user.
	 *
	 */

	public function completed_form( ...$args ) {    //GF  args[0]=$entry args[1]= $form and NF args[0]=form_data object	
		$args       = func_get_args();
		$settings   = get_exopite_sof_option( 'segment-for-wordpress' );
		$action     = current_action();
		$properties = array();
		//FOR NINJA FORMS
		// NF args[0]=form_data object	
		if ( $settings['track_ninja_forms_fieldset']['track_ninja_forms'] === 'yes' && $action === 'ninja_forms_after_submission' ) {
			//extract values from each active field type and include key => value pairs in the track call		
			$fields = $args[0]["fields"];
			$keys   = array_keys( $fields );
			foreach ( $keys as $key ) {

				if ( $fields[ $key ]["type"] !== "" && $fields[ $key ]["value"] !== "" && $fields[ $key ]["value"] !== null ) {
					//if not emptty or NULL
					$field_type  = $fields[ $key ]["type"];
					$field_value = $fields[ $key ]["value"];

					if ( $field_type === "firstname" ) {
						$field_type = "firstName";
					}

					if ( $field_type === "lastname" ) {
						$field_type = "lastName";
					}

					if ( $field_type === "email" ) {
						$field_type = "email";
					}

					$properties[ $field_type ] = sanitize_text_field( $field_value );
				}
			}
			//remember to add form title here

			$properties = array_merge_recursive( $properties );
			$properties = array_filter( $properties );
			$properties = base64_encode( json_encode( $properties ) );
			$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
			Segment_Cookie::set_cookie( 'completed_form', $properties );
		}

		//FOR GRAVITY FORMS
		////GF  args[0]=$entry args[1]= $form
		if ( $settings['track_gravity_forms_fieldset']['track_gravity_forms'] === 'yes' && $action === 'gform_after_submission' ) {
			$entry_id                 = $args[0]['id'];
			$form_id                  = $args[0];
			$form_id                  = $form_id['form_id'];
			$form                     = GFAPI::get_form( $form_id ); //since it's not passed as Obj correctly in $args				
			$entry                    = GFAPI::get_entry( $entry_id ); //since it's not passed as Obj correctly in $args 
			$properties["form_id"]    = $form_id;
			$properties['source_url'] = $entry['source_url'];
			$fields                   = $form['fields'];
			foreach ( $fields as $field ) {
				$field_type = $field['type'];
				$field_id   = $field['id'];
				$inputs     = rgar( $entry, $field_id );
				foreach ( $inputs as $input ) {
					$value = rgar( $entry, $field_id );
					if ( $input['id'] === '2.3' ) { //these ids arbritary set by GF https://docs.gravityforms.com/name/
						$input['label'] = 'First Name';
					}
					if ( $input['id'] === '2.4' ) {
						$input['label'] = 'Middle Name';
					}
					if ( $input['id'] === '2.6' ) {
						$input['label'] = 'Last Name';
					}
					if ( $input['id'] === '2.2' ) {
						$input['label'] = 'Title';
					}
					if ( $input['type'] === 'email' ) {
						$input['label'] = 'email';
					}
				}
				if ( $field['type'] === 'email' ) {
					$field['label'] = 'email';
				}
				if ( $field['type'] === 'phone' ) {
					$field['label'] = 'phone';
				}
				$properties[ $field['label'] ] = rgar( $entry, $field['id'] );
			}
			$properties = array_merge_recursive( $properties );
			$properties = array_filter( $properties );
			$properties = base64_encode( json_encode( $properties ) );
			$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
			Segment_Cookie::set_cookie( 'completed_form', $properties );
		}
	}

	/**
	 * ECOMMERCE COOKIES
	 * Uses Segment_Cookie::set_cookie() to notify Segment of user ecommerce events
	 *                 *
	 */

	// viewed product
	public function viewed_product() {
		$current_user   = wp_get_current_user();
		$user_id        = $current_user->ID;
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		if ( $trackable_user === false ) {
			//not trackable
			return;
		}
		$current_post        = get_post();
		$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

		if ( $trackable_post_type === false ) {
			//not trackable
			return;
		}
		$action     = current_action();
		$event_name = Segment_Analytics_WordPress::get_event_name( $action );
		$args       = array(); //the next function needs $args so passing empty array
		$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
		$properties = base64_encode( json_encode( $properties ) );
		$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
		Segment_Cookie::set_cookie( 'viewed_product', $properties );
	}

	// added product
	public function product_added( ...$args ) {
		//$args[0]=$cart_item_key,$args[1]=$product_id,$args[2]=$quantity, $args[3]=$variation_id,$args[4]=$variation, $args[5]=$cart_item_data
		$args           = func_get_args();
		$current_user   = wp_get_current_user();
		$user_id        = $current_user->ID;
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		if ( $trackable_user === false ) {
			//not trackable
			return;
		}
		$current_post        = get_post();
		$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );
		if ( $trackable_post_type === false ) {
			//not trackable
			return;
		}
		$cart_id        = $args[0];
		$product_id     = $args[1];
		$quantity       = $args[2];
		$variation_id   = $args[3];
		$variation      = $args[4];
		$cart_item_data = $args[5];
		$image_url      = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
		$_product       = wc_get_product( $product_id );
		$_product->get_meta_data();
		$properties['product_id']   = $_product->id;
		$properties['sku']          = $_product->sku;
		$properties['category']     = $_product->category_ids; //array
		$properties['name']         = $_product->name;
		$properties['price']        = $_product->price;
		$properties['quantity']     = $quantity;
		$properties['cart_id']      = $cart_id;
		$properties['variation_id'] = $variation_id;
		$properties['variation']    = $variation;
		$properties['url']          = get_permalink( $product_id );
		$properties['image_url']    = $image_url[0];
		$properties                 = base64_encode( json_encode( $properties ) );
		$properties                 = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
		Segment_Cookie::set_cookie( 'product_added', $properties );
	}

	// removed product
	public function product_removed( ...$args ) {
		// args $removed_cart_item_key, $cart
		$args           = func_get_args();
		$current_user   = wp_get_current_user();
		$user_id        = $current_user->ID;
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		if ( $trackable_user === false ) {
			//not trackable
			return;
		}
		$current_post        = get_post();
		$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );
		if ( $trackable_post_type === false ) {
			//not trackable
			return;
		}
		$action     = current_action();
		$event_name = Segment_Analytics_WordPress::get_event_name( $action );
		$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
		$properties = base64_encode( json_encode( $properties ) );
		$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
		Segment_Cookie::set_cookie( 'product_removed', $properties );
	}

	// restored product	
	public function product_readded( ...$args ) {
		// args $removed_cart_item_key, $cart
		$args           = func_get_args();
		$current_user   = wp_get_current_user();
		$user_id        = $current_user->ID;
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		if ( $trackable_user === false ) {
			//not trackable
			return;
		}
		$current_post        = get_post();
		$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );
		if ( $trackable_post_type === false ) {
			//not trackable
			return;
		}
		$removed_cart_item_key      = $args[0];
		$cart                       = $args[1];
		$product_id                 = $cart->cart_contents[ $removed_cart_item_key ]['product_id'];
		$quantity                   = $cart->cart_contents[ $removed_cart_item_key ]['quantity'];
		$variation_id               = $cart->cart_contents[ $removed_cart_item_key ]['variation_id'];
		$variation                  = $cart->cart_contents[ $removed_cart_item_key ]['variation'];
		$properties['product_id']   = $product_id;
		$properties['quantity']     = $quantity;
		$properties['variation_id'] = $variation_id;
		$properties['variation']    = $variation;
		$properties                 = base64_encode( json_encode( $properties ) );
		$properties                 = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
		Segment_Cookie::set_cookie( 'product_readded', $properties );
	}

	// viewed cart
	public function viewed_cart() {
		$current_user   = wp_get_current_user();
		$user_id        = $current_user->ID;
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		if ( $trackable_user === false ) {
			//not trackable
			return;
		}
		$current_post        = get_post();
		$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

		if ( $trackable_post_type === false ) {
			//not trackable
			return;
		}
		$action     = current_action();
		$event_name = Segment_Analytics_WordPress::get_event_name( $action );
		$args       = array(); //the next function needs $args so passing empty array
		$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
		Segment_Cookie::set_cookie( 'viewed_cart', base64_encode( json_encode( $properties ) ) );
	}

	// started checkout
	public function initiated_checkout() {
		$current_user   = wp_get_current_user();
		$user_id        = $current_user->ID;
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		if ( $trackable_user === false ) {
			//not trackable
			return;
		}
		$current_post        = get_post();
		$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

		if ( $trackable_post_type === false ) {
			//not trackable
			return;
		}
		$action     = current_action();
		$event_name = Segment_Analytics_WordPress::get_event_name( $action );
		$args       = array(); //the next function needs $args so passing empty array
		$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
		Segment_Cookie::set_cookie( 'initiated_checkout', base64_encode( json_encode( $properties ) ) );
	}

	// order pending
	public function order_pending( ...$args ) {
		$args         = func_get_args();
		$current_user = wp_get_current_user();
		$user_roles   = ( array ) $current_user->roles;
		if ( ! in_array( 'administrator', $user_roles ) === false && ! in_array( 'shop_manager', $user_roles ) === false ) {
			$user_id        = $current_user->ID;
			$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			if ( $trackable_user === false ) {
				//not trackable
				return;
			}
			$current_post        = get_post();
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

			if ( $trackable_post_type === false ) {
				//not trackable
				return;
			}
			$action     = current_action();
			$event_name = Segment_Analytics_WordPress::get_event_name( $action );
			if ( $current_user->ID === 0 || $current_user->ID === null ) {
				$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
				if ( $event_user_id === 0 && $event_user_id === null ) {
					return; // if there is no uid then there's no server side event
				} else {
					$user_id = $event_user_id;
				}
				$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$properties = base64_encode( json_encode( $properties ) );
				$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
				Segment_Cookie::set_cookie( 'order_pending', $properties );
			}
		}
	}

	// order processing
	public function order_processing( ...$args ) {
		$args         = func_get_args();
		$current_user = wp_get_current_user();
		$user_roles   = ( array ) $current_user->roles;

		if ( in_array( 'administrator', $user_roles ) === false && in_array( 'shop_manager', $user_roles ) === false ) {
			$user_id        = $current_user->ID;
			$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			if ( $trackable_user === false ) {
				//not trackable
				return;
			}
			$current_post        = get_post();
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

			if ( $trackable_post_type === false ) {
				//not trackable
				return;
			}
			$action     = current_action();
			$event_name = Segment_Analytics_WordPress::get_event_name( $action );
			if ( $current_user->ID === 0 || $current_user->ID === null ) {
				$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
				if ( $event_user_id === 0 && $event_user_id === null ) {
					return; // if there is no uid then there's no server side event
				} else {
					$user_id = $event_user_id;
				}

				$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$properties = base64_encode( json_encode( $properties ) );
				$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
				Segment_Cookie::set_cookie( 'order_processing', $properties );
			}
		}
	}

	// order completed
	public function order_completed( ...$args ) {
		$args         = func_get_args();
		$current_user = wp_get_current_user();
		$user_roles   = ( array ) $current_user->roles;
		if ( in_array( 'administrator', $user_roles ) === false && in_array( 'shop_manager', $user_roles ) === false ) {
			$user_id        = $current_user->ID;
			$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			if ( $trackable_user === false ) {
				//not trackable
				return;
			}
			$current_post        = get_post();
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

			if ( $trackable_post_type === false ) {
				//not trackable
				return;
			}
			$action     = current_action();
			$event_name = Segment_Analytics_WordPress::get_event_name( $action );
			if ( $current_user->ID === 0 || $current_user->ID === null ) {
				$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
				if ( $event_user_id === 0 && $event_user_id === null ) {
					return; // if there is no uid then there's no server side event
				} else {
					$user_id = $event_user_id;
				}
				$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$properties = base64_encode( json_encode( $properties ) );
				$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
				Segment_Cookie::set_cookie( 'order_completed', $properties );
			}
		}
	}

	// order paid
	public function order_paid( ...$args ) {
		$args         = func_get_args();
		$current_user = wp_get_current_user();
		$user_roles   = ( array ) $current_user->roles;
		if ( in_array( 'administrator', $user_roles ) === false && in_array( 'shop_manager', $user_roles ) === false ) {
			$user_id        = $current_user->ID;
			$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			if ( $trackable_user === false ) {
				//not trackable
				return;
			}
			$current_post        = get_post();
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );
			if ( $trackable_post_type === false ) {
				//not trackable
				return;
			}
			$action     = current_action();
			$event_name = Segment_Analytics_WordPress::get_event_name( $action );
			if ( $current_user->ID === 0 || $current_user->ID === null ) {
				$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
				if ( $event_user_id === 0 && $event_user_id === null ) {
					return; // if there is no uid then there's no server side event
				} else {
					$user_id = $event_user_id;
				}
				$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$properties = base64_encode( json_encode( $properties ) );
				$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
				Segment_Cookie::set_cookie( 'order_paid', $properties );
			}
		}
	}

	// order cancelled
	public function order_cancelled( ...$args ) {
		$args         = func_get_args();
		$current_user = wp_get_current_user();
		$user_roles   = ( array ) $current_user->roles;
		if ( in_array( 'administrator', $user_roles ) === false && in_array( 'shop_manager', $user_roles ) === false ) {
			$user_id        = $current_user->ID;
			$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			if ( $trackable_user === false ) {
				//not trackable
				return;
			}
			$current_post        = get_post();
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

			if ( $trackable_post_type === false ) {
				//not trackable
				return;
			}
			$action     = current_action();
			$event_name = Segment_Analytics_WordPress::get_event_name( $action );
			if ( $current_user->ID === 0 || $current_user->ID === null ) {
				$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
				if ( $event_user_id === 0 && $event_user_id === null ) {
					return; // if there is no uid then there's no server side event
				} else {
					$user_id = $event_user_id;
				}
				$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$properties = base64_encode( json_encode( $properties ) );
				$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
				Segment_Cookie::set_cookie( 'order_cancelled', $properties );
			}
		}
	}

	// coupon added
	public function coupon_added( ...$args ) {
		$args         = func_get_args();
		$current_user = wp_get_current_user();
		$user_roles   = ( array ) $current_user->roles;
		if ( in_array( 'administrator', $user_roles ) === false && in_array( 'shop_manager', $user_roles ) === false ) {
			$user_id        = $current_user->ID;
			$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			if ( $trackable_user === false ) {
				//not trackable
				return;
			}
			$current_post        = get_post();
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );

			if ( $trackable_post_type === false ) {
				//not trackable
				return;
			}
			$action     = current_action();
			$event_name = Segment_Analytics_WordPress::get_event_name( $action );
			if ( $current_user->ID === 0 || $current_user->ID === null ) {
				$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
				if ( $event_user_id === 0 && $event_user_id === null ) {
					return; // if there is no uid then there's no server side event
				} else {
					$user_id = $event_user_id;
				}
				$properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$properties = base64_encode( json_encode( $properties ) );
				$properties = Segment_Encrypt::encrypt_decrypt( $properties, 'e' );
				Segment_Cookie::set_cookie( 'coupon_added', $properties );
			}
		}
	}

	/**
	 * Used to track the current event.  Used for analytics.track().
	 *
	 * @return array Array containing the page being tracked along with any additional properties.
	 */

	static function get_current_page_track() {
		$settings = get_exopite_sof_option( 'segment-for-wordpress' );
		$user     = wp_get_current_user();
		$post     = get_post();
		$track    = array();
		$i        = 0;

		// Posts 
		// -----
		if ( $settings['track_posts_fieldset']['track_posts'] == "yes" ) {
			// A post or a custom post. `is_single` also returns attachments, so
			// we filter those out. The event name is based on the post's type, and is uppercased.
			if ( is_single() && ! is_attachment() ) {
				$track[ $i ]          = array();
				$properties           = array();
				$track[ $i ]['event'] = sprintf( __( 'Viewed %s', 'segment' ), ucfirst( get_post_type() ) );
				if ( $settings['track_posts_fieldset']['track_posts_custom_event_label'] !== "" ) {
					$track[ $i ]['event'] = $settings['track_posts_fieldset']['track_posts_custom_event_label'];
				}
				$track[ $i ]['http_event'] = 'left_comment';
				$properties['title']       = single_post_title( '', false );
				$properties['category']    = implode( ', ', wp_list_pluck( get_the_category( get_the_ID() ), 'name' ) );
				$track[ $i ]['properties'] = $properties;
				$i ++;
			}
		}

		// Signups
		// --------		
		if ( Segment_Cookie::get_cookie( 'signed_up' ) ) {
			$user_id               = Segment_Cookie::get_cookie( 'signed_up' );
			$user_id               = Segment_Encrypt::encrypt_decrypt( $user_id, 'd' );
			$user_id               = base64_decode( $user_id );
			$user_id               = json_decode( $user_id );
			$user                  = get_user_by( 'id', $user_id );
			$track[ $i ]           = array(
				'event'      => 'Signed up',
				'properties' => array(
					'username'  => $user->user_login,
					'email'     => $user->user_email,
					'name'      => $user->display_name,
					'firstName' => $user->user_firstname,
					'lastName'  => $user->user_lastname,
					'url'       => $user->user_url
				),
				'http_event' => 'signed_up'
			);
			$track[ $i ]['userId'] = $user->ID;
			if ( $settings['track_signups_fieldset']['track_signups_custom_event_label'] !== "" ) {
				$track[ $i ]['event'] = $settings['track_signups_fieldset']['track_signups_custom_event_label'];
			}
			if ( $track[ $i ] ) {
				$track[ $i ]['properties']['noninteraction'] = true;
				$track[ $i ]['properties']                   = array_filter( $track[ $i ]['properties'] );
			}
			$i ++;
		}

		// Login Event $login, $user
		// --------
		if ( $settings['track_logins_fieldset']['track_logins'] == "yes" ) {
			if ( Segment_Cookie::get_cookie( 'logged_in' ) ) {
				$user        = Segment_Cookie::get_cookie( 'logged_in' );
				$user        = Segment_Encrypt::encrypt_decrypt( $user, 'd' );
				$user        = base64_decode( $user );
				$user        = json_decode( $user );
				$track[ $i ] = array(
					'event'      => 'Logged in',
					'properties' => array(),
					'http_event' => 'logged_in'
				);
				if ( $settings['track_logins_fieldset']['track_logins_custom_event_label'] !== "" ) {
					$track[ $i ]['event'] = $settings['track_logins_fieldset']['track_logins_custom_event_label'];
				}

				if ( $track[ $i ] ) {
					$track[ $i ]['properties']['noninteraction'] = true;
					$track[ $i ]['properties']                   = array_filter( $track['properties'] );
				}

				$i ++;
			}
		}

		// Comments
		// --------
		if ( $settings['track_comments_fieldset']['track_comments'] == "yes" ) {
			if ( Segment_Cookie::get_cookie( 'left_comment' ) ) {
				$comment_id               = Segment_Cookie::get_cookie( 'left_comment' );
				$comment_id               = base64_decode( $comment_id );
				$comment_id               = Segment_Encrypt::encrypt_decrypt( $comment_id, 'd' );
				$properties               = array();
				$properties["comment_id"] = $comment_id;
				$track[ $i ]              = array(
					'event'      => 'Commented',
					'properties' => $properties,
					'http_event' => 'left_comment'
				);

				if ( $settings['track_comments_fieldset']['track_comments_custom_event_label'] !== "" ) {
					$track[ $i ]['event'] = $settings['track_comments_fieldset']['track_comments_custom_event_label'];
				}

				if ( $settings['include_user_ids'] === "yes" ) {
					$user_email                          = get_comment_author_email( $comment_id );
					$user                                = get_user_by( 'email', $user_email );
					$user_id                             = $user->ID;
					$track[ $i ]['properties']['userId'] = $user_id;
					$track[ $i ]['properties']['email']  = $user_email;
				}
			}

			if ( $track[ $i ] ) {
				$track[ $i ]['properties']['noninteraction'] = true;
				$track[ $i ]['properties']                   = array_filter( $track[ $i ]['properties'] );
			}

			$i ++;
		}

		// Pages
		// -----
		if ( $settings['track_pages_fieldset']['track_pages'] == "yes" ) {
			// The front page of their site, whether it's a page or a list of
			// recent blog entries. `is_home` only works if it's not a page,
			if ( is_front_page() ) {
				$track[ $i ] = array(
					'event' => 'Viewed Home Page'
				);
			} // A normal WordPress page.
			else if ( is_page() ) {
				$track[ $i ] = array(
					'event' => sprintf( __( 'Viewed %s Page', 'segment' ), single_post_title( '', false ) ),
				);
			} else if ( did_action( 'login_init' ) ) {
				if ( $settings['track_login_page_fieldset']['track_login_page'] === "yes" ) {
					$track[ $i ] = array( 'event' => 'Viewed Login Page' );
					if ( $settings['track_login_page_fieldset']['track_login_page_custom_event_label'] !== "" ) {
						$track[ $i ]['event'] = $settings['track_login_page_fieldset']['track_login_page_custom_event_label'];
					}
				}
			}
			$i ++;
		}

		// Archives
		// --------
		if ( $settings['track_archives_fieldset']['track_archives'] == "yes" ) {
			// An author archive page.
			if ( is_author() ) {
				$author      = get_queried_object();
				$track[ $i ] = array(
					'event'      => 'Viewed Author Page',
					'properties' => array(
						'author' => $author->display_name
					)
				);
			} // A tag archive page. Use `single_tag_title` to get the name.
			else if ( is_tag() ) {
				$track[ $i ] = array(
					'event'      => 'Viewed Tag Page',
					'properties' => array(
						'	tag' => single_tag_title( '', false )
					)
				);
			} // A category archive page. Use `single_cat_title` to get the name.
			else if ( is_category() ) {
				$track[ $i ] = array(
					'event'      => 'Viewed Category Page',
					'properties' => array(
						'category' => single_cat_title( '', false )
					)
				);
			}
			$i ++;
		}

		///////////
		// Forms
		// --------

		if ( Segment_Cookie::get_cookie( 'completed_form' ) ) {
			$properties  = Segment_Cookie::get_cookie( 'completed_form' );
			$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
			$properties  = base64_decode( $properties );
			$properties  = json_decode( $properties );
			$properties  = json_decode( json_encode( $properties ), true );
			$track[ $i ] = array(
				'event'      => 'Completed Form',
				'properties' => $properties,
				'http_event' => 'completed_form'
			);

			$i ++;
		}

		///////////
		// Ecommerce events
		// --------

		if ( $settings['track_woocommerce_fieldset']['track_woocommerce'] === 'yes' ) {

			// Viewed product

			if ( Segment_Cookie::get_cookie( 'viewed_product' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'viewed_product' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_before_single_product';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$i ++;
			}

			// Product Added

			if ( Segment_Cookie::get_cookie( 'product_added' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'product_added' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( json_encode( $properties ), true );
				$track[ $i ] = array(
					'event'      => 'Product Added',
					'properties' => $properties,
				);

				if ( $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"] !== "" ) {
					$track[ $i ]['event'] = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"];
				}

				$i ++;
			}

			// Product Removed

			if ( Segment_Cookie::get_cookie( 'product_removed' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'product_removed' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_remove_cart_item';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$i ++;
			}

			// Product Restored		
			if ( Segment_Cookie::get_cookie( 'product_readded' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'product_readded' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( json_encode( $properties ), true );
				$track[ $i ] = array(
					'event'      => 'Product Added',
					'properties' => $properties,
				);

				if ( $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"] !== "" ) {
					$track[ $i ]['event'] = $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_events_product_added"];
				}

				$i ++;
			}

			// Viewed Cart

			if ( Segment_Cookie::get_cookie( 'viewed_cart' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'viewed_cart' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_before_cart';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$i ++;
			}

			// Started checkout

			if ( Segment_Cookie::get_cookie( 'initiated_checkout' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'initiated_checkout' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_before_checkout_form';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$i ++;
			}

			// Order Pending

			if ( Segment_Cookie::get_cookie( 'order_pending' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'order_pending' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_order_status_pending';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track[ $i ]['userId'] = $user_id;
				$i ++;
			}

			// Order Processing			
			if ( Segment_Cookie::get_cookie( 'order_processing' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'order_processing' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_order_status_processing';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$order_id    = $properties['order_id'];
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track[ $i ]['userId'] = $user_id;
				$i ++;
			}

			// Order Completed			
			if ( Segment_Cookie::get_cookie( 'order_completed' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'order_completed' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_order_status_completed';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track[ $i ]['userId'] = $user_id;
				$i ++;
			}

			// Order Paid			
			if ( Segment_Cookie::get_cookie( 'order_paid' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'order_paid' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_payment_complete';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track[ $i ]['userId'] = $user_id;
				$i ++;
			}

			// Order Cancelled
			if ( Segment_Cookie::get_cookie( 'order_cancelled' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'order_cancelled' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_order_status_cancelled';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track[ $i ]['userId'] = $user_id;
				$i ++;
			}

			//coupon added		
			if ( Segment_Cookie::get_cookie( 'coupon_added' ) ) {
				$properties  = Segment_Cookie::get_cookie( 'coupon_added' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$action      = 'woocommerce_applied_coupon';
				$event_name  = Segment_Analytics_WordPress::get_event_name( $action );
				$track[ $i ] = array(
					'event'      => $event_name,
					'properties' => $properties,
				);
				$i ++;
			}
		}

		if ( ! isset( $track ) ) {    // We don't have any track calls
			$track = false;
		}

		return $track; // Returns an array of track calls		
	}

	/**
	 * Used to track the current page.  Used for analytics.page().
	 * Unlike get_current_page_track(), we use this primarily as a pub-sub observer for other core events.
	 * This makes it much more manageable for other developers to hook and unhook from it as needed.
	 *
	 * @return array Array containing the page being tracked along with any additional properties.
	 */
	private function get_current_page() {
		$page = apply_filters( 'segment_get_current_page', false, $this->get_settings(), $this );
		if ( $page ) {
			$page['properties']                   = is_array( $page['properties'] ) ? $page['properties'] : array();
			$page['properties']['noninteraction'] = true;
			$page['properties']                   = array_filter( $page['properties'] );
		}

		return $page;
	}

	/**
	 * Helper function, essentially a replica of stripslashes_deep, but for esc_js.
	 *
	 * @param mixed $value Handles arrays, strings and objects that we are trying to escape for JS.
	 *
	 * @return mixed  $value esc_js()'d value.
	 * @since 1.0.0
	 *
	 */
	public static function esc_js_deep( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( array( __CLASS__, 'esc_js_deep' ), $value );
		} elseif ( is_object( $value ) ) {
			$vars = get_object_vars( $value );
			foreach ( $vars as $key => $data ) {
				$value->{$key} = self::esc_js_deep( $data );
			}
		} elseif ( is_string( $value ) ) {
			$value = esc_js( $value );
		}

		return $value;
	}

	/** Get user id from WooCommerce order id */
	public static function get_user_id_from_order( $order_id ) {


		$order = new WC_Order ( $order_id );
		$order_email = $order->get_billing_email();
		if ( email_exists( $order_email ) ) {
			$user_id = email_exists( $order_email );

		} elseif ( username_exists( $order_email ) ) {
			$user    = get_user_by( 'email', $order_email );
			$user_id = $user->ID;
		}

		if ( isset( $user_id ) ) {

			return $user_id;
		}

		return;
	}

	/**
	 * Turns objects to arrays
	 *
	 * @param null $obj
	 *
	 * @return array
	 */
	public static function object_to_array( $obj ) {
		if ( is_object( $obj ) ) {
			$obj = (array) $obj;
		}
		if ( is_array( $obj ) ) {
			$new = array();
			foreach ( $obj as $key => $val ) {
				$new[ $key ] = self::object_to_array( $val );
			}
		} else {
			$new = $obj;
		}

		return $new;
	}

}

register_activation_hook( __FILE__, array( 'Segment_Analytics_WordPress', 'setup_settings' ) );
add_action( 'plugins_loaded', 'Segment_Analytics_WordPress::get_instance' );
