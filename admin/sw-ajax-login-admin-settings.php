<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


add_action('admin_menu', 'sw_ajax_login_menu');


/**
 * adds plugin settings menu to admin
 */
function sw_ajax_login_menu() {
    add_menu_page('SW Ajax Login', 'SW Ajax Login', 'administrator', SWAL_PLUGIN_SETTINGS_PAGE, 'swal_settings_page', SWAL_PLUGIN_ADMIN_IMAGES.'/menu-icon.png',25);
    add_submenu_page( SWAL_PLUGIN_SETTINGS_PAGE, esc_html__('Documentation','sw-ajax-login'), esc_html__('Documentation','sw-ajax-login'),'manage_options', SWAL_PLUGIN_SETTINGS_PAGE.'-docs','swal_doc_page',10);
}


/**
 * Flush rewrite rules when saving the following settings
 *
 *
 * @since 1.9.6
 */
add_action('swal_loaded', 'swal_settings_to_flush_after_save', 10 );

function swal_settings_to_flush_after_save() {
    $options = array(
                    'swal_redirect_after_login',
                    'swal_custom_redirect_after_login',
                    'swal_redirect_after_register',
                    'swal_custom_redirect_after_register',
                    'swal_redirect_after_logout',
                    'swal_custom_redirect_after_logout',
                    'swal_logged_in_redirect',
                    'swal_logged_in_redirect_custom_page',
                    'swal_pagina_account',
                    'swal_pagina_account_default',
                    'not_loggedin_force_redirect_to_login',
                    'swal_restrict_wp_dashboard',
                    'swal_restrict_wp_dashboard_roles',
                    'swal_hide_admin_bar',
                    );
    /**
     * Filter here the options that have to trigger flush rewrite rules
     */
    $options = apply_filters('swal_options_to_flush_when_saving', $options);

    foreach($options as $key => $value) {
        add_action('update_option_'.$value, 'swal_flush_rewrite_rules_on_saving', 10, 2 );
    }
}



/**
 * Flush rewrite rules when saving settings
 */
function swal_flush_rewrite_rules_on_saving($old_value, $new_value) {
    //get the permalink just saved and flush rewrite rules
    if (SWAL_P) {
        Layers_SwAjaxLogin::prefix_swal_rewrite_rule();
    } else {
        Layers_SwAjaxLogin_free::prefix_swal_rewrite_rule();
    }
    
    flush_rewrite_rules();
}


/**
 *
 * SW Ajax Login settings form
 *
 */
function swal_settings_page() {
?>

<div class="wrap swal-fields-editor-wrap">
    <div class="swal-loader-overlay"><div class="swal-loading-css-big"></div></div>
    <?php
    /**
     *
     * This hook is for the header bar
     *
     */
    do_action('swal_admin_header_bar');
    ?>
    <div class="swal-wrap-inner">
        <h1><?php esc_html_e('StranoWeb Ajax Login settings', 'sw-ajax-login') ?></h1>


        <form method="post" action="options.php" id="swal-admin-form">
            <?php 
            settings_fields( SWAL_PLUGIN_SETTINGS_PAGE . '-group' );
            do_settings_sections( SWAL_PLUGIN_SETTINGS_PAGE . '-group' );

            //get settings

            $homeurl    = home_url();

            $tab_premium = esc_html__('Premium Version','sw-ajax-login');
            if (SWAL_P)
                $tab_premium = esc_html__('Features','sw-ajax-login');



            /**
             *
             * This hook is for the tabs menu
             *
             */
            do_action('swal_admin_tabs_menu');

            ?>

        </form>
    </div>
</div>
<?php

}


?>