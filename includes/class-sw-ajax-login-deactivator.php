<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.ajaxlogin.com
 * @since      1.0.0
 *
 * @package    sw-ajax-login
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    sw-ajax-login
 * @author     StranoWeb <info@ajaxlogin.com>
 */
class Sw_ajax_login_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// clear the permalinks to remove plugin's rewrite rules
        flush_rewrite_rules();

	}

}
