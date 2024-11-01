<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Apparence settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_apparence_settings' );



/**
 *
 * Adds register Apparence settings
 *
 */
function swal_register_apparence_settings() {

  // Apparence
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_layout_style', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_animation', 'sanitize_html_class' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_customize_popup', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_simple_layout_width', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_double_layout_width', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_text_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_image_text_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_secondary_text_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_link_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_buttons_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_overlay_login', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_overlay_color', 'sanitize_hex_color' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_overlay_opacity', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_additional_css', 'swal_notify_import_rules_stripped' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_close_icon_position', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_button_style', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_input_fields_style', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_popup_border_radius', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_button_border_radius', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_input_border_radius', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_button_height', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_input_height', 'intval' );

  // End Apparence

}


/**
 * 
 * Apparence setting fields
 * 
 */
function swal_admin_apparence_settings() {

  // Apparence
    $swal_popup_layout_style             = intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
    $swal_popup_animation                = sanitize_html_class(get_option('swal_popup_animation'));
    $swal_customize_popup                = intval(get_option('swal_customize_popup'));
    $swal_popup_color                    = get_option('swal_popup_color') ? sanitize_hex_color(get_option('swal_popup_color')) : SWAL_POPUP_COLOR;
    $swal_simple_layout_width            = intval(get_option('swal_simple_layout_width',SWAL_SIMPLE_LAYOUT_WIDTH));
    $swal_double_layout_width            = intval(get_option('swal_double_layout_width',SWAL_DOUBLE_LAYOUT_WIDTH));
    $swal_popup_text_color               = get_option('swal_popup_text_color') ? sanitize_hex_color(get_option('swal_popup_text_color')) : SWAL_POPUP_TEXT_COLOR;
    $swal_popup_image_text_color         = get_option('swal_popup_image_text_color') ? sanitize_hex_color(get_option('swal_popup_image_text_color')) : SWAL_POPUP_IMAGE_TEXT_COLOR;
    $swal_popup_secondary_text_color     = get_option('swal_popup_secondary_text_color') ? sanitize_hex_color(get_option('swal_popup_secondary_text_color')) : SWAL_POPUP_SECONDARY_TEXT_COLOR;
    $swal_link_color                     = get_option('swal_link_color') ? sanitize_hex_color(get_option('swal_link_color')) : SWAL_LINK_COLOR;
    $swal_buttons_color                  = get_option('swal_buttons_color') ? sanitize_hex_color(get_option('swal_buttons_color')) : SWAL_LINK_COLOR;
    $swal_add_overlay_login              = intval(get_option('swal_add_overlay_login',SWAL_ADD_OVERLAY_LOGIN));
    $swal_overlay_color                  = get_option('swal_overlay_color') ? sanitize_hex_color(get_option('swal_overlay_color')) : SWAL_OVERLAY_COLOR;
    $swal_overlay_opacity                = intval(get_option('swal_overlay_opacity',SWAL_OVERLAY_OPACITY));
    $swal_additional_css                 = esc_attr(get_option('swal_additional_css'));
    $swal_popup_close_icon_position      = intval(get_option('swal_popup_close_icon_position'));
    $swal_button_style                   = intval(get_option('swal_button_style'));
    $swal_input_fields_style             = intval(get_option('swal_input_fields_style'));
    $swal_popup_border_radius            = get_option('swal_popup_border_radius') ? intval(get_option('swal_popup_border_radius')) : SWAL_POPUP_BORDER_RADIUS;
    $swal_button_border_radius           = get_option('swal_button_border_radius') ? intval(get_option('swal_button_border_radius')) : SWAL_BUTTON_BORDER_RADIUS;
    $swal_input_border_radius            = get_option('swal_input_border_radius') ? intval(get_option('swal_input_border_radius')) : SWAL_INPUT_BORDER_RADIUS;
    $swal_button_height                  = get_option('swal_button_height') ? intval(get_option('swal_button_height')) : SWAL_BUTTON_HEIGHT;
    $swal_input_height                   = get_option('swal_input_height') ? intval(get_option('swal_input_height')) : SWAL_INPUT_HEIGHT;

  // End Apparence


    ?>
     <!-- Popup apparence -->
    <h3><?php esc_html_e('Popup apparence','sw-ajax-login') ?></h3>
            <table class="form-table">

                <tr valign="top">
                <th scope="row"><label for="swal_popup_layout_style"><?php esc_html_e("Popup layout style",'sw-ajax-login') ?></label></th>
                <td>
                    <div class="cc-selector">
                        <?php 

                        $item = array(
                                    array(__('Simple (no image)','sw-ajax-login'),'layout-radiobuttons','swal_simple_layout_width'),
                                    );
                            foreach($item as $key => $value) {
                                    echo '<input type="radio" class="sw-radiobuttonshowoncheck" data-group="'.$value[1].'" data-target="'.$value[2].'" id="swal_popup_layout_style0" name="swal_popup_layout_style" value="0"'.checked( $swal_popup_layout_style, '0',false ).'>
                                    <label class="drinkcard-cc swal_popup_layout_style0" for="swal_popup_layout_style0">'.$value[0].'</label>';
                                }

                        $item = array(
                                    array(__('Simple (with background image)','sw-ajax-login'),'layout-radiobuttons','swal_simple_layout_width'),
                                    );
                            foreach($item as $key => $value) {
                                    echo '<div class="drinkcard-cc swal_popup_layout_style1" for="swal_popup_layout_style1">'.$value[0].'</div>';
                                }

                        $item = array(
                                    array(__('Double column (Image on the left)','sw-ajax-login'),'layout-radiobuttons','swal_double_layout_width'),
                                    );
                            foreach($item as $key => $value) {
                                    echo '<input type="radio" class="sw-radiobuttonshowoncheck" data-group="'.$value[1].'" data-target="'.$value[2].'" id="swal_popup_layout_style2" name="swal_popup_layout_style" value="2"'.checked( $swal_popup_layout_style, '2',false ).'>
                                    <label class="drinkcard-cc swal_popup_layout_style2" for="swal_popup_layout_style2">'.$value[0].'</label>';
                                }

                            $item = array(
                                    array(__('<span class="sw-premium-text">Premium</span> - Double column (Image on the right)','sw-ajax-login'),'layout-radiobuttons','swal_double_layout_width'),
                                    array(__('<span class="sw-premium-text">Premium</span> - Double column (Image on the right)','sw-ajax-login'),'layout-radiobuttons','swal_double_layout_width'),
                                    array(__('<span class="sw-premium-text">Premium</span> - Double column (Full background image, form on left)','sw-ajax-login'),'layout-radiobuttons','swal_double_layout_width'),
                                    array(__('<span class="sw-premium-text">Premium</span> - Login & Register tabs','sw-ajax-login'),'layout-radiobuttons','swal_simple_layout_width'),
                                    array(__('<span class="sw-premium-text">Premium</span> - Login & Register tabs (with background image)','sw-ajax-login'),'layout-radiobuttons','swal_simple_layout_width'),
                                    );
                            foreach($item as $key => $value) {
                                $newkey = intval($key)+4;
                                    echo '<div class="drinkcard-cc swal_popup_layout_style'.$newkey.'" for="swal_popup_layout_style'.$newkey.'">'.$value[0].'</div>';
                                }

                        ?>
                    </div>
                    </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_popup_animation"><?php esc_html_e("Opening animation",'sw-ajax-login') ?></label></th>
                    <td>
                        <div class="gc">
                            <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                                <?php 
                                    $item = array(
                                            array(__('Fade','sw-ajax-login'),''),
                                            array(__('Zoom in','sw-ajax-login'),'swal-zoom-in'),
                                            array(__('Slide down','sw-ajax-login'),'swal-slide-down'),
                                            );
                                    foreach($item as $key => $value) {
                                            echo '<label class="sw-label-radio-buttons" for="swal_popup_animation'.$key.'"><input type="radio" id="swal_popup_animation'.$key.'" name="swal_popup_animation" value="'.$value[1].'"'.checked( $swal_popup_animation, $value[1],false ).'>
                                            '.$value[0].'</label>';
                                        } 
                                ?>
                                <p class="description sw-premium-text">
                                    <?php esc_html_e('More effects available on Premium version','sw-ajax-login'); ?>
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr valign="top">
                <th scope="row"></th>
                <td><?php
                $args = array(
                    'id'            => 'swal_customize_popup',
                    'name'          => 'swal_customize_popup',
                    'value'         => $swal_customize_popup,
                    'input_value'   => 1,
                    'class'         => 'sw-showoncheck',
                    'data'          => array(
                                        'target' => 'customize-popup',
                                        ),
                    'label'         => esc_html__('Customize popup and main overlay','sw-ajax-login'),
                    'label_class'   => 'sw-right-label',
                        );
                    swal_checkbox_ios_style( $args );
                    ?>
                <p class="description">
                    <?php esc_html_e('Customize Popup width & color and overlay color & opacity','sw-ajax-login'); ?>
                </p>
                <div class="customize-popup inner-div-settings">
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_popup_color"><?php esc_html_e('Popup color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_popup_color" name="swal_popup_color" class="clear swal-colorpicker  settings-select" value="<?php echo $swal_popup_color; ?>">
                        </div>

                        <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td layout-radiobuttons swal_simple_layout_width">
                            <label for="swal_simple_layout_width" class="sw-right-label"><?php esc_html_e('Popup width','sw-ajax-login'); ?></label><?php swal_reset_value_button(); ?>
                            <div class="range-slider">
                              <input id="swal_simple_layout_width" name="swal_simple_layout_width" class="range-slider__range" type="range" value="<?php echo $swal_simple_layout_width ?>" min="340" max="640" step="2" data-default="<?php echo SWAL_SIMPLE_LAYOUT_WIDTH; ?>">
                              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_simple_layout_width ?></span> px</span>
                            </div>
                        </div>

                         <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td layout-radiobuttons swal_double_layout_width">
                            <label for="swal_double_layout_width" class="sw-right-label"><?php esc_html_e('Popup width','sw-ajax-login'); ?></label>
                            <div class="range-slider">
                              <input id="swal_double_layout_width" name="swal_double_layout_width" class="range-slider__range" type="range" value="<?php echo $swal_double_layout_width ?>" min="800" max="960" step="2">
                              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_double_layout_width ?></span> px</span>
                            </div>
                        </div>
                    </div>

                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        </div>
                        <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_popup_border_radius" class="sw-right-label"><?php esc_html_e('Popup border radius','sw-ajax-login'); ?></label><?php swal_reset_value_button(); ?>
                            <div class="range-slider">
                              <input id="swal_popup_border_radius" name="swal_popup_border_radius" class="range-slider__range" type="range" value="<?php echo $swal_popup_border_radius ?>" min="0" max="100" step="1" data-default="<?php echo SWAL_POPUP_BORDER_RADIUS; ?>">
                              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_popup_border_radius ?></span> px</span>
                            </div>
                        </div>
                    </div>

                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_main_overlay_color"><?php esc_html_e('Main overlay color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                        <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_main_overlay_opacity" class="sw-right-label"><?php esc_html_e('Opacity','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                    </div>


                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_popup_secondary_text_color"><?php esc_html_e('Text color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_popup_text_color" name="swal_popup_text_color" class="clear swal-colorpicker  settings-select" value="<?php echo $swal_popup_text_color; ?>">
                        </div>
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_popup_image_text_color"><?php esc_html_e('Text over images color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_popup_image_text_color" name="swal_popup_image_text_color" class="clear swal-colorpicker  settings-select" value="<?php echo $swal_popup_image_text_color; ?>">
                        </div>
                    </div>

                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_popup_secondary_text_color"><?php esc_html_e('Secondary text color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_popup_secondary_text_color" name="swal_popup_secondary_text_color" class="clear swal-colorpicker  settings-select" value="<?php echo $swal_popup_secondary_text_color; ?>">
                        </div>
                    </div>

                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_link_color"><?php esc_html_e('Link color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_link_color" name="swal_link_color" class="clear swal-colorpicker  settings-select" value="<?php echo $swal_link_color; ?>">
                        </div>
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                        </div>
                    </div>
                </div>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row"><label for="swal_popup_close_icon_position"><?php esc_html_e("Close button position:",'sw-ajax-login') ?></label></th>
                <td>
                        <?php 
                            $item = array();
                            $item[] = __("Inside the popup",'sw-ajax-login');
                            $item[] = __("Outside the popup",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<label class="sw-label-radio-buttons"><input type="radio" id="swal_popup_close_icon_position'.$key.'" name="swal_popup_close_icon_position" value="'.$key.'"'.checked( $swal_popup_close_icon_position, $key,false ).'> '.$value.'</label>';
                                } 
                        ?>
                        <p class="clear description">
                        <?php esc_html_e('Note: Inside the popup doesn\'t work on tabs layout where the close button is always outside the popup.' ,'sw-ajax-login');
                         ?></p>
                     </td>
                 </tr>

                <tr valign="top" class="customize-popup">
                <th scope="row"><label for="swal_input_fields_style"><?php esc_html_e("Input fields style",'sw-ajax-login') ?></label></th>
                <td>
                    <div class="inner-div-settings">
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1 mobile-1 padding-td">
                            <?php 
                                $item = array();
                                $item[] = __("Inner shadow",'sw-ajax-login');
                                $item[] = __("Flat",'sw-ajax-login');
                                foreach($item as $key => $value) {
                                        echo '<label class="sw-label-radio-buttons"><input type="radio" id="swal_input_fields_style'.$key.'" name="swal_input_fields_style" value="'.$key.'"'.checked( $swal_input_fields_style, $key,false ).'> '.$value.'</label>';
                                    } 
                            ?>
                        </div>
                        <div class="sw-grid span-2-3 tablet-1 mobile-1 padding-td">
                            <label for="swal_input_border_radius" class="sw-right-label"><?php esc_html_e('Input border radius','sw-ajax-login'); ?></label><?php swal_reset_value_button(); ?>
                            <div class="range-slider">
                              <input id="swal_input_border_radius" name="swal_input_border_radius" class="range-slider__range" type="range" value="<?php echo $swal_input_border_radius ?>" min="0" max="100" step="1" data-default="<?php echo SWAL_INPUT_BORDER_RADIUS; ?>">
                              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_input_border_radius ?></span> px</span>
                            </div>
                        </div>
                    </div>
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_input_text_color"><?php esc_html_e('Input text color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_input_background_color"><?php esc_html_e('Input background color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_input_border_color"><?php esc_html_e('Input border color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_input_focus_color"><?php esc_html_e('Input focus color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                    </div>
                    </div>
                    </td>
                 </tr>

                <tr valign="top" class="customize-popup">
                <th scope="row"><label for="swal_button_style"><?php esc_html_e("Button",'sw-ajax-login') ?></label></th>
                <td>
                    <div class="inner-div-settings">
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1 mobile-1 padding-td">
                            <?php 
                                $item = array();
                                $item[] = __("Embossed",'sw-ajax-login');
                                $item[] = __("Flat",'sw-ajax-login');
                                foreach($item as $key => $value) {
                                        echo '<label class="sw-label-radio-buttons"><input type="radio" id="swal_button_style'.$key.'" name="swal_button_style" value="'.$key.'"'.checked( $swal_button_style, $key,false ).'> '.$value.'</label>';
                                    } 
                            ?>
                        </div>
                        <div class="sw-grid span-2-3 tablet-1 mobile-1 padding-td">
                            <label for="swal_button_border_radius" class="sw-right-label"><?php esc_html_e('Button border radius','sw-ajax-login'); ?></label><?php swal_reset_value_button(); ?>
                            <div class="range-slider">
                              <input id="swal_button_border_radius" name="swal_button_border_radius" class="range-slider__range" type="range" value="<?php echo $swal_button_border_radius ?>" min="0" max="100" step="1" data-default="<?php echo SWAL_BUTTON_BORDER_RADIUS; ?>">
                              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_button_border_radius ?></span> px</span>
                            </div>
                        </div>
                    </div>
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_buttons_color"><?php esc_html_e('Buttons color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_buttons_color" name="swal_buttons_color" class="clear swal-colorpicker  settings-select" value="<?php echo $swal_buttons_color; ?>">
                        </div>
                        <div class="sw-grid span-2-3 tablet-1 mobile-1 padding-td">
                            <label for="swal_button_height" class="sw-right-label"><?php esc_html_e('Button height','sw-ajax-login'); ?></label><?php swal_reset_value_button(); ?>
                            <div class="range-slider">
                              <input id="swal_button_height" name="swal_button_height" class="range-slider__range" type="range" value="<?php echo $swal_button_height ?>" min="0" max="100" step="1" data-default="<?php echo SWAL_BUTTON_HEIGHT; ?>">
                              <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_button_height ?></span> px</span>
                            </div>
                        </div>
                    </div>
                    </div>
                    </td>
                 </tr>

                 <tr valign="top">
                <th scope="row"><label for="swal_loader_background_color"><?php esc_html_e('Loader','sw-ajax-login'); ?></label></th>
                <td>
                <div class="inner-div-settings">

                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1">
                            <label for="swal_loader_background_color"><?php esc_html_e('Loader background color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1">
                            <label for="swal_loader_text_color"><?php esc_html_e('Loader text color','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="gc">
                        <div class="sw-grid span-2-3 tablet-1 mobile-1 padding-td">
                            <label for="swal_loader_persistence" class="sw-right-label"><?php esc_html_e('Loader fades out after','sw-ajax-login'); ?></label>
                            <p class="description sw-premium-text">
                                <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                </td>
                </tr>
                 <?php
                    /**
                     *
                     * This hook fires after popup layout and before image overlay setting
                     *
                     */
                    do_action('swal_settings_after_popup_layout');
                ?>
                <tr valign="top">
                <th scope="row"></th>
                <td>
                    <?php
                        $args = array(
                            'id'            => 'swal_add_overlay_login',
                            'name'          => 'swal_add_overlay_login',
                            'value'         => $swal_add_overlay_login,
                            'input_value'   => 1,
                            'class'         => 'sw-showoncheck',
                            'data'          => array(
                                                'target' => 'swal-overlay-setting',
                                                ),
                            'label'         => esc_html__('Customize popup and main overlay','sw-ajax-login'),
                            'label_class'   => 'sw-right-label',
                                );
                            swal_checkbox_ios_style( $args );
                    ?>
                <p class="description">
                    <?php esc_html_e('Overlay is helpfull to make the text more readable','sw-ajax-login'); ?>
                </p>
                <div class="swal-overlay-setting inner-div-settings">
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_overlay_color"><?php esc_html_e('Overlay color','sw-ajax-login'); ?></label>
                            <input type="text" id="swal_overlay_color" name="swal_overlay_color" class="swal-colorpicker" value="<?php echo $swal_overlay_color; ?>">
                        </div>
                        <div class="sw-grid span-2-3 tablet-1-2 mobile-1 padding-td">
                            <label for="swal_overlay_opacity" class="sw-right-label"><?php esc_html_e('Opacity','sw-ajax-login'); ?></label><?php swal_reset_value_button(); ?>
                            <div class="range-slider">
                              <input id="swal_overlay_opacity" name="swal_overlay_opacity" class="range-slider__range" type="range" value="<?php echo $swal_overlay_opacity ?>" min="0" max="100" step="5" data-default="<?php echo SWAL_OVERLAY_OPACITY; ?>">
                      <span class="range-slider__value-wrapper"><span class="range-slider__value"><?php echo $swal_overlay_opacity ?></span> %</span>
                            </div>
                        </div>
                    </div>
                </div>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_additional_css"><?php esc_html_e('Custom CSS','sw-ajax-login'); ?></label></th>
                <td>
                    <textarea id="swal_additional_css" name="swal_additional_css" rows="10" class="settings-textarea"><?php echo $swal_additional_css ?></textarea>
                    <p class="description">
                        <?php esc_html_e('You can add additional css here. For security reasons, css comments, @import and @charset are removed.','sw-ajax-login');
                         ?>
                    </p>
                </td>
                </tr>
            </table>
            <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_1',true,array('id' => 'submit_tab_1' )); ?>

            <!-- Premium Advise -->
            <div class="clear description-section">
                <h3><?php esc_html_e('Different layout models','sw-ajax-login'); ?></h3>
                <p><?php esc_html_e('Need a different layout for your website or more custom settings?','sw-ajax-login');
                 ?><br/>
                 <?php esc_html_e('With Premium version you\'ll get all the available layouts and all the StranoWeb Ajax Login features!','sw-ajax-login');
                 ?></p>
                 <div>
                    <div class="swal-premium-button left">
                        <a href="<?php echo esc_html(SWAL_PREMIUM_BUY_LINK) ?>" target="_blank"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> <?php esc_html_e('Upgrade to the Premium version', 'sw-ajax-login') ?></a>
                    </div>
                </div>
            </div>
    <?php
}


?>