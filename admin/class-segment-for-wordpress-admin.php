<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://in8.io
 * @since      1.0.0
 *
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/admin
 * @author     in8.io <hi@in8.io>
 */
class Segment_For_Wordpress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/segment-for-wordpress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( 'js-cookie', plugin_dir_url( __FILE__ ) . 'js/js.cookie.js', array( 'jquery' ), 1, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/segment-for-wordpress-admin.js', array( 'jquery' ), $this->version, true );
	}

	public function create_menu() {
		/*
		* Create a submenu page under Plugins.
		* Framework also add "Settings" to your plugin in plugins list.
		*/
		$config_submenu = array(
			'type'            => 'menu',
			// Required, menu or metabox
			'id'              => $this->plugin_name . '-test',
			// Required, meta box id, unique per page, to save: get_option( id )
			'parent'          => 'plugins.php',
			// Required, sub page to your options page
			'submenu'         => true,
			// Required for submenu
			'title'           => esc_html__( 'Segment for WordPress', 'plugin-name' ),
			//The name of this page
			'capability'      => 'manage_options',
			// The capability needed to view the page
			'plugin_basename' => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
			// 'tabbed'            => false,
		);
		//
		// - OR --
		// eg: if Yoast SEO is active, then add submenu under Yoast SEO admin menu,
		// if not then under Plugins admin menu:
		//
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		$parent         = 'plugins.php';
		$settings_link  = 'plugins.php?page=plugin-name';
		$config_submenu = array(
			'type'            => 'menu',
			// Required, menu or metabox
			'id'              => $this->plugin_name,
			// Required, meta box id, unique per page, to save: get_option( id )
			'menu'            => $parent,
			// Required, sub page to your options page
			'submenu'         => true,
			// Required for submenu
			'settings-link'   => $settings_link,
			'title'           => esc_html__( 'Segment for WordPress', 'plugin-name' ),
			//The name of this page
			'capability'      => 'manage_options',
			// The capability needed to view the page
			'plugin_basename' => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
			'tabbed'          => true,
		);

		$fields[] = array(
			'name'        => 'API_keys',
			'title'       => 'API Keys',
			'icon'        => 'dashicons-admin-generic',
			'description' => 'Go to Segment,copy your API keys and paste them here',
			'fields'      => array(
				array(
					'id'          => 'js_api_key',
					'type'        => 'text',
					'title'       => 'JS API Write Key',
					'description' => 'Go to Segment,copy your js api and paste it here',
					'attributes'  => array(
						'placeholder' => 'paste your Segment API write key here',
					),
				),
				array(
					'id'          => 'http_api_key',
					'type'        => 'text',
					'title'       => 'HTTP API Write Key',
					'description' => 'Go to Segment,copy your HTTP api and paste it here',
					'attributes'  => array(
						'placeholder' => 'paste your Segment API write key here',
					),
				),
			),
		);

		global $wp_roles;
		$roles         = $wp_roles->get_names();
		$post_types    = get_post_types();
		$current_user  = get_current_user();
		$trait_options = array(
			'First Name',
			'Last Name',
			'Email',
			'Username',
			'Display Name',
			'Signup Date',
			'URL',
			'Bio',
			'ID'
		);
		$args          = array(
			'public'   => true,
			'_builtin' => false
		);
		$post_types    = get_post_types( $args, 'names', 'and' );

		$fields[] = array(
			'name'        => 'Filtering',
			'title'       => 'Filtering',
			'icon'        => 'dashicons-admin-generic',
			'description' => 'Filtering your tracking',
			'fields'      => array(
				array(
					'id'      => 'track_wp_admin',
					'type'    => 'switcher',
					'title'   => 'Track wp-admin area?',
					'default' => 'no',
				),
				array(
					'id'          => 'ignored_users',
					'type'        => 'tap_list',
					'title'       => 'User roles to ignore',
					'description' => 'These users won\'t be tracked',
					'options'     => $roles,
				),
				array(
					'id'          => 'ignored_post_types',
					'type'        => 'tap_list',
					'title'       => 'Custom post types to ignore',
					'description' => 'Custom post types to ignore',
					'options'     => $post_types,
				),
			),
		);
		$fields[] = array(
			'name'   => 'Events',
			'title'  => 'Events',
			'icon'   => 'fa fa-list-alt',
			'fields' => array(
				array(
					'id'          => 'include_user_ids',
					'type'        => 'switcher',
					'title'       => 'Add userId and email as properties to each event.',
					'description' => 'Some email tools require this in order to attribute events to each user properly.',
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'event_identifier_fieldset',
					'title'       => 'Append a label to server side event names',
					'description' => 'A way to tag server side event names. Can be useful when you\'re combining them with client side events in other tools.',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'use_event_identifier',
							'type'    => 'switcher',
							'prepend' => 'Label server side events?',
						),
						array(
							'id'         => 'use_event_identifier_label',
							'type'       => 'text',
							'default'    => ' - Server Side',
							'attributes' => array(
								'placeholder' => ' - Server Side',
							)
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'track_signups_fieldset',
					'title'       => 'Track sign ups',
					'description' => 'Trigger an event when people sign up',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'track_signups',
							'type'    => 'switcher',
							'prepend' => 'Track sign ups?',
						),
						array(
							'id'         => 'track_signups_custom_event_label',
							'type'       => 'text',
							'prepend'    => 'Event Name',
							'attributes' => array(
								'placeholder' => 'Signed up',
							)
						),
					),
				),

				array(
					'type'        => 'fieldset',
					'id'          => 'track_logins_fieldset',
					'title'       => 'Track log ins',
					'description' => 'Triggers an event when people log in.',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'track_logins',
							'type'    => 'switcher',
							'prepend' => 'Track log ins?',
						),
						array(
							'id'         => 'track_logins_custom_event_label',
							'type'       => 'text',
							'prepend'    => 'Event Name',
							'attributes' => array(
								'placeholder' => 'Logged in',
							)
						),
					),
				),

				array(
					'type'        => 'fieldset',
					'id'          => 'track_comments_fieldset',
					'title'       => 'Track comments',
					'description' => 'Trigger an event when people leave a comment',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'track_comments',
							'type'    => 'switcher',
							'prepend' => 'Track comments?',
						),
						array(
							'id'         => 'track_comments_custom_event_label',
							'type'       => 'text',
							'prepend'    => 'Event Name',
							'attributes' => array(
								'placeholder' => 'Commented',
							)
						),
					),
				),

				array(
					'type'        => 'fieldset',
					'id'          => 'track_posts_fieldset',
					'title'       => 'Track posts',
					'description' => 'Trigger an event when people view a post. You do not normally need these because the data is also in the page calls',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'track_posts',
							'type'    => 'switcher',
							'prepend' => 'Track posts?',
						),
						array(
							'id'         => 'track_posts_custom_event_label',
							'type'       => 'text',
							'prepend'    => 'Event Name',
							'attributes' => array(
								'placeholder' => 'Viewed post',
							)
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'track_archives_fieldset',
					'title'       => 'Track archives',
					'description' => 'Trigger an event when people view an archive page, like the categories or tags lists.',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'track_archives',
							'type'    => 'switcher',
							'prepend' => 'Trigger an event when people view archives? ie,  Blog page, category pages, etc..',
						),
						array(
							'id'         => 'track_archives_custom_event_label',
							'type'       => 'text',
							'prepend'    => 'Event Name',
							'attributes' => array(
								'placeholder' => 'Viewed an archive',
							)
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'track_login_page_fieldset',
					'title'       => 'Track "Log in" page',
					'description' => 'Trigger an event when people view an archive page, like the categories or tags lists.',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(
						array(
							'id'      => 'track_login_page',
							'type'    => 'switcher',
							'prepend' => 'Track log ins?',
						),
						array(
							'id'         => 'track_login_page_custom_event_label',
							'type'       => 'text',
							'prepend'    => 'Event Name',
							'attributes' => array(
								'placeholder' => 'Viewed log in page"',
							)
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'track_pages_fieldset',
					'title'       => 'Track pages',
					'description' => 'Trigger an event when people view a post. You do not normally need these because the data is also in the page calls',
					'options'     => array(
						'cols' => 1,
					),
					'fields'      => array(
						array(
							'id'      => 'track_pages',
							'type'    => 'switcher',
							'prepend' => 'Trigger custom event for viewed pages? (Viewed "Page Name")',
						),
					),
				),
			)
		);

		$fields[] = array(

			'name'        => 'Identify',
			'title'       => 'Identify',
			'icon'        => 'dashicons-admin-generic',
			'attributes'  => array(
				'cols' => 2,
			),
			'description' => 'Identify Calls',
			'fields'      => array(
				array(
					'id'          => 'userid_is_email',
					'type'        => 'switcher',
					'title'       => 'Use email as the User ID instead of WP user ID. Not best practice.',
					'description' => '',
				),

				array(
					'id'          => 'use_alias',
					'type'        => 'switcher',
					'title'       => 'Use Alias calls, for Mixpanel for example',
					'description' => '',
				),
				array(
					'id'          => 'included_user_traits',
					'type'        => 'tap_list',
					'title'       => 'Select user traits',
					'description' => 'Select the user traits you want to add to your identify calls.',
					'options'     => $trait_options,
				),
				array(
					'id'         => 'chosen_class',
					'type'       => 'select',
					'title'      => 'Advanced',
					'attributes' => array(
						'multiple' => 'multiple',
						'style'    => 'display:none;',
					),
					'class'      => 'chosen',
					'style'      => 'display:none;',
				),

				array(
					'type'    => 'group',
					'id'      => 'custom_user_traits',
					'title'   => esc_html__( 'Custom user traits', 'plugin-name' ),
					'options' => array(
						'repeater'     => true,
						'accordion'    => true,
						'button_title' => esc_html__( 'Add new', 'plugin-name' ),
						'group_title'  => esc_html__( 'Accordion Title', 'plugin-name' ),
						'limit'        => 50,
						'sortable'     => false,
						'mode'         => 'compact',

					),
					'fields'  => array(

						array(
							'id'         => 'custom_user_traits_label',
							'type'       => 'text',
							'prepend'    => 'Trait name',
							'attributes' => array(
								// mark this field az title, on type this will change group item title
								'data-title'  => 'title',
								'placeholder' => esc_html__( 'Trait label', 'plugin-name' ),
							),
							'class'      => 'chosen',
						),
						array(
							'id'         => 'custom_user_traits_key',
							'type'       => 'text',
							'prepend'    => 'Meta key',
							'class'      => 'chosen',
							'attributes' => array(
								// mark this field az title, on type this will change group item title
								'data-title'  => 'title',
								'placeholder' => esc_html__( 'Custom field meta key', 'plugin-name' ),

							),
						),
					),
				),
			),
		);

		$fields[] = array(
			'name'   => 'Forms',
			'title'  => 'Forms',
			'icon'   => 'fa fa-list-alt',
			'fields' => array(
				array(
					'type'        => 'fieldset',
					'id'          => 'track_ninja_forms_fieldset',
					'title'       => 'Track Ninja Forms',
					'description' => 'Trigger events when people complete any Ninja Form',
					'options'     => array(
						'cols' => 3,
					),
					'fields'      => array(
						array(
							'id'   => 'track_ninja_forms',
							'type' => 'switcher',
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'track_gravity_forms_fieldset',
					'title'       => 'Track Gravity Forms',
					'description' => 'Trigger events when people complete any Gravity Form',
					'options'     => array(
						'cols' => 3,
					),
					'fields'      => array(
						array(
							'id'   => 'track_gravity_forms',
							'type' => 'switcher',
						),
					),
				),
				array(
					'id'          => 'forms_trigger_identify_calls',
					'type'        => 'switcher',
					'default'     => 'no',
					'title'       => 'Update user traits when forms are submitted?',
					'description' => 'ie, if a form has user info first name, last name, email, etc... we will make an identify call. Can be useful but can also cause unexpected issues.',
				),
			),
		);

		$fields[] = array(
			'name'   => 'WooCommerce',
			'title'  => 'WooCommerce',
			'icon'   => 'fa fa-list-alt',
			'fields' => array(
				array(
					'type'        => 'fieldset',
					'id'          => 'track_woocommerce_fieldset',
					'title'       => 'Track WooCommerce',
					'description' => 'Trigger events when people complete Woocommerce actions',
					'options'     => array(
						'cols' => 3,
					),
					'fields'      => array(
						array(
							'id'   => 'track_woocommerce',
							'type' => 'switcher',
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'woocommerce_events_labels',
					'title'       => 'Custom event labels',
					'description' => 'Change the event labels',
					'options'     => array(
						'cols' => 2,
					),
					'fields'      => array(

						array(
							'id'       => 'woocommerce_events_custom_labels',
							'type'     => 'accordion',
							'options'  => array(
								'allow_all_open' => true,
							),
							'sections' => array(
								array(
									'options' => array(
										'icon'   => 'fa fa-star',
										'title'  => 'Product and Cart Events',
										'closed' => true,
									),
									'fields'  => array(
										array(
											'id'      => 'woocommerce_events_product_added',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Product Added',

										),
										array(
											'id'      => 'woocommerce_events_product_removed',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Product Removed',

										),
										array(
											'id'      => 'woocommerce_events_cart_viewed',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Cart Viewed',

										),
									),
								),

								array(
									'options' => array(
										'icon'   => 'fa fa-star',
										'title'  => 'Order Events',
										'closed' => true,
									),
									'fields'  => array(
										array(
											'id'      => 'woocommerce_event_order_processing',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Processing',

										),
										array(
											'id'      => 'woocommerce_event_order_pending',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Pending',

										),
										array(
											'id'      => 'woocommerce_event_order_failed',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Failed',

										),
										array(
											'id'      => 'woocommerce_event_order_on_hold',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order On-Hold',

										),
										array(
											'id'      => 'woocommerce_event_order_paid',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Paid',

										),
										array(
											'id'      => 'woocommerce_event_order_completed',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Completed',

										),
										array(
											'id'      => 'woocommerce_event_order_refunded',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Refunded',

										),
										array(
											'id'      => 'woocommerce_event_order_cancelled',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Order Cancelled',

										),
										array(
											'id'      => 'woocommerce_event_order_coupon_applied',
											'type'    => 'text',
											'title'   => '</br>',
											'prepend' => 'Coupon Applied',

										),
									),
								),
							),
						),
					),
				),
				array(
					'type'        => 'fieldset',
					'id'          => 'track_woocommerce_meta_fieldset',
					'title'       => 'Add WooCommerce user meta to identify calls',
					'description' => 'Add WooCommerce user meta to identify calls',
					'options'     => array(
						'cols' => 3,
					),
					'fields'      => array(
						array(
							'id'   => 'track_woocommerce_meta',
							'type' => 'switcher',
						),
					),
				),
			),
		);

		/**
		 * instantiate your admin page
		 */
		$options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
	}
}
