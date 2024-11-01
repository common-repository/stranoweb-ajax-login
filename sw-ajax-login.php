<?php
/**
 * Plugin Name: Stranoweb Ajax Login
 * Plugin URI: https://www.ajaxlogin.com
 * Description: Adds a beautiful and fully customizable modal Ajax Login popup with various social logins and reCaptcha option for new user registration, can restrict access to Wordpress dashboard and a lot more.
 * Version: 2.0.4
 * Author: StranoWeb
 * Author URI: https://www.stranoweb.com
 * Tested up to: 6.4
 * Text Domain: sw-ajax-login
 * Domain Path: /languages
 * License: GPLv3 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define('SWAL_PLUGIN_VERSION', '2.0.4');
define('SWAL_DEVELOP', false);

// Include the main class.
if ( ! class_exists( 'Layers_SwAjaxLogin_free' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-sw-ajax-login.php';
    include_once dirname( __FILE__ ) . '/includes/class-sw-ajax-login-custom-nav.php';
}


// Check if Free version is installed
add_action( 'plugins_loaded', 'swal_pro_add_core_free' );


/**
 *
 * Build error message
 *
 */
function swal_pro_deactivate_premium_version_notice() {
   ?>
   <div class="notice notice-error is-dismissible">
      <p><img src="<?php echo plugins_url( 'admin/img/logo-sito-swal.png', __FILE__ ); ?>" alt="" /><br/>
      	<strong><?php esc_html_e('Uh Oh!', 'sw-ajax-login' ); ?></strong>: <?php echo sprintf( __( 'It seems you have already installed <strong>StranoWeb Ajax Login Premium</strong>! If you really want to downgrade to the free version you need to deactivate and delete the Premium version from the %splugins page%s', 'sw-ajax-login' ), '<a href="' . wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=sw-ajax-login/sw-ajax-login.php&amp;plugin_status=all&amp;paged=1&amp;s=', 'deactivate-plugin_sw-ajax-login/sw-ajax-login.php' ) . '">', '</a>' ); ?></p>
   </div>
   <?php
}

/**
 *
 * Display Error message if premium version of plugin is already installed, otherwise initialize the free plugin
 *
 */
function swal_pro_add_core_free() {
   if ( class_exists( 'Layers_SwAjaxLogin' ) ) {
      add_action( 'admin_notices', 'swal_pro_deactivate_premium_version_notice' );
      return;
   } else {

   	// Initialize the plugin
	  Layers_SwAjaxLogin_free::get_instance();
    SWAL_Custom_Nav::get_instance();

   }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sw-ajax-login-activator.php
 */
//add_action( 'admin_notices', 'swal_pro_deactivate_free_version_notice' );
function activate_sw_ajax_login_free() {

// Include the create media file class for the images upload on activation
  if ( ! class_exists( 'JDN_Create_Media_File' ) ) {
      require_once plugin_dir_path( __FILE__ ) . 'includes/class-create-media-file.php';
    }
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-sw-ajax-login-activator.php';
    Sw_ajax_login_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sw-ajax-login-deactivator.php
 */
function deactivate_sw_ajax_login_free() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-sw-ajax-login-deactivator.php';
  Sw_ajax_login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sw_ajax_login_free' );
register_deactivation_hook( __FILE__, 'deactivate_sw_ajax_login_free' );

?>