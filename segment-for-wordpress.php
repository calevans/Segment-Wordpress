<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://in8.io
 * @since             1.0.0
 * @package           Segment_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Segment for WP by in8.io
 * Plugin URI:        https://in8.io
 * Description:       Re-wrote and extended the Segment Analytics plugin. The official one has been dead for a couple of years, which is a shame. Hope you find this useful! Added several extra features.
 * Version:           1.0.9
 * Author:            Juan
 * Author URI:        https://juangonzalez.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       segment-for-wordpress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_PLUGIN_NAME', 'segment-for-wordpress' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-segment-for-wordpress-activator.php
 */
function activate_segment_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-segment-for-wordpress-activator.php';
	Segment_For_Wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-segment-for-wordpress-deactivator.php
 */
function deactivate_segment_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-segment-for-wordpress-deactivator.php';
	Segment_For_Wordpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_segment_for_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_segment_for_wordpress' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-segment-for-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
/********************************************
 * ALLOWS YOU TO ACCESS THE PLUGIN CLASS
 * in template/outside of the plugin.
 */
global $pbt_prefix_segment_for_wordpress;
$pbt_prefix_segment_for_wordpress = new Segment_For_Wordpress();
$pbt_prefix_segment_for_wordpress->run();