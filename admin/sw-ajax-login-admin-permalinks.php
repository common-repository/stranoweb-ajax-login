<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 *
 * Add reCAPTCHA settings to admin options
 *
 */
add_action( 'admin_init', 'swal_permalinks_section_settings' );
add_action( 'swal_permalinks_section','swal_admin_permalinks_section_settings');


/**
 *
 * Adds options to flush rewrite rules when saved
 *
 * @since 1.9.6
 */
add_filter( 'swal_options_to_flush_when_saving', 'swal_permalink_options_to_flush', 10);

function swal_permalink_options_to_flush($options) {

    $options[] = 'swal_pagina_account_login';
    $options[] = 'swal_pagina_account_register';
    $options[] = 'swal_pagina_account_forgot_password';
    $options[] = 'swal_pagina_account_reset_password';
    $options[] = 'swal_pagina_account_logout';

    return $options;
}

/**
 *
 * Adds Permalinks settings
 *
 */
function swal_permalinks_section_settings() {

    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account_login', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account_register', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account_forgot_password', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account_reset_password', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account_logout', 'sanitize_text_field' );
}


/**
 * 
 * Permalinks setting fields
 * 
 */
function swal_admin_permalinks_section_settings() {

	global $post;

	$swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));
    $swal_pagina_account_login    = intval(get_option('swal_pagina_account_login'));
    $swal_pagina_account_register    = intval(get_option('swal_pagina_account_register'));
    $swal_pagina_account_forgot_password    = intval(get_option('swal_pagina_account_forgot_password'));
    $swal_pagina_account_reset_password    = intval(get_option('swal_pagina_account_reset_password'));
    $swal_pagina_account_logout    = intval(get_option('swal_pagina_account_logout'));



    ?>
    <tr valign="top">
        <th scope="row">
            <input type="radio" id="swal_pagina_account_pages" name="swal_pagina_account_default" value="2"<?php echo checked( $swal_pagina_account_default, '2',false ); ?>>
            <label for="swal_pagina_account_pages" class="sw-right-label"><?php esc_html_e('Custom pages','sw-ajax-login'); ?></label>
        </th>
        <td>
        	<?php
        		if (!swal_check_page_existance_from_template('login') && !swal_check_page_existance_from_template('register') && !swal_check_page_existance_from_template('forgot-password') && !swal_check_page_existance_from_template('reset-password') && !swal_check_page_existance_from_template('logout')) {
            	
            	echo '<p>'.esc_html__('StranoWeb Ajax Login will automatically create login, register, forgot password, reset password and logout pages. You can modify these pages according to your needs.','sw-ajax-login') .'</p>';
            	} else {
            ?>

        <div id="swal_custom_pages_wrapper" class="radiobuttons-group">
            <div class="sw-info-box sw-info-box-info">
                <h4><?php esc_html_e('Please read carefully','sw-ajax-login') ?></h4>
                <p><?php esc_html_e('StranoWeb Ajax Login have created all the necessary login, register, forgot password, reset password and logout pages.','sw-ajax-login') ?>
                </p>
                <p>
                    <?php esc_html_e('You can modify these pages according to your needs, just be sure "login page" contains one of the two SWAL Login shortcodes, otherwise you won\'t be able to login to your website!','sw-ajax-login') ?>
                </p>
                <p>
                    <?php esc_html_e('We suggest during test phase to work with 2 different browsers and stay logged on one of them while you are working on the other one.','sw-ajax-login') ?>
                </p>
            </div>

	        <ol>
	            <li>
	                <label for="swal_pagina_account_login"><?php esc_html_e('Login page','sw-ajax-login'); ?></label>
	                  <?php 
	                  $args = array(
	                      'depth'                 => 0,
	                      'child_of'              => 0,
	                      'selected'              => $swal_pagina_account_login,
	                      'echo'                  => 1,
	                      'name'                  => 'swal_pagina_account_login',
	                      'id'                    => null,
	                      'class'                 => 'clear regular-text text_long settings-select',
	                      'show_option_none'      => __('--- None ---','sw-ajax-login'),
	                      'show_option_no_change' => null,
	                      'option_none_value'     => null,
	                      );
	                      wp_dropdown_pages($args); ?>
	            </li>
	            <li>
	                <label for="swal_pagina_account_register"><?php esc_html_e('Register page','sw-ajax-login'); ?></label>
	                  <?php 
	                  $args = array(
	                      'depth'                 => 0,
	                      'child_of'              => 0,
	                      'selected'              => $swal_pagina_account_register,
	                      'echo'                  => 1,
	                      'name'                  => 'swal_pagina_account_register',
	                      'id'                    => null,
	                      'class'                 => 'clear regular-text text_long settings-select',
	                      'show_option_none'      => __('--- None ---','sw-ajax-login'),
	                      'show_option_no_change' => null,
	                      'option_none_value'     => null,
	                      );
	                      wp_dropdown_pages($args); ?>
	            </li>
	            <li>
	                <label for="swal_pagina_account_forgot_password"><?php esc_html_e('Forgot password page','sw-ajax-login'); ?></label>
	                  <?php 
	                  $args = array(
	                      'depth'                 => 0,
	                      'child_of'              => 0,
	                      'selected'              => $swal_pagina_account_forgot_password,
	                      'echo'                  => 1,
	                      'name'                  => 'swal_pagina_account_forgot_password',
	                      'id'                    => null,
	                      'class'                 => 'clear regular-text text_long settings-select',
	                      'show_option_none'      => __('--- None ---','sw-ajax-login'),
	                      'show_option_no_change' => null,
	                      'option_none_value'     => null,
	                      );
	                      wp_dropdown_pages($args); ?>
	            </li>
	            <li>
	                <label for="swal_pagina_account_reset_password"><?php esc_html_e('Reset password page','sw-ajax-login'); ?></label>
	                  <?php 
	                  $args = array(
	                      'depth'                 => 0,
	                      'child_of'              => 0,
	                      'selected'              => $swal_pagina_account_reset_password,
	                      'echo'                  => 1,
	                      'name'                  => 'swal_pagina_account_reset_password',
	                      'id'                    => null,
	                      'class'                 => 'clear regular-text text_long settings-select',
	                      'show_option_none'      => __('--- None ---','sw-ajax-login'),
	                      'show_option_no_change' => null,
	                      'option_none_value'     => null,
	                      );
	                      wp_dropdown_pages($args); ?>
	            </li>
	            <li>
	                <label for="swal_pagina_account_logout"><?php esc_html_e('Logout page','sw-ajax-login'); ?></label>
	                  <?php 
	                  $args = array(
	                      'depth'                 => 0,
	                      'child_of'              => 0,
	                      'selected'              => $swal_pagina_account_logout,
	                      'echo'                  => 1,
	                      'name'                  => 'swal_pagina_account_logout',
	                      'id'                    => null,
	                      'class'                 => 'clear regular-text text_long settings-select',
	                      'show_option_none'      => __('--- None ---','sw-ajax-login'),
	                      'show_option_no_change' => null,
	                      'option_none_value'     => null,
	                      );
	                      wp_dropdown_pages($args); ?>
	            </li>
	        </ol>
        </div>
        <?php
        	}
        	?> 
        </td>
    </tr>  
    <?php
}


?>