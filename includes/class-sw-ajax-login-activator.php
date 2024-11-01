<?php

/**
 * Fired during plugin activation
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
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    sw-ajax-login
 * @author     StranoWeb <info@ajaxlogin.com>
 */
class Sw_ajax_login_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$swal_plugin_images = plugin_dir_url( dirname(__FILE__) ) . "assets/img";

        //If the option is null then copy the image into wordpress media, this avoid duplication in case the option already exists
        $image_login = esc_attr(get_option( 'swal_ajax_login_background' ));

        if (!$image_login) {
            // Add an unattached image to the media library and set it as default
            $image_url = $swal_plugin_images . '/landscape-1.jpg';
            $create_image = new JDN_Create_Media_File( $image_url );
            $image_id = $create_image->attachment_id;
            update_option('swal_ajax_login_background', $image_id );
        }

        $image_register = esc_attr(get_option( 'swal_ajax_register_background' ));
        if (!$image_register) {
            $image_url = $swal_plugin_images . '/landscape-2.jpg';
            $create_image = new JDN_Create_Media_File( $image_url );
            $image_id = $create_image->attachment_id;
            update_option('swal_ajax_register_background', $image_id );
        }

        $image_forgot_password = esc_attr(get_option( 'swal_ajax_forgot_password_background' ));
        if (!$image_forgot_password) {
            $image_url = $swal_plugin_images . '/landscape-3.jpg';
            $create_image = new JDN_Create_Media_File( $image_url );
            $image_id = $create_image->attachment_id;
            update_option('swal_ajax_forgot_password_background', $image_id );
        }

        flush_rewrite_rules();
	}

}
