<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 *
 * Add reCAPTCHA settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_recaptcha_settings' );
add_action( 'swal_admin_register_tab','swal_admin_recaptcha_settings');
 

/**
 *
 * Adds register instagram settings
 *
 */
function swal_register_recaptcha_settings() {

    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_version', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_recaptcha', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_key', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_secret_key', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_v3_key', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_v3_secret_key', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_v3_threshold', 'sanitize_text_field' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_recaptcha_theme', 'sanitize_text_field' );
}


/**
 * 
 * reCAPTCHA setting fields
 * 
 */
function swal_admin_recaptcha_settings() {

    $swal_recaptcha_version             = intval( get_option('swal_recaptcha_version', 0) );
    $swal_add_recaptcha                 = intval(get_option('swal_add_recaptcha'));
    $swal_recaptcha_key                 = esc_attr(get_option('swal_recaptcha_key'));
    $swal_recaptcha_secret_key          = esc_attr(get_option('swal_recaptcha_secret_key'));
    $swal_recaptcha_v3_key              = esc_attr(get_option('swal_recaptcha_v3_key'));
    $swal_recaptcha_v3_secret_key       = esc_attr(get_option('swal_recaptcha_v3_secret_key'));
    $swal_recaptcha_v3_threshold        = esc_attr(get_option('swal_recaptcha_v3_threshold', '0.5'));
    $swal_recaptcha_theme               = esc_attr(get_option('swal_recaptcha_theme', 'light'));


    ?>
    <tr valign="top" class="swal-disable-new-user-registration">
        <th scope="row"></th>
        <td>
          <?php
                    $args = array(
                            'id'            => 'swal_add_recaptcha',
                            'name'          => 'swal_add_recaptcha',
                            'value'         => $swal_add_recaptcha,
                            'input_value'   => 1,
                            'class'         => 'sw-showoncheck',
                            'data'          => array(
                                                'target' => 'swal-recaptcha',
                                                ),
                            'label'         => esc_html__('Add reCAPTCHA','sw-ajax-login'),
                            'label_class'   => 'sw-right-label',
                                );
                            swal_checkbox_ios_style( $args );
                            ?>
            <p class="description">
                <?php esc_html_e('reCAPTCHA helps to avoid spam users registration, we reccomend to use it.','sw-ajax-login');
                 ?>
            </p>
            <div class="gc swal-recaptcha inner-div-settings">
                <div class="sw-grid span-1 padding-td">
                    <?php 
                        $item = array(
                                    array(__('reCAPTCHA v2','sw-ajax-login'),'recaptcha-radiobuttons','swal_recaptcha_v2_settings'),
                                    array(__('reCAPTCHA v3','sw-ajax-login'),'recaptcha-radiobuttons','swal_recaptcha_v3_settings'),
                                    );
                        foreach($item as $key => $value) {
                                echo '<label class="sw-label-radio-buttons"><input type="radio" class="sw-radiobuttonshowoncheck" data-group="'.$value[1].'" data-target="'.$value[2].'" id="swal_recaptcha_version'.$key.'" name="swal_recaptcha_version" value="'.$key.'"'.checked( $swal_recaptcha_version, $key,false ).'> '.$value[0].'</label>';
                            } 
                    ?>
                </div>
            </div>
            <div class="gc swal-recaptcha inner-div-settings">

                <div class="recaptcha-radiobuttons swal_recaptcha_v2_settings">
                  <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                      <label for="swal_recaptcha_key"><?php esc_html_e('v2 Website Key','sw-ajax-login'); ?></label>
                      <input type="text" id="swal_recaptcha_key" name="swal_recaptcha_key" class="clear settings-select" value="<?php echo $swal_recaptcha_key; ?>">
                  </div>
                  <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                      <label for="swal_recaptcha_secret_key"><?php esc_html_e('v2 Secret Key','sw-ajax-login'); ?></label>
                      <input type="text" id="swal_recaptcha_secret_key" name="swal_recaptcha_secret_key" class="clear settings-select" value="<?php echo $swal_recaptcha_secret_key; ?>">
                  </div>
                  <div class="sw-grid span-1 padding-td">
                      <label for="swal_recaptcha_theme"><?php esc_html_e('Theme','sw-ajax-login'); ?></label>
                      <select name="swal_recaptcha_theme" id="swal_recaptcha_theme" class="floatL margin-right settings-select">
                          <?php
                              $item = array();
                              $item[] = __('Light','sw-ajax-login');
                              $item[] = __('Dark','sw-ajax-login');
                              foreach($item as $key => $value) {
                                      echo '<option value="'.$value.'"'.selected( $swal_recaptcha_theme, $value,false ).'>'.$value.'</option>';
                                  } 
                          ?>
                      </select>
                  </div>
                </div>

                <div class="clear recaptcha-radiobuttons swal_recaptcha_v3_settings">
                  <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                      <label for="swal_recaptcha_v3_key"><?php esc_html_e('v3 Website Key','sw-ajax-login'); ?></label>
                      <input type="text" id="swal_recaptcha_v3_key" name="swal_recaptcha_v3_key" class="clear settings-select" value="<?php echo $swal_recaptcha_v3_key; ?>">
                  </div>
                  <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                      <label for="swal_recaptcha_v3_secret_key"><?php esc_html_e('v3 Secret Key','sw-ajax-login'); ?></label>
                      <input type="text" id="swal_recaptcha_v3_secret_key" name="swal_recaptcha_v3_secret_key" class="clear settings-select" value="<?php echo $swal_recaptcha_v3_secret_key; ?>">
                  </div>
                  <div class="sw-grid span-2-3 tablet-1 mobile-1 padding-td clear">
                      <label for="swal_recaptcha_v3_threshold"><?php esc_html_e('Threshold','sw-ajax-login'); ?></label>
                      <div class="range-slider">
                        <input id="swal_recaptcha_v3_threshold" name="swal_recaptcha_v3_threshold" class="range-slider__range" type="range" value="<?php echo $swal_recaptcha_v3_threshold ?>" min="0.1" max="0.9" step="0.1">
                        <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_recaptcha_v3_threshold ?></span></span>
                      </div>
                      <p class="description">
                          <?php esc_html_e('Lower than 0.5 = Easier for bots to register, Higher than 0.5 = harder for bots, but may block some real users. We suggest to leave at 0.5 then check your Google console for fine tuning.','sw-ajax-login');
                           ?>
                      </p>
                  </div>
                </div>
                <div class="sw-grid span-1 wrapper-tab_content">
                <a href="#" class="slidedown-div" data-item="recaptcha-info"><?php esc_html_e('How to get reCaptcha keys','sw-ajax-login'); ?> (?)</a>
                    <div id="recaptcha-info" class="hide">
                        <ol class="description">
                            <li>
                                <?php esc_html_e('Login into your Google account and go to ','sw-ajax-login'); ?><a href="https://www.google.com/recaptcha" target="_blank">Google reCAPTCHA website</a>
                            </li>
                            <li><?php esc_html_e("Find and click the 'Get Recaptcha' button in the upper-right corner.",'sw-ajax-login'); ?></li>
                            <li><?php esc_html_e("In the 'Register a new site' section, enter a label in the 'Label' box, and the URL for your site, without with http://. E.X. – example.com in the Domains box. Click the Register button.", 'sw-ajax-login'); ?></li>
                            <li><?php esc_html_e("You should now see your new site key and secret key. Copy and add to the settings above.", 'sw-ajax-login'); ?></li>
                    </div>
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
    <?php
}


?>