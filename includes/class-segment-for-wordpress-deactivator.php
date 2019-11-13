<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Segment_For_Wordpress
 * @subpackage Segment_For_Wordpress/includes
 * @author     Your Name <email@example.com>
 */
class Segment_For_Wordpress_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		/**
		 * This only required if custom post type has rewrite!
		 */
		flush_rewrite_rules();

	}

}
