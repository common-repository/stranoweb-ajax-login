<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 *
 * WPML integration
 *
 */
add_action( 'init', 'swal_wpml_admin_login_window_settings', 10 );

function swal_wpml_admin_login_window_settings() {

    // If WPML is not installed then return
    if ( !function_exists('icl_object_id') ) {
        return;
    }

    // Menu item
    do_action( 'wpml_multilingual_options', 'swal_menu_item_custom_text' );
    do_action( 'wpml_multilingual_options', 'swal_menu_item_logout_custom_text' );
    do_action( 'wpml_multilingual_options', 'swal_loggedin_menu_item_custom_text' );
    do_action( 'wpml_multilingual_options', 'swal_menu_item_custom_link_to' );
    
    // Login Window
    do_action( 'wpml_multilingual_options', 'swal_login_form_title' );
    do_action( 'wpml_multilingual_options', 'swal_login_button' );
    do_action( 'wpml_multilingual_options', 'swal_login_button' );
    do_action( 'wpml_multilingual_options', 'swal_login_intro_text' );
    do_action( 'wpml_multilingual_options', 'swal_login_intro_text_link' );
    do_action( 'wpml_multilingual_options', 'swal_login_forgot_password_text' );
    do_action( 'wpml_multilingual_options', 'swal_text_login' );

    // Register Window
    do_action( 'wpml_multilingual_options', 'swal_no_password_text' );
    do_action( 'wpml_multilingual_options', 'swal_termsconditions_text' );
    do_action( 'wpml_multilingual_options', 'swal_termsconditionsintro_text' );
    do_action( 'wpml_multilingual_options', 'swal_register_form_title' );
    do_action( 'wpml_multilingual_options', 'swal_register_button' );
    do_action( 'wpml_multilingual_options', 'swal_register_intro_text' );
    do_action( 'wpml_multilingual_options', 'swal_register_intro_text_link' );
    do_action( 'wpml_multilingual_options', 'swal_register_login' );
    do_action( 'wpml_multilingual_options', 'swal_termsconditions_link_to' );
    do_action( 'wpml_multilingual_options', 'swal_register_no_autologin_redirect_custom_page' );
    do_action( 'wpml_multilingual_options', 'swal_register_success_message' );
    
    // Forgot Password Window
    do_action( 'wpml_multilingual_options', 'swal_forgot_pwd_intro_text' );
    do_action( 'wpml_multilingual_options', 'swal_forgot_pwd_intro_text_link' );
    do_action( 'wpml_multilingual_options', 'swal_forgot_pwd_form_title' );
    do_action( 'wpml_multilingual_options', 'swal_forgot_pwd_button' );
    do_action( 'wpml_multilingual_options', 'swal_forgot_password_login' );

    // Logout Window
    do_action( 'wpml_multilingual_options', 'swal_logout_login' );
    
    // Permalinks
    do_action( 'wpml_multilingual_options', 'swal_pagina_account_login' );
    do_action( 'wpml_multilingual_options', 'swal_pagina_account_register' );
    do_action( 'wpml_multilingual_options', 'swal_pagina_account_forgot_password' );
    do_action( 'wpml_multilingual_options', 'swal_pagina_account_reset_password' );
    do_action( 'wpml_multilingual_options', 'swal_login_forgot_password_text' );
    do_action( 'wpml_multilingual_options', 'swal_pagina_account_logout' );

    // Emails
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_registration' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_registration_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_registration_no_pwd' );
    do_action( 'wpml_multilingual_options', 'swal_email_forgot_password' );
    do_action( 'wpml_multilingual_options', 'swal_email_forgot_password_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_reset_password' );
    do_action( 'wpml_multilingual_options', 'swal_email_reset_password_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_footer' );

    // Register fields
    do_action( 'wpml_multilingual_options', 'swal_register_field' );
    do_action( 'wpml_multilingual_options', 'swal_register_steps_settings' );

    // Auto popup
    do_action( 'wpml_multilingual_options', 'swal_pages_autopopup' );

    // User verification
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_approval_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_approval_body' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_verification_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_verification_body' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_activation_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_activation_body' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_deactivation_subject' );
    do_action( 'wpml_multilingual_options', 'swal_email_new_user_deactivation_body' );

    // Messages
    do_action( 'wpml_multilingual_options', 'swal_sending_user_info_text' );
    do_action( 'wpml_multilingual_options', 'swal_login_successful_text' );
    do_action( 'wpml_multilingual_options', 'swal_register_successful_text' );
    do_action( 'wpml_multilingual_options', 'swal_redirecting_text' );
    do_action( 'wpml_multilingual_options', 'swal_logging_out_text' );
    do_action( 'wpml_multilingual_options', 'swal_authorization_failed_text' );
    do_action( 'wpml_multilingual_options', 'swal_something_went_wrong_text' );
    do_action( 'wpml_multilingual_options', 'swal_user_not_valid_code_text' );
    do_action( 'wpml_multilingual_options', 'swal_user_verified_account_text' );
    do_action( 'wpml_multilingual_options', 'swal_user_already_verified_account_text' );
    do_action( 'wpml_multilingual_options', 'swal_user_cant_verify_account_text' );
    do_action( 'wpml_multilingual_options', 'swal_2fa_code_text' );
    do_action( 'wpml_multilingual_options', 'swal_2fa_back_text' );

}

?>