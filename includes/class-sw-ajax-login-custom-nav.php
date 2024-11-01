<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Add custom nav meta box.
 *
 * Adapted from http://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
 * @since 1.6.0
 */

if ( ! class_exists( 'SWAL_Custom_Nav' ) ) {
class SWAL_Custom_Nav {

	private static $instance;
     
    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
            self::$instance->__construct();
        }
        return self::$instance;
    }

	public function __construct() {
		add_action('admin_init', array($this, 'add_nav_menu_meta_boxes'));
	}

  
    public function add_nav_menu_meta_boxes() {
          add_meta_box('swal_login_nav_link',__( 'SW Ajax Login endpoints', 'sw-ajax-login' ), array( $this, 'nav_menu_link'),'nav-menus','side','low');
        }
        
    public function nav_menu_link() {

    	    	
    	$swal_menu_item_text            = intval(get_option('swal_menu_item_text',SWAL_MENU_ITEM_TEXT));
  		$swal_menu_item_custom_text     = esc_html(get_option('swal_menu_item_custom_text'));
  		$swal_menu_login_text           = swal_menu_login_text($swal_menu_item_text,$swal_menu_item_custom_text);

  		$swal_login_intro_text_link          = esc_html(get_option('swal_login_intro_text_link',__(SWAL_LOGIN_INTRO_TEXT_LINK,'sw-ajax-login')));

    	$swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
      	$swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');
      	
    	?>
      <div id="posttype-swal-login" class="posttypediv">
        <div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
          <ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
            <li>
              <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> <?php esc_html_e('Login Link','sw-ajax-login'); ?>
              </label>
              <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
              <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?php echo $swal_menu_login_text; ?>">
              <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="<?php echo wp_login_url(); ?>">
              <input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value="swal-menu-item sw-open-login">
            </li>
            <li>
              <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item[-2][menu-item-object-id]" value="-2"> <?php esc_html_e('Register Link','sw-ajax-login'); ?>
              </label>
              <input type="hidden" class="menu-item-type" name="menu-item[-2][menu-item-type]" value="custom">
              <input type="hidden" class="menu-item-title" name="menu-item[-2][menu-item-title]" value="<?php echo $swal_login_intro_text_link; ?>">
              <input type="hidden" class="menu-item-url" name="menu-item[-2][menu-item-url]" value="<?php echo wp_registration_url(); ?>">
              <input type="hidden" class="menu-item-classes" name="menu-item[-2][menu-item-classes]" value="swal-menu-item sw-open-register">
            </li>
            <li>
              <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item[-3][menu-item-object-id]" value="-3"> <?php esc_html_e('Logout Link','sw-ajax-login'); ?>
              </label>
              <input type="hidden" class="menu-item-type" name="menu-item[-3][menu-item-type]" value="custom">
              <input type="hidden" class="menu-item-title" name="menu-item[-3][menu-item-title]" value="<?php echo $swal_menu_item_logout_custom_text; ?>">
              <input type="hidden" class="menu-item-url" name="menu-item[-3][menu-item-url]" value="<?php echo swal_logout_url(); ?>">
              <input type="hidden" class="menu-item-classes" name="menu-item[-3][menu-item-classes]" value="swal-menu-item open_logout">
            </li>
          </ul>
        </div>
        <ul class="categorychecklist form-no-clear">
        	<li><?php esc_html_e('- All the menu items added as \'Login Link\' submenu will be only visible to logged in users.','sw-ajax-login'); ?></li>
        	<li><?php esc_html_e('- \'Register Link\' is only visible to NOT logged in users.','sw-ajax-login'); ?></li>
        	<li><?php esc_html_e('- \'Logout Link\' is only visible to logged in users.','sw-ajax-login'); ?></li>
        </p>
        <p class="button-controls">
          <span class="add-to-menu">
            <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_html_e('Add to Menu','sw-ajax-login'); ?>" name="add-post-type-menu-item" id="submit-posttype-swal-login">
            <span class="spinner"></span>
          </span>
        </p>
      </div>
    <?php }
}
}

SWAL_Custom_Nav::get_instance();


?>