<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


add_action( 'admin_init', 'swal_create_pages' );

/**
 *
 * Check if it has to create SWAL pages
 *
 */
function swal_create_pages() {

    // Create pages only if Custom pages option is selected
    $swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));

    if ($swal_pagina_account_default == 2) {
        swal_create_custom_pages();
    }
}

/**
 *
 * Create new SWAL page 'Login'
 *
 */
function swal_create_custom_pages() {

    $swal_pagina_account_login    = intval(get_option('swal_pagina_account_login'));
    $swal_pagina_account_register    = intval(get_option('swal_pagina_account_register'));
    $swal_pagina_account_forgot_password    = intval(get_option('swal_pagina_account_forgot_password'));
    $swal_pagina_account_reset_password    = intval(get_option('swal_pagina_account_reset_password'));
    $swal_pagina_account_logout    = intval(get_option('swal_pagina_account_logout'));

    // if Login page not exists then create
    if ( !swal_check_page_existance_from_template('login') && !$swal_pagina_account_login ) {
        // Create page
        $args = array(
                'post_title'    => __( 'Login', 'sw-ajax-login' ),
                'post_content'  => '[swal_show_login_form]',
                'post_name'     => 'login', //page slug
                'page_type'     => 'login'
            );
        
        // Create the page and update the option 
        update_option( 'swal_pagina_account_login', swal_new_template_page($args), true );
    }

    // if Register page not exists then create
    if ( !swal_check_page_existance_from_template('register') && !$swal_pagina_account_register ) {
        // Create page
        $args = array(
                'post_title'    => __( 'Register', 'sw-ajax-login' ),
                'post_content'  => '[swal_show_register_form]',
                'post_name'     => 'register', //page slug
                'page_type'     => 'register'
            );
        
        // Create the page and update the option 
        update_option( 'swal_pagina_account_register', swal_new_template_page($args), true );
    }

    // if Forgot Password page not exists then create
    if ( !swal_check_page_existance_from_template('forgot-password') && !$swal_pagina_account_forgot_password ) {
        // Create page
        $args = array(
                'post_title'    => __( 'Forgot Password', 'sw-ajax-login' ),
                'post_content'  => '[swal_show_forgot_password_form]',
                'post_name'     => 'forgot-password', //page slug
                'page_type'     => 'forgot-password'
            );
        
        // Create the page and update the option 
        update_option( 'swal_pagina_account_forgot_password', swal_new_template_page($args), true );
    }

    // if Reset Password page not exists then create
    if ( !swal_check_page_existance_from_template('reset-password') && !$swal_pagina_account_reset_password ) {
        // Create page
        $args = array(
                'post_title'    => __( 'Reset Password', 'sw-ajax-login' ),
                'post_content'  => '[swal_show_reset_password_form]',
                'post_name'     => 'reset-password', //page slug
                'page_type'     => 'reset-password'
            );
        
        // Create the page and update the option 
        update_option( 'swal_pagina_account_reset_password', swal_new_template_page($args), true );
    }

    // if Logout page not exists then create
    if ( !swal_check_page_existance_from_template('logout') && !$swal_pagina_account_logout ) {
        // Create page
        $args = array(
                'post_title'    => __( 'Logout', 'sw-ajax-login' ),
                'post_content'  => '[swal_show_logout_form]',
                'post_name'     => 'logout', //page slug
                'page_type'     => 'logout'
            );
        
        // Create the page and update the option 
        update_option( 'swal_pagina_account_logout', swal_new_template_page($args), true );
    }
    

    return;
}




/**
 *
 * Filter SWAL pages title on admin page
 *
 */
add_filter('display_post_states', 'swal_custom_post_states', 10,2);

function swal_custom_post_states( $states, $post ) { 

    //global $post;
    global $pagenow;

    if ($pagenow == 'nav-menus.php') {
        //return $states;
    }

    // Get the SWAL pages IDs
    $swal_pagina_account_login    = intval(get_option('swal_pagina_account_login'));
    $swal_pagina_account_register    = intval(get_option('swal_pagina_account_register'));
    $swal_pagina_account_forgot_password    = intval(get_option('swal_pagina_account_forgot_password'));
    $swal_pagina_account_reset_password    = intval(get_option('swal_pagina_account_reset_password'));
    $swal_pagina_account_logout    = intval(get_option('swal_pagina_account_logout'));

    if ( 'page' == get_post_type( $post->ID ) && $swal_pagina_account_login == $post->ID ) {

            $states[] = 'SW Ajax Login - ' . __('Login Page','sw-ajax-login'); 

    }

    if ( 'page' == get_post_type( $post->ID ) && $swal_pagina_account_register == $post->ID) {

            $states[] = 'SW Ajax Login - ' . __('Register Page','sw-ajax-login'); 

    }

    if ( 'page' == get_post_type( $post->ID ) && $swal_pagina_account_forgot_password == $post->ID) {

            $states[] = 'SW Ajax Login - ' . __('Forgot Password Page','sw-ajax-login'); 

    }

    if ( 'page' == get_post_type( $post->ID ) && $swal_pagina_account_reset_password == $post->ID) {

            $states[] = 'SW Ajax Login - ' . __('Reset Password Page','sw-ajax-login'); 

    }

    if ( 'page' == get_post_type( $post->ID ) && $swal_pagina_account_logout == $post->ID) {

            $states[] = 'SW Ajax Login - ' . __('Logout Page','sw-ajax-login'); 

    }

    return $states;
} 

?>