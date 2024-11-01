<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 *
 * Add Login Window settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_login_window_settings' );

 

/**
 *
 * Adds Login Window settings
 *
 */
function swal_register_login_window_settings() {

  // Login Window
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_login_remember_credentials', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_min_password_length', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_login_icons', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_ajax_login_background', 'sanitize_file_name' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_login_bg_image_alignment', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_text_login', 'htmlentities' );

  // End Login Window

}


/**
 * 
 * Social login setting fields
 * 
 */
function swal_admin_login_window_settings() {

  // Login Window
    $swal_login_remember_credentials     = intval(get_option('swal_login_remember_credentials',SWAL_LOGIN_REMEMBER_CREDENTIALS));
    $swal_min_password_length            = intval(get_option('swal_min_password_length',SWAL_MIN_PASSWORD_LENGTH));
    $swal_add_login_icons                = intval(get_option('swal_add_login_icons'));
    $swal_login_bg_image_alignment       = intval(get_option('swal_login_bg_image_alignment',SWAL_LOGIN_BG_IMAGE_ALIGNMENT));
    $swal_text_login                     = html_entity_decode(strip_tags(get_option('swal_text_login')));
  // End Login Window

    $homeurl    = home_url();


    ?>
    <!-- Login Window -->
    <h3><?php esc_html_e('Login window','sw-ajax-login') ?></h3>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><label for="swal_login_remember_credentials"><?php esc_html_e('Remember credentials','sw-ajax-login'); ?></label></th>
        <td>
            <select name="swal_login_remember_credentials" id="swal_login_remember_credentials" class="settings-select">
                <?php
                    $item = array();
                    $item[] = __("Always",'sw-ajax-login');
                    $item[] = __("Never",'sw-ajax-login');
                    $item[] = __("Show checkbox",'sw-ajax-login');
                    foreach($item as $key => $value) {
                            echo '<option value="'.$key.'"'.selected( $swal_login_remember_credentials, $key,false ).'>'.$value.'</option>';
                        } 
                ?>
            </select>
            <p class="description">
                    <?php esc_html_e('You can choose if website remembers user\'s credentials, never or shows a checkbox to let the user decide.','sw-ajax-login'); ?>
                </p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="swal_min_password_length"><?php esc_html_e("Minimum password length",'sw-ajax-login') ?></label></th>
        <td>
            <select name="swal_min_password_length" id="swal_min_password_length" class="settings-select">
                <?php 
                    for ($n = 0; $n <= 10; $n+=1) {
                            echo '<option value="'.$n.'"'.selected( $swal_min_password_length, $n,false ).'>'.$n.'</option>';
                        } 
                ?>
            </select>
            <p class="description">
                    <?php esc_html_e('Select the minimum password length, 0 means no minimum length required.','sw-ajax-login'); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td><input type="checkbox" id="swal_add_login_icons" name="swal_add_login_icons" value="1" <?php echo checked( '1', $swal_add_login_icons, true ); ?>/>
            <label for="swal_add_login_icons" class="sw-right-label"><?php esc_html_e('Add icons to login input fields','sw-ajax-login'); ?></label>
            <p class="description">
                <?php esc_html_e('By enabling this option fontawesome icons will be added to username and password input fields.','sw-ajax-login'); ?>
            </p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Login background image",'sw-ajax-login') ?></label></th>
            <td>
                <div class="gc">
                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        <?php sw_ajax_login_image_uploader( 'swal_ajax_login_background', $width = 115, $height = 115 ); ?>
                        <p class="description">
                            <?php esc_html_e('The image the will appear as background of login popup if you have chosen the popup layout style with image','sw-ajax-login'); ?>
                        </p>
                    </div>
                    <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                        <h4><?php esc_html_e("Background position",'sw-ajax-login') ?></h4>
                        <div class="cc-selector">
                            <?php
                                swal_set_alignment_radiobuttons('swal_login_bg_image_alignment', $swal_login_bg_image_alignment);
                            ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Login text (optional)",'sw-ajax-login') ?></label></th>
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
                    wp_editor($swal_text_login, 'swal_text_login', $settings);
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_2',true,array('id' => 'submit_tab_2' )); ?>

    <?php
}

?>