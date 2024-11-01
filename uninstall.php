<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('swal_pagina_account');
delete_option('swal_user_thumbnail_style');
delete_option('swal_menu_to_append');
delete_option('swal_ajax_login_background');
delete_option('swal_ajax_register_background');
delete_option('swal_ajax_forgot_password_background');
delete_option('swal_ajax_logout_background');
 
// for site options in Multisite
delete_site_option('swal_pagina_account');
delete_site_option('swal_user_thumbnail_style');
delete_site_option('swal_menu_to_append');
delete_site_option('swal_ajax_login_background');
delete_site_option('swal_ajax_register_background');
delete_site_option('swal_ajax_forgot_password_background');
delete_site_option('swal_ajax_logout_background');

?>