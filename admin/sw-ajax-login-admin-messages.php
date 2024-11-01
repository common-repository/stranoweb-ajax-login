<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 *
 * Adds the tab to manage Messages
 *
 */
add_filter('swal_admin_tabs_items', 'swal_messages_tab');

function swal_messages_tab( $menu_item) {

    $menu_item[] = array(
          'title'  => esc_html__('Messages','sw-ajax-login'),
          'priority'   => 44,
          'callback'   => 'swal_admin_messages_settings',
          ); 

  return $menu_item;
}

/**
 *
 * Add Messages settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_messages_settings' );
 

/**
 *
 * Adds Messages settings
 *
 */
function swal_register_messages_settings() {

    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_sending_user_info_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_login_successful_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_register_successful_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_redirecting_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logging_out_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_authorization_failed_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_something_went_wrong_text', 'sanitize_text_field' );

    if (class_exists( 'SwAjaxLogin_user' )) {
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_user_not_valid_code_text', 'sanitize_text_field' );
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_user_verified_account_text', 'sanitize_text_field' );
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_user_already_verified_account_text', 'sanitize_text_field' );
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_user_cant_verify_account_text', 'sanitize_text_field' );
    }

    if (class_exists( 'swal_2fa_login' )) {
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_2fa_code_text', 'sanitize_text_field' );
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_2fa_back_text', 'sanitize_text_field' );
    }
    
}


/**
 * 
 * Messages setting fields
 * 
 */
function swal_admin_messages_settings() {

    

  // Messages
    $swal_sending_user_info_text          = get_option('swal_sending_user_info_text') ? esc_attr(get_option('swal_sending_user_info_text')) : __(SWAL_SENDING_USER_INFO_TEXT,'sw-ajax-login');
    $swal_login_successful_text           = get_option('swal_login_successful_text') ? esc_attr(get_option('swal_login_successful_text')) : __(SWAL_LOGIN_SUCCESSFUL_TEXT,'sw-ajax-login');
    $swal_register_successful_text        = get_option('swal_register_successful_text') ? esc_attr(get_option('swal_register_successful_text')) : __(SWAL_REGISTER_SUCCESSFUL_TEXT,'sw-ajax-login');
    $swal_redirecting_text                = get_option('swal_redirecting_text') ? esc_attr(get_option('swal_redirecting_text')) : __(SWAL_REDIRECTING_TEXT,'sw-ajax-login');
    $swal_logging_out_text                = get_option('swal_logging_out_text') ? esc_attr(get_option('swal_logging_out_text')) : __(SWAL_LOGGING_OUT_TEXT,'sw-ajax-login');
    $swal_authorization_failed_text       = get_option('swal_authorization_failed_text') ? esc_attr(get_option('swal_authorization_failed_text')) : __(SWAL_AUTHORIZATION_FAILED_TEXT,'sw-ajax-login');
    $swal_something_went_wrong_text       = get_option('swal_something_went_wrong_text') ? esc_attr(get_option('swal_something_went_wrong_text')) : __(SWAL_SOMETHING_WENT_WRONG_TEXT,'sw-ajax-login');

    if (class_exists( 'SwAjaxLogin_user' )) {
      $swal_user_not_valid_code_text        = get_option('swal_user_not_valid_code_text') ? esc_attr(get_option('swal_user_not_valid_code_text')) : __(SWAL_USER_NOT_VALID_CODE_TEXT,'sw-ajax-login');
      $swal_user_verified_account_text      = get_option('swal_user_verified_account_text') ? esc_attr(get_option('swal_user_verified_account_text')) : __(SWAL_USER_VERIFIED_ACCOUNT_TEXT,'sw-ajax-login');
      $swal_user_already_verified_account_text      = get_option('swal_user_already_verified_account_text') ? esc_html(get_option('swal_user_already_verified_account_text')) : __(SWAL_USER_ALREADY_VERIFIED_ACCOUNT_TEXT,'sw-ajax-login');
      $swal_user_cant_verify_account_text   = get_option('swal_user_cant_verify_account_text') ? esc_html(get_option('swal_user_cant_verify_account_text')) : __(SWAL_USER_CANT_VERIFY_ACCOUNT_TEXT,'sw-ajax-login');
    }

    if (class_exists( 'swal_2fa_login' )) {
      $swal_2fa_code_text                   = get_option('swal_2fa_code_text') ? esc_attr(get_option('swal_2fa_code_text')) : __(SWAL_2FA_CODE_TEXT,'sw-ajax-login');
      $swal_2fa_back_text                   = get_option('swal_2fa_back_text') ? esc_attr(get_option('swal_2fa_back_text')) : __(SWAL_2FA_BACK_TEXT,'sw-ajax-login');
    }
  // Messages
    

    ?>

  <h3><?php esc_html_e('Messages','sw-ajax-login') ?></h3>
  <table class="form-table">
    <tr valign="top">
        <th scope="row">
          <label for="swal_sending_user_info_text"><?php esc_html_e('Sending user info','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_SENDING_USER_INFO_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_sending_user_info_text" name="swal_sending_user_info_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_sending_user_info_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_login_successful_text"><?php esc_html_e('Login successful','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_LOGIN_SUCCESSFUL_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_login_successful_text" name="swal_login_successful_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_login_successful_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_register_successful_text"><?php esc_html_e('Registration successful','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_REGISTER_SUCCESSFUL_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_register_successful_text" name="swal_register_successful_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_register_successful_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_redirecting_text"><?php esc_html_e('Redirecting','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_REDIRECTING_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_redirecting_text" name="swal_redirecting_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_redirecting_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_logging_out_text"><?php esc_html_e('Logging out','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_LOGGING_OUT_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_logging_out_text" name="swal_logging_out_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_logging_out_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_authorization_failed_text"><?php esc_html_e('Authorization failed','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_AUTHORIZATION_FAILED_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_authorization_failed_text" name="swal_authorization_failed_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_authorization_failed_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_something_went_wrong_text"><?php esc_html_e('Something went wrong','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_SOMETHING_WENT_WRONG_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_something_went_wrong_text" name="swal_something_went_wrong_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_something_went_wrong_text; ?>">
        </td>
    </tr>
  </table>

  <?php
        if (class_exists( 'SwAjaxLogin_user' )) {
          ?>
  <h3><?php esc_html_e('User Verification','sw-ajax-login') ?></h3>
  <table class="form-table">
    <tr valign="top">
        <th scope="row">
          <label for="swal_user_not_valid_code_text"><?php esc_html_e('Verification code is not valid','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_USER_NOT_VALID_CODE_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_user_not_valid_code_text" name="swal_user_not_valid_code_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_user_not_valid_code_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_user_verified_account_text"><?php esc_html_e('User verification success','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_USER_VERIFIED_ACCOUNT_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_user_verified_account_text" name="swal_user_verified_account_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_user_verified_account_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_user_already_verified_account_text"><?php esc_html_e('User already verified','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_USER_ALREADY_VERIFIED_ACCOUNT_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_user_already_verified_account_text" name="swal_user_already_verified_account_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_user_already_verified_account_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_user_cant_verify_account_text"><?php esc_html_e('Can\'t verify the account','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_USER_CANT_VERIFY_ACCOUNT_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_user_cant_verify_account_text" name="swal_user_cant_verify_account_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_user_cant_verify_account_text; ?>">
        </td>
    </tr>
  </table>
    <?php
        }
          ?>

  <?php
        if (class_exists( 'swal_2fa_login' )) {
          ?>
  <h3><?php esc_html_e('Two-Factor Authentication','sw-ajax-login') ?></h3>
  <table class="form-table">
    <tr valign="top">
        <th scope="row">
          <label for="swal_2fa_code_text"><?php esc_html_e('2FA Code','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_2FA_CODE_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_2fa_code_text" name="swal_2fa_code_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_2fa_code_text; ?>">
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
          <label for="swal_2fa_back_text"><?php esc_html_e('Back','sw-ajax-login'); ?></label>
        </th>
        <td>
            <input placeholder="<?php esc_html_e(SWAL_2FA_BACK_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_2fa_back_text" name="swal_2fa_back_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_2fa_back_text; ?>">
        </td>
    </tr>
  </table>
    <?php
        }
          ?>
  <?php 

}


?>