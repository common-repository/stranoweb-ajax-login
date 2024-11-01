<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Register window settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_register_window_settings' );

 

/**
 *
 * Adds Register window settings
 *
 */
function swal_register_register_window_settings() {

  // Register Window
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'users_can_register', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_register_no_email_to_user', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_register_form_type', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_no_password_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_terms_link', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_termsconditions_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_termsconditions_link_to', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_termsconditionsintro_text', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_register_icons', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_ajax_register_background', 'sanitize_file_name' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_register_bg_image_alignment', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_register_login', 'htmlentities' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_disable_username_field', 'intval' );
 // End Register Window

}


/**
 * 
 * Social login setting fields
 * 
 */
function swal_admin_register_window_settings() {

  // Register Window
    $users_can_register                  = intval(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
    $swal_register_no_email_to_user      = intval( get_option('swal_register_no_email_to_user', 0) );
    $swal_register_form_type             = intval( get_option('swal_register_form_type', 0) );
    $swal_no_password_text               = esc_attr(get_option('swal_no_password_text',__(SWAL_NO_PASSWORD_TEXT,'sw-ajax-login')));
    $swal_add_terms_link                 = intval(get_option('swal_add_terms_link'));
    $swal_termsconditions_text           = esc_attr(get_option('swal_termsconditions_text',SWAL_GDPR_CONSENT_TEXT));
    $swal_termsconditions_link_to        = intval(get_option('swal_termsconditions_link_to'));
    $swal_termsconditionsintro_text      = esc_attr(get_option('swal_termsconditionsintro_text',SWAL_GDPR_CONSENTINTRO_TEXT));
    $swal_add_register_icons             = intval(get_option('swal_add_register_icons'));
    $swal_register_bg_image_alignment    = intval(get_option('swal_register_bg_image_alignment',SWAL_REGISTER_BG_IMAGE_ALIGNMENT));
    $swal_register_login                 = html_entity_decode(strip_tags(get_option('swal_register_login')));
    $swal_disable_username_field         = intval(get_option('swal_disable_username_field'));
 // End Register Window

    $homeurl    = home_url();


    ?>
    <!-- Register Window -->
    <h3><?php esc_html_e('Register window','sw-ajax-login') ?></h3>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'users_can_register',
                    'name'          => 'users_can_register',
                    'value'         => $users_can_register,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'data'          => array(
                                        'target' => 'swal-disable-new-user-registration',
                                        ),
                    'label'         => esc_html__('Anyone can register','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
        <p class="description">
            <?php esc_html_e('By checking this option you enable new users registering on your site.','sw-ajax-login');
             ?>
        </p>
        </td>
        </tr>

        <tr valign="top" class="swal-disable-new-user-registration">
            <th scope="row"></th>
            <td>
                <?php
            $args = array(
                    'id'            => 'swal_disable_username_field',
                    'name'          => 'swal_disable_username_field',
                    'value'         => $swal_disable_username_field,
                    'input_value'   => 1,
                    'label'         => esc_html__('Disable username field','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
                <p class="description">
                    <?php esc_html_e('By enabling this option users won\'t need to add the username on registration process. The username will be automatically created from the first portion of the email address.','sw-ajax-login');
                     ?><br/>
                     <?php esc_html_e('If a username already exists, the new one will have an incremental number as suffix.','sw-ajax-login');
                     ?>

                </p>
            </td>
        </tr>

        <tr valign="top" class="swal-disable-new-user-registration">
        <th scope="row"></th>
        <td>
            <div class="gc text_long inner-div-settings">
                <div class="sw-grid span-1 padding-td">
                    <label for="swal_register_form_type"><?php esc_html_e('Form type','sw-ajax-login'); ?></label>
                    <?php 
                        $item = array(
                            array(__('Show the input password fields','sw-ajax-login'),'form-type-radiobuttons','form-type-with-pwd'),
                            array(__('Don\'t show the input password fields, password will be automatically generated and sent on email','sw-ajax-login'),'form-type-radiobuttons','form-type-without-pwd'),
                            );
                        foreach($item as $key=>$value) {
                                echo '<label class="sw-label-radio-buttons"><input type="radio" class="sw-radiobuttonshowoncheck" data-group="'.$value[1].'" data-target="'.$value[2].'" id="swal_register_form_type'.$key.'" name="swal_register_form_type" value="'.$key.'"'.checked( $swal_register_form_type, $key,false ).'> '.$value[0].'</label>';
                            } 
                    ?>
                </div>
            </div>

            <div id="swal_register_form_type_intructions" class="gc text_long inner-div-settings form-type-radiobuttons form-type-radiobuttons form-type-without-pwd">
                <div id="swal_register_form_type1" class="sw-grid span-2-3 tablet-1 mobile-1 padding-td">
                    <label for="swal_no_password_text"><?php esc_html_e('Registration instruction','sw-ajax-login'); ?></label>
                    <input placeholder="<?php esc_html_e(SWAL_NO_PASSWORD_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_no_password_text" name="swal_no_password_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_no_password_text; ?>">
                    <p class="clear description"><?php esc_html_e('Insert here what user will receive after registration.','sw-ajax-login') ?></p>
                </div>
            </div>
        </td>
        </tr>
        
        <?php
            /**
             *
             * This hook is for reCAPTCHA settings
             *
             */
            do_action('swal_admin_register_tab');
            ?>
        <tr valign="top" class="swal-disable-new-user-registration">
            <th scope="row"></th>
            <td>
                <?php
            $args = array(
                    'id'            => 'swal_register_no_email_to_user',
                    'name'          => 'swal_register_no_email_to_user',
                    'value'         => $swal_register_no_email_to_user,
                    'input_value'   => 1,
                    'label'         => esc_html__('Don\'t send email to user after registration','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
                <p class="description">
                    <?php esc_html_e('By enabling this option users won\'t receive the confirmation email from our plugin, this can be usefull if you are using a third party plugin for new user activation.','sw-ajax-login');
                     ?>
                </p>
            </td>
        </tr>
    </table>

    <table class="form-table" class="swal-disable-new-user-registration">
    <h3 class="swal-disable-new-user-registration"><?php esc_html_e('GDPR consent','sw-ajax-login') ?></h3>
        <tr valign="top" class="swal-disable-new-user-registration">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_add_terms_link',
                    'name'          => 'swal_add_terms_link',
                    'value'         => $swal_add_terms_link,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'data'          => array(
                                        'target' => 'swal-termsconditions-link',
                                        ),
                    'label'         => esc_html__('Add GDPR consent checkbox','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description">
                <?php esc_html_e('This option adds a mandatory checkbox for GDPR consent with a link to Privacy Policy page.','sw-ajax-login');
                 ?>
            </p>
            <div id="swal-termsconditions-link" class="clear text_long padding-top swal-termsconditions-link">
                <div class="gc inner-div-settings">
                    <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                        <label for="swal_termsconditions_text"><?php esc_html_e('GDPR consent text','sw-ajax-login'); ?></label>
                        <input placeholder="<?php esc_html_e(SWAL_GDPR_CONSENT_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_termsconditions_text" name="swal_termsconditions_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_termsconditions_text; ?>">
                        <p class="clear description"><?php esc_html_e('Insert here the text to get user consent on form submition.','sw-ajax-login') ?></p>
                    </div>
                </div>

                <div class="gc inner-div-settings">
                    <div class="sw-grid span-1-3 tablet-1 mobile-1 padding-td">
                        <label for="swal_termsconditions_link_to"><?php esc_html_e('Privacy page','sw-ajax-login'); ?></label>
                        <?php 
                        $args = array(
                            'depth'                 => 0,
                            'child_of'              => 0,
                            'selected'              => $swal_termsconditions_link_to,
                            'echo'                  => 1,
                            'name'                  => 'swal_termsconditions_link_to',
                            'id'                    => null,
                            'class'                 => 'clear regular-text text_long settings-select',
                            'show_option_none'      => __('--- None ---','sw-ajax-login'),
                            'show_option_no_change' => null,
                            'option_none_value'     => null,
                            );
                        wp_dropdown_pages($args); ?>
                        <p class="clear description"><?php esc_html_e('A link to privacy page will be appended to the consent message.','sw-ajax-login') ?></p>
                    </div>
                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        <label for="swal_termsconditionsintro_text"><?php esc_html_e('Link text','sw-ajax-login'); ?></label>
                        <input placeholder="<?php esc_html_e(SWAL_GDPR_CONSENTINTRO_TEXT,'sw-ajax-login'); ?>" type="text" id="swal_termsconditionsintro_text" name="swal_termsconditionsintro_text" class="regular-text text_long wrapper-tab_content" value="<?php echo $swal_termsconditionsintro_text; ?>">
                        <p class="clear description"><?php esc_html_e('Insert here privacy link text if you want it different from the page name.','sw-ajax-login') ?></p>
                    </div>
                </div>
            </div>
        </td>
        </tr>
    </table>
    
    <table class="form-table" class="swal-disable-new-user-registration">
    <h3 class="swal-disable-new-user-registration"><?php esc_html_e('Appearance','sw-ajax-login') ?></h3>
        <tr valign="top" class="swal-disable-new-user-registration">
        <th scope="row"></th>
        <td>
            <?php
            $args = array(
                    'id'            => 'swal_add_register_icons',
                    'name'          => 'swal_add_register_icons',
                    'value'         => $swal_add_register_icons,
                    'input_value'   => 1,
                    'label'         => esc_html__('Add icons to registration input fields','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
            <p class="description">
                <?php esc_html_e('By enabling this option fontawesome icons will be added to new user registration input fields.','sw-ajax-login'); ?>
            </p>
        </td>
        </tr>

        <tr valign="top" class="swal-disable-new-user-registration">
        <th scope="row"><label for="swal_list_columns"><?php esc_html_e("Register background image",'sw-ajax-login') ?></label></th>
            <td>
                <div class="gc">
                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        <?php sw_ajax_login_image_uploader( 'swal_ajax_register_background', $width = 115, $height = 115 ); ?>
                        <p class="description">
                            <?php esc_html_e('The image the will appear as background on register popup if you have chosen the popup layout style with image','sw-ajax-login'); ?>
                        </p>
                    </div>
                    <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                        <h4><?php esc_html_e("Background position",'sw-ajax-login') ?></h4>
                        <div class="cc-selector">
                            <?php
                                swal_set_alignment_radiobuttons('swal_register_bg_image_alignment', $swal_register_bg_image_alignment);
                            ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr valign="top" class="swal-disable-new-user-registration">
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
                    wp_editor($swal_register_login, 'swal_register_login', $settings);
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_3',true,array('id' => 'submit_tab_3' )); ?>

    <?php
}


?>