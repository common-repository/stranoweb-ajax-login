<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Advanced settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_advanced_settings' );



/**
 *
 * Adds register Advanced settings
 *
 */
function swal_register_advanced_settings() {


  // Advanced
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_disable_nonces', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_enable_nonces', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_force_fontawesome_load', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_enable_autopopup', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_autopopup_delay', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_autopopup_delay_autoopen_after_closing', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_disable_admin_bar_link', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_enable_wp_login_page', 'intval' );
  // End Advanced

}


/**
 * 
 * Advanced setting fields
 * 
 */
function swal_admin_advanced_settings() {


  // Advanced
    $swal_disable_nonces                = intval(get_option('swal_disable_nonces'));
    $swal_enable_nonces                = intval(get_option('swal_enable_nonces'));
    $swal_force_fontawesome_load        = intval(get_option('swal_force_fontawesome_load'));
    $swal_enable_autopopup              = intval(get_option('swal_enable_autopopup'));
    $swal_autopopup_delay               = get_option('swal_autopopup_delay') ? intval(get_option('swal_autopopup_delay')) : SWAL_AUTOPOPUP_DELAY;
    $swal_autopopup_delay_autoopen_after_closing          = intval(get_option('swal_autopopup_delay_autoopen_after_closing'));
    $swal_disable_admin_bar_link        = intval(get_option('swal_disable_admin_bar_link'));
    $swal_enable_wp_login_page          = intval(get_option('swal_enable_wp_login_page'));
  // End Advanced


    ?>
    <!-- Advanced -->
    <h3><?php esc_html_e('Advanced','sw-ajax-login') ?></h3>
    <table class="form-table">
    
        <!--
        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_disable_nonces',
                    'name'          => 'swal_disable_nonces',
                    'value'         => $swal_disable_nonces,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'label'         => esc_html__('Disable SWAL nonce','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('StranoWeb Ajax Login adds nonce to login, register, forgot password and change password to increase security. As the nonce is added as a hidden form element, it will also end up being cached by some caching plugins. This can cause some issues with nonce verification (403 error on admin-ajax.php)' ,'sw-ajax-login');
                 ?><br/>
                <u><?php esc_html_e('Note: WordPress default forms for Login, Register, Forgot Password and Change Password don\'t have nonce validation!','sw-ajax-login');
                 ?></u>
            </p>
        </td>
        </tr>-->

        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_enable_wp_login_page',
                    'name'          => 'swal_enable_wp_login_page',
                    'value'         => $swal_enable_wp_login_page,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'label'         => esc_html__('Do not disable wp-login.php page','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('SWAL is designed to replace all the default WordPress forms for login, registration and so on. However, it is possible to keep the wp-login.php page active (not recommended if the site is in production).' ,'sw-ajax-login');
                 ?>
            </p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_enable_nonces',
                    'name'          => 'swal_enable_nonces',
                    'value'         => $swal_enable_nonces,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'label'         => esc_html__('Enable SWAL nonce','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('StranoWeb Ajax Login will add nonce to login, register, forgot password and change password to increase security.' ,'sw-ajax-login');
                 ?><br/>
                <small class="swal-info-blue"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php esc_html_e('Note: Nonces are used to protect forms from certain types of misuse, malicious or otherwise, but they will also end up being cached by some caching plugins. This can cause some issues with nonce verification (403 error on admin-ajax.php) not allowing the login.','sw-ajax-login');
                 ?><br/><?php esc_html_e('Test carefully, if you are having issues with logins don\'t enable this option.','sw-ajax-login');
                 ?></small>
            </p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_force_fontawesome_load',
                    'name'          => 'swal_force_fontawesome_load',
                    'value'         => $swal_force_fontawesome_load,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'label'         => esc_html__('Force FontAwesome Loading','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('StranoWeb Ajax Login checks if FontAwesome is already in use on your site. If it\'s not, then it loads it. However with some themes this check fails, with the result that the icons are not loaded.' ,'sw-ajax-login');
                 ?><br/>
                <?php esc_html_e('With this function you can force their loading.','sw-ajax-login');
                 ?>
            </p>
        </td>
        </tr>
    </table>

    <h3><?php esc_html_e('Auto Popup','sw-ajax-login') ?></h3>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_enable_autopopup',
                    'name'          => 'swal_enable_autopopup',
                    'value'         => $swal_enable_autopopup,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'data'          => array(
                                        'target' => 'swal-auto-popup',
                                        ),
                    'label'         => esc_html__('Enable auto popup','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('Enabling this option the Login popup will auto open if the user isn\'t logged in.','sw-ajax-login');
                 ?>
            </p>
        </td>
        </tr>

        <tr valign="top" class="swal-auto-popup">
                <th scope="row"><label for="swal_autopopup_delay"><?php esc_html_e('Delay in opening popup' ,'sw-ajax-login');
                     ?></label></th>
                <td>

                    <div class="loggedin_menu_item_radiobuttons clear text_long settings-select swal_loggedin_custom_text inner-div-settings">
                        <input placeholder="<?php esc_html_e('Insert delay','sw-ajax-login'); ?>" type="number" id="swal_autopopup_delay" name="swal_autopopup_delay" class="text_long settings-select" value="<?php echo $swal_autopopup_delay; ?>">
                    </div>
                    <p class="clear description">
                    <?php esc_html_e('Delay in milliseconds. 1000 = 1s, 0 = no delay. The delay begins when the page is fully loaded.' ,'sw-ajax-login');
                     ?>
                    </p>   
                </td>
                </tr>

                <tr valign="top" class="swal-auto-popup">
                <th scope="row"><label for="swal_autopopup_delay_autoopen_after_closing"><?php esc_html_e('Delay in opening popup after closing' ,'sw-ajax-login');
                     ?></label></th>
                <td>

                    <div class="loggedin_menu_item_radiobuttons clear text_long settings-select swal_loggedin_custom_text inner-div-settings">
                        <input placeholder="<?php esc_html_e('Insert delay','sw-ajax-login'); ?>" type="number" id="swal_autopopup_delay_autoopen_after_closing" name="swal_autopopup_delay_autoopen_after_closing" class="text_long settings-select" value="<?php echo $swal_autopopup_delay_autoopen_after_closing; ?>">
                    </div>
                    <p class="clear description">
                    <?php esc_html_e('Delay in seconds. 1 = 1s, 3600 = 1hr, 0 = no delay.' ,'sw-ajax-login');
                     ?>
                    </p>   
                </td>
                </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_disable_admin_bar_link',
                    'name'          => 'swal_disable_admin_bar_link',
                    'value'         => $swal_disable_admin_bar_link,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'label'         => esc_html__('Hide admin bar link','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('Enable this option to hide the "SW Ajax Login" link on the admin bar','sw-ajax-login');
                 ?>
            </p>
        </td>
        </tr>

        <tr valign="top" class="swal-auto-popup">
        <th scope="row"></th>
        <td>
            <div class="swal-auto-popup-custom-pages padding-top">
                <h4><?php esc_html_e('Auto popup on these pages','sw-ajax-login') ?></h4>
                <p class="description sw-premium-text">
                    <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                </p>
            </div>
        </td>
        </tr>

    </table>
    <!-- Premium Advise -->
    <div class="clear description-section">
        <h3><?php esc_html_e('Need more settings?','sw-ajax-login'); ?></h3>
        <p>
         <?php esc_html_e('With Premium version you\'ll get the full control of StranoWeb Ajax Login with more options to improve speed performance!','sw-ajax-login');
         ?></p>
         <div>
            <div class="swal-premium-button left">
                <a href="<?php echo esc_html(SWAL_PREMIUM_BUY_LINK) ?>" target="_blank"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> <?php esc_html_e('Upgrade to the Premium version', 'sw-ajax-login') ?></a>
            </div>
        </div>
    </div>
    <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_11',true,array('id' => 'submit_tab_11' )); ?>

    <?php
}


?>