<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


add_action('admin_init', 'swal_register_custom_logo' );
add_action('swal_settings_after_popup_layout', 'sw_ajax_login_add_logo_upload');
add_action('swal_forms_before_title', 'sw_ajax_add_custom_link');

// Adds register logo option
function swal_register_custom_logo() {
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_custom_logo', 'sanitize_file_name' );
}

// Adds image upload input to the admin settings
function sw_ajax_login_add_logo_upload() {
?>
    <tr valign="top">
    <th scope="row"><label for="swal_custom_logo"><?php esc_html_e("Custom Logo",'sw-ajax-login') ?></label></th>
        <td>
            <div class="gc">
                <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                    <?php sw_ajax_login_image_uploader( 'swal_custom_logo', $width = 115, $height = 115 ); ?>
                    <p class="description">
                        <?php esc_html_e('You can add a logo to the forms, it will appear before the forms title','sw-ajax-login'); ?>
                    </p>
                </div>
            </div>
        </td>
    </tr>
<?php
}


// Adds the logo to the forms action hook
function sw_ajax_add_custom_link() {

    //Login background image
    $options            = esc_attr(get_option('swal_custom_logo'));
    $image_attributes   = wp_get_attachment_image_src( $options, 'large');
    $src = $image_attributes[0];

    if (!$options) {
        return;
    }

    $output = '<div class="swal-forms-logo">
                    <img src="'. $src .'"/>
                </div>';

    echo $output;
}
?>