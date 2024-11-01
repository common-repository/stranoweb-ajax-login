<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Logout Window settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_logout_window_settings' );

 

/**
 *
 * Adds register Logout Window settings
 *
 */
function swal_register_logout_window_settings() {

  // Logout Window Window
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_ajax_logout_background', 'sanitize_file_name' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logout_login', 'htmlentities' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_overlay_logout', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logout_overlay_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logout_overlay_opacity', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logout_bg_image_alignment', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_logout_direct', 'intval' );
  // End Logout Window Window

}


/**
 * 
 * Logout Window setting fields
 * 
 */
function swal_admin_logout_window_settings() {

  // Logout Window Window
    $swal_add_overlay_logout             = intval(get_option('swal_add_overlay_logout'));
    $swal_logout_overlay_color           = sanitize_hex_color(get_option('swal_logout_overlay_color',SWAL_LOGOUT_OVERLAY_COLOR));
    $swal_logout_overlay_opacity         = intval(get_option('swal_logout_overlay_opacity',SWAL_OVERLAY_OPACITY));
    $swal_logout_bg_image_alignment      = intval(get_option('swal_logout_bg_image_alignment',SWAL_LOGOUT_BG_IMAGE_ALIGNMENT));
    $swal_logout_login                   = html_entity_decode(strip_tags(get_option('swal_logout_login',__('Do you want to logout?','sw-ajax-login'))));
    $swal_logout_direct                  = intval(get_option('swal_logout_direct'));
  // End Logout Window Window

    $homeurl    = home_url();


    ?>
    <!-- Logout Window -->
    <h3><?php esc_html_e('Logout window','sw-ajax-login') ?></h3>
    <table class="form-table">

        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_logout_direct',
                    'name'          => 'swal_logout_direct',
                    'value'         => $swal_logout_direct,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'label'         => esc_html__('Logout without confirmation popup','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description wrapper-tab_content">
                <?php esc_html_e('Enabling this option the popup won\'t open and the user will be directly logged out.' ,'sw-ajax-login');
                 ?>
            </p>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Logout background image",'sw-ajax-login') ?></label></th>
            <td>
                <div class="gc">
                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        <?php sw_ajax_login_image_uploader( 'swal_ajax_logout_background', $width = 115, $height = 115 ); ?>
                        <p class="description">
                            <?php esc_html_e('The image the will appear as background on logout popup if you have chosen the popup layout style with image','sw-ajax-login'); ?>
                        </p>
                    </div>
                    <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                        <h4><?php esc_html_e("Background position",'sw-ajax-login') ?></h4>
                        <div class="cc-selector">
                            <?php
                                swal_set_alignment_radiobuttons('swal_logout_bg_image_alignment', $swal_logout_bg_image_alignment);
                            ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td><input type="checkbox" class="sw-showoncheck" data-target="swal-logout-overlay-setting" id="swal_add_overlay_logout" name="swal_add_overlay_logout" value="1" <?php echo checked( '1', $swal_add_overlay_logout, true ); ?>/>
            <label for="swal_add_overlay_logout" class="sw-right-label"><?php esc_html_e('Customize logout image overlay','sw-ajax-login'); ?></label>
        <p class="description">
            <?php esc_html_e('By enabling this option you can set a different overlay color and opacity to logout window','sw-ajax-login'); ?>
        </p>
        <div class="swal-logout-overlay-setting inner-div-settings">
            <div class="gc">
                <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                    <label for="swal_logout_overlay_color"><?php esc_html_e('Overlay color','sw-ajax-login'); ?></label>
                    <input type="text" id="swal_logout_overlay_color" name="swal_logout_overlay_color" class="swal-colorpicker" value="<?php echo $swal_logout_overlay_color; ?>">
                </div>
                <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                    <label for="swal_logout_overlay_opacity"><?php esc_html_e('Opacity','sw-ajax-login'); ?></label>
                    <div class="range-slider">
                      <input id="swal_logout_overlay_opacity" name="swal_logout_overlay_opacity" class="range-slider__range" type="range" value="<?php echo $swal_logout_overlay_opacity ?>" min="0" max="100" step="5">
              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_logout_overlay_opacity ?></span> %</span>
                    </div>
                </div>
            </div>
        </div>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Logout text (optional)",'sw-ajax-login') ?></label></th>
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
                    wp_editor($swal_logout_login, 'swal_logout_login', $settings);
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_5',true,array('id' => 'submit_tab_5' )); ?>
    <?php
}


?>