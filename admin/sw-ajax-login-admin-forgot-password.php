<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Forgot Password settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_forgot_password_settings' );


/**
 *
 * Adds register Forgot Password settings
 *
 */
function swal_register_forgot_password_settings() {

  // Forgot Password Window
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_show_password_reset_password_email', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_forgot_password_icons', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_ajax_forgot_password_background', 'sanitize_file_name' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_password_bg_image_alignment', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_forgot_password_login', 'htmlentities' );
  // End Forgot Password Window

}


/**
 * 
 * Forgot Password setting fields
 * 
 */
function swal_admin_forgot_password_settings() {

  // Forgot Password Window
    $swal_show_password_reset_password_email = esc_attr(get_option('swal_show_password_reset_password_email'));
    $swal_add_forgot_password_icons      = intval(get_option('swal_add_forgot_password_icons'));
    $swal_password_bg_image_alignment   = intval(get_option('swal_password_bg_image_alignment',SWAL_PASSWORD_BG_IMAGE_ALIGNMENT));
    $swal_forgot_password_login     = html_entity_decode(strip_tags(get_option('swal_forgot_password_login')));
  // End Forgot Password Window

    $homeurl    = home_url();


    ?>
    <!-- Forgot Password Window -->
    <h3><?php esc_html_e('Forgot Password window','sw-ajax-login') ?></h3>
    <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Reset Password confirmation email','sw-ajax-login'); ?></th>
        <td><input type="checkbox" id="swal_show_password_reset_password_email" name="swal_show_password_reset_password_email" value="1" <?php echo checked( '1', $swal_show_password_reset_password_email, true ); ?>/>
            <label for="swal_show_password_reset_password_email" class="sw-right-label"><?php esc_html_e('Show new password','sw-ajax-login'); ?></label>
        <p class="description">
            <?php 
            esc_html_e('Upon successful reset password, the user will receive a new password confirmation email. ','sw-ajax-login');
            esc_html_e('By enabling this option the new password will be shown on email content.','sw-ajax-login');
             ?>
        </p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td><input type="checkbox" id="swal_add_forgot_password_icons" name="swal_add_forgot_password_icons" value="1" <?php echo checked( '1', $swal_add_forgot_password_icons, true ); ?>/>
            <label for="swal_add_forgot_password_icons" class="sw-right-label"><?php esc_html_e('Add icon to email input field','sw-ajax-login'); ?></label>
            <p class="description">
                <?php esc_html_e('By enabling this option fontawesome icons will be added to email input field.','sw-ajax-login'); ?>
            </p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Forgot password background image",'sw-ajax-login') ?></label></th>
            <td>
                <div class="gc">
                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        <?php sw_ajax_login_image_uploader( 'swal_ajax_forgot_password_background', $width = 115, $height = 115 ); ?>
                        <p class="description">
                            <?php esc_html_e('The image the will appear as background on forgot password popup if you have chosen the popup layout style with image','sw-ajax-login'); ?>
                        </p>
                    </div>
                    <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                        <h4><?php esc_html_e("Background position",'sw-ajax-login') ?></h4>
                        <div class="cc-selector">
                            <?php
                                swal_set_alignment_radiobuttons('swal_password_bg_image_alignment', $swal_password_bg_image_alignment);
                            ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Register text (optional)",'sw-ajax-login') ?></label></th>
            <td>
                <div class="wrapper-tab_content">
                        <?php
                        $settings = array(
                        'teeny' => true,
                        'tinymce' => array(
                            'external_plugins' => wp_json_encode(array(
                                                        'keyup_event' => SWAL_PLUGIN_JS . "/admin-for-tinymce.js",
                                                    ))
                        ),
                        'media_buttons' => false,
                        'editor_height' => 180,
                        'tabindex' => 1
                    );
                    wp_editor($swal_forgot_password_login, 'swal_forgot_password_login', $settings);
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_4',true,array('id' => 'submit_tab_4' )); ?>

    <?php
}


?>