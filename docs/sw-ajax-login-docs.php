<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Display callback for the submenu page.
 */
function swal_doc_page() { 

	$homeurl    = home_url();

    ?>
    <div class="wrap">
        <h2><?php _e( 'StranoWeb Ajax Login Documentation', 'sw-ajax-login' ); ?></h2>
        <div class="tab_orizz_edit" id="tabs_edit">
	        <ul class="newTabs">
	        	<li><a href="#tab1"><?php esc_html_e('Features','sw-ajax-login') ?></a></li>
	        	<li><a href="#tab2"><?php esc_html_e('How to','sw-ajax-login') ?></a></li>
	            <li><a href="#tab3"><?php esc_html_e('Shortcodes','sw-ajax-login') ?></a></li>
	        </ul>
        </div>

        <div id="sw-ajax-login-admin-form" class="swal-tabscontent-docs">

    <!-- Features -->
        <div id="tab1" class="tab_content tab_description">
            <h3><?php esc_html_e('Features','sw-ajax-login') ?></h3>
            <hr/>
            <p><strong>Stranoweb Ajax Login Pro</strong> <?php _e( 'is a modal popup (but not only) that replaces the default WordPress login, register and lost password forms and comes with a lot of amazing features:', 'sw-ajax-login' ); ?></p>
            <ul class="swal-list-docs">
            	<li><?php esc_html_e('Ajax modal popup for login, register, lost password and logout. Same functions are working even on non-popup mode and without javascript.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Fully customizable login, register, lost password and logout popups.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Form fields validation.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Different popup layouts with image and text over image (more layouts to come).','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Optional custom css setting.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Social logins (facebook, twitter and google+, more to come) with several icon styles and position displacement.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Logged in Menu item: Once the user is logged in the plugin adds a menu item to the selected menu with optional user thumbnail and additional submenu. Thumbnail style, menu item text and submenu are fully customizable.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Customizable redirects and permalink.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('WordPress admin dashboard access restriction to users with specific roles.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Password length, you can choose the minimum length required.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Optional reCaptcha for new user registration form.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('New user registration can be disabled.','sw-ajax-login'); ?></li>
         		<li><?php esc_html_e('Styled Forgot password email.','sw-ajax-login'); ?></li>
            </ul>

        </div>

    <!-- How to -->
        <div id="tab2" class="tab_content tab_description">
        	<h3><?php esc_html_e('How to','sw-ajax-login') ?></h3>
            <hr/>
            <div class="sw-info-box">
                <p><i class="fa fa-info-circle fa-2x"></i> <?php printf(esc_html__('For the full \'How to\' guide please visit %s.','sw-ajax-login'), '<a href="https://www.ajaxlogin.com/docs/plugin-documentation/" target="_blank"><strong>StranoWeb Ajax Login</strong></a>'); ?></p>
            </div>

        </div>

    <!-- Shortcodes -->
        <div id="tab3" class="tab_content tab_description">
            <h3><?php esc_html_e('Shortcodes','sw-ajax-login') ?></h3>
            <hr/>
            <p><?php _e( 'StranoWeb Ajax Login comes with several shortcodes that can be used to insert content inside posts and pages.', 'sw-ajax-login' ); ?></p>
            <p><?php _e( 'You will find a dropdown list on the text editor.', 'sw-ajax-login' ); ?></p>
            <div class="sw-info-box">
                <p><i class="fa fa-info-circle fa-2x"></i> <?php printf(esc_html__('For the full Shortcodes list please visit %s.','sw-ajax-login'), '<a href="https://www.ajaxlogin.com/docs/plugin-documentation/shortcodes/" target="_blank"><strong>StranoWeb Ajax Login</strong></a>'); ?></p>
            </div>

        </div>

 
    </div>
    
    <?php
}

?>