<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Redirects & Permalinks settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_redirects_settings' );

 

/**
 *
 * Adds Redirects & Permalinks settings
 *
 */
function swal_register_redirects_settings() {

  // Redirects & Permalink
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_redirect_after_login', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_custom_redirect_after_login', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_redirect_after_logout', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_custom_redirect_after_logout', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logged_in_redirect', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logged_in_redirect_custom_page', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_pagina_account_default', 'intval' );
  // End Redirects & Permalink

}


/**
 * 
 * Social Redirects & Permalinks fields
 * 
 */
function swal_admin_redirects_settings() {

  // Redirects & Permalink
    $swal_redirect_after_login           = intval(get_option('swal_redirect_after_login',SWAL_REDIRECT_AFTER_LOGIN));
    $swal_custom_redirect_after_login    = ltrim(esc_attr(get_option('swal_custom_redirect_after_login')), '/');
    $swal_redirect_after_logout          = intval(get_option('swal_redirect_after_logout',SWAL_REDIRECT_AFTER_LOGOUT));
    $swal_custom_redirect_after_logout   = ltrim(esc_attr(get_option('swal_custom_redirect_after_logout')), '/');
    $swal_logged_in_redirect             = intval(get_option('swal_logged_in_redirect',SWAL_LOGGED_IN_REDIRECT));
    $swal_logged_in_redirect_custom_page    = ltrim(esc_attr(get_option('swal_logged_in_redirect_custom_page')), '/');
    $swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));
    $swal_pagina_account            = esc_attr(get_option('swal_pagina_account'));
  // End Redirects & Permalink

    $homeurl    = home_url();


    ?>
    <!-- Redirects & Permalink -->
    <h3><?php esc_html_e('Redirects','sw-ajax-login') ?></h3>
            <table class="form-table">
                <tr valign="top">
                <th scope="row"><label for="swal_redirect_after_login"><?php esc_html_e('After login redirect to:','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_redirect_after_login" id="swal_redirect_after_login" class="floatL margin-right settings-select">
                        <?php
                            $item = array();
                            $item[] = __("Homepage",'sw-ajax-login');
                            $item[] = __("Same page",'sw-ajax-login');
                            $item[] = __("Custom page",'sw-ajax-login');
                            $item[] = __("Admin dashboard",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_redirect_after_login, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <div id="custom-page-redirect" class="clear padding-top hide text_long">
                        <code><?php echo $homeurl ?>/</code>
                        <input placeholder="<?php esc_html_e('Insert page to redirect','sw-ajax-login'); ?>" type="text" id="swal_custom_redirect_after_login" name="swal_custom_redirect_after_login" class="regular-text code text_long settings-select" value="<?php echo $swal_custom_redirect_after_login; ?>">
                        <p class="clear description"><?php esc_html_e('If the field is empty the user will be redirected to the homepage.','sw-ajax-login') ?></p>
                    </div>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_redirect_after_logout"><?php esc_html_e('After logout redirect to:','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_redirect_after_logout" id="swal_redirect_after_logout" class="floatL margin-right settings-select">
                        <?php
                            $item = array();
                            $item[] = __("Homepage",'sw-ajax-login');
                            $item[] = __("Same page",'sw-ajax-login');
                            $item[] = __("Custom page",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_redirect_after_logout, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <div id="custom-page-redirect-logout" class="clear padding-top hide text_long">
                        <code><?php echo $homeurl ?>/</code>
                        <input placeholder="<?php esc_html_e('Insert page to redirect','sw-ajax-login'); ?>" type="text" id="swal_custom_redirect_after_logout" name="swal_custom_redirect_after_logout" class="regular-text code text_long settings-select" value="<?php echo $swal_custom_redirect_after_logout; ?>">
                        <p class="clear description"><?php esc_html_e('If the field is empty the user will be redirected to the homepage.','sw-ajax-login') ?></p>
                    </div>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_logged_in_redirect"><?php esc_html_e('Login page -> If already logged in:','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_logged_in_redirect" id="swal_logged_in_redirect" class="floatL margin-right settings-select">
                        <?php
                            $item = array();
                            $item[] = __('Show "User already logged in" message','sw-ajax-login');
                            $item[] = __("Redirect to homepage",'sw-ajax-login');
                            $item[] = __("Redirect to custom page",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_logged_in_redirect, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <div id="loggedin-custom-page-redirect" class="clear padding-top hide text_long">
                        <code><?php echo $homeurl ?>/</code>
                        <input placeholder="<?php esc_html_e('Insert page to redirect','sw-ajax-login'); ?>" type="text" id="swal_logged_in_redirect_custom_page" name="swal_logged_in_redirect_custom_page" class="regular-text code text_long settings-select" value="<?php echo $swal_logged_in_redirect_custom_page; ?>">
                    </div>
                    <p class="clear description"><?php esc_html_e('You can set up the action to do if an already logged in user lands on Login, Register or Forgot Password page.','sw-ajax-login') ?> <?php esc_html_e('If the field is empty the user will be redirected to the homepage.','sw-ajax-login') ?></p>
                </td>
                </tr>
            </table>

            <h3><?php esc_html_e('WordPress admin dashboard access','sw-ajax-login') ?></h3>
            <table class="form-table">
                <tr valign="top">
                <th scope="row"></th>
                <td>
                    <p class="description sw-premium-text">
                        <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                    </p>
                </td>
                </tr>
            </table>

            <h3><?php esc_html_e('Permalink','sw-ajax-login') ?></h3>
            <?php
                        if (swal_check_permalink_for_index()) {
                            echo '<div class="sw-info-box sw-info-box-info"><p><i class="fa fa-info-circle fa-2x"></i> ';
                            esc_html_e( 'Your permalink structure contains /index.php. It’s not search engine friendly & you are missing out the opportunity to rank higher in the search engine. We reccomend to remove it.');
                            echo '<br/>';
                            printf( esc_html__( 'To fix it go to %s and click on Post name & save the permalink structure.', 'sw-ajax-login' ) , '<a href="'. $homeurl.'/wp-admin/options-permalink.php">'. esc_html__('Settings> Permalink','sw-ajax-login').'</a>' );
                            echo '</p></div>';
                        }
                    ?> 
            <p><?php esc_html_e('SWAL replaces WordPress default login, registration and forgot password pages with its own modules. All SWAL ajax pages are also accessible via direct URL, you can customize the base to suit your needs.','sw-ajax-login') ?>
                <br/><?php 

                $current_permalink = swal_get_permalink();

                esc_html_e('Links to forms are: ','sw-ajax-login') ?></p>

               <ol>
                    <li><code><?php echo wp_login_url() ? wp_login_url() : esc_html__( 'You must assign the Login page!', 'sw-ajax-login' ); ?></code></li>
                    <li><code><?php echo wp_registration_url() ? wp_registration_url() : esc_html__( 'You must assign the Register page!', 'sw-ajax-login' ); ?></code></li>
                    <li><code><?php echo wp_lostpassword_url() ? wp_lostpassword_url() : esc_html__( 'You must assign the Forgot Password page!', 'sw-ajax-login' ); ?></code></li>
                    <li><code><?php echo swal_resetpassword_url() ? swal_resetpassword_url() : esc_html__( 'You must assign the Reset Password page!', 'sw-ajax-login' ); ?></code></li>
                    <li><code><?php echo swal_logout_url() ? swal_logout_url() : esc_html__( 'You must assign the Logout page!', 'sw-ajax-login' ); ?></code></li>
                </ol>
                
            <table class="form-table">

                <tr valign="top">
                <th scope="row">
                    <input type="radio" id="swal_pagina_account_default" name="swal_pagina_account_default" value="0"<?php echo checked( $swal_pagina_account_default, '0',false ); ?>>
                    <label for="swal_pagina_account_default" class="sw-right-label"><?php esc_html_e('Default','sw-ajax-login'); ?></label>
                </th>
                <td>
                    <code class="clear"><?php echo $homeurl . '/' . swal_check_permalink_for_index() . SWAL_PAGINA_ACCOUNT; ?></code>      
                </td>
                </tr>

                <tr valign="top">
                <th scope="row">
                    <input type="radio" id="swal_pagina_account_custom" name="swal_pagina_account_default" value="1"<?php echo checked( $swal_pagina_account_default, '1',false ); ?>>
                    <label for="swal_pagina_account_custom" class="sw-right-label"><?php esc_html_e('Custom base','sw-ajax-login'); ?></label>
                </th>
                <td>
                    <code><?php echo $homeurl . '/' . swal_check_permalink_for_index(); ?></code>
                    <input placeholder="<?php esc_html_e('Insert base','sw-ajax-login'); ?>" type="text" id="swal_pagina_account" name="swal_pagina_account" class="regular-text code text_long settings-select" value="<?php echo $swal_pagina_account; ?>">       
                    <p class="clear description"><?php esc_html_e('If the field is empty it will use the default permalink.','sw-ajax-login') ?></p>               
                </td>
                </tr>

                <?php
                    /**
                     *
                     * This hook is for Social login settings
                     *
                     */
                    do_action('swal_permalinks_section');
                ?>  
            </table>
            <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_8',true,array('id' => 'submit_tab_8' )); ?>

    <?php
}


?>