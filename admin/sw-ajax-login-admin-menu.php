<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Menu settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_menu_settings' );


 
/**
 *
 * Append the Login Menu item to selected menus when options are updated
 *
 */
add_action( 'updated_option', 'swal_update_deprecated_login_menu_items', 10, 3);

function swal_update_deprecated_login_menu_items($option_name, $old_value, $value) {

    // If none of the following options has been modified then return
    $options = array(
                    'swal_menu_to_append',
                    'swal_menu_item_text',
                    'swal_menu_item_custom_text',
                    'swal_login_intro_text_link',
                    'swal_pagina_account_default',
                    'swal_menu_item_logout_text',
                    'swal_menu_item_logout_custom_text',
                    );
    if (!in_array( $option_name, $options )) {
        return;
    }

    swal_add_login_menu_item_to_menus();

}


/**
 *
 * New Menus update from version 1.6.1, Update Login menu items and append to them the old submenu
 *
 */
add_action( 'admin_init', 'swal_move_deprecated_login_submenu_items', 10);

function swal_move_deprecated_login_submenu_items() {

    if (!isset($_GET['swal_nonce']) || !wp_verify_nonce($_GET['swal_nonce'], 'swal_updating_menus')) {
        return;
    }

    if (!isset($_GET['swal_update_menus']) || (isset($_GET['swal_update_menus']) && $_GET['swal_update_menus'] != 'true')) {
        return;
    }
 
    $x = swal_add_login_menu_item_to_menus();

    /*
     * If the deprecated submenu has been moved then delete from the old location
     */ 
    if ($x) {
        swal_delete_menu_items_from_old_submenu();
    }

   
}



/**
 *
 * Adds register Menu settings
 *
 */
function swal_register_menu_settings() {

  // Logged menu
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_menu_to_append', 'swal_sanitize_array_text_field'); // This is an array
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_menu_item_text', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_user_thumbnail_style', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_menu_item_style', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_menu_item_link_to', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_menu_item_custom_link_to', 'sanitize_text_field' );
  // End Logged menu

}


/**
 * 
 * Menu setting fields
 * 
 */
function swal_admin_menu_settings() {

  // Logged menu
    $swal_menu_to_append                 = is_array(get_option('swal_menu_to_append')) ? array_map('esc_attr',get_option('swal_menu_to_append')) : esc_attr(get_option('swal_menu_to_append'));
    $swal_menu_item_text                 = intval(get_option('swal_menu_item_text',SWAL_MENU_ITEM_TEXT));
    $swal_user_thumbnail_style           = intval(get_option('swal_user_thumbnail_style',SWAL_USER_THUMBNAIL_STYLE_DEFAULT));
    $swal_menu_item_style                = intval(get_option('swal_menu_item_style',SWAL_MENU_ITEM_STYLE));
    $swal_menu_item_link_to              = intval(get_option('swal_menu_item_link_to',SWAL_MENU_ITEM_LINK_TO));
    $swal_menu_item_custom_link_to       = esc_attr(get_option('swal_menu_item_custom_link_to'));
    $swal_menu_item_custom_link_to       = ltrim($swal_menu_item_custom_link_to, '/');
  // End Logged menu

    $homeurl    = home_url();


    ?>
    <!-- Logged menu -->
    <h3><?php esc_html_e('Menu', 'sw-ajax-login') ?></h3>
            <table class="form-table">

                 <tr valign="top">
                <th scope="row"><label for="swal_menu_to_append"><?php esc_html_e('Add Login menu item to:','sw-ajax-login'); ?></label></th>
                <td>
                    <?php 
                        
                        $menus = wp_get_nav_menus();
                        $check_menu_exists = true;
                        $submenu_name = '';

                        // check if the Login item submenu location has the menu,
                        // if yes doens't show on the list to avoid an infinite loop is it's selected.
                        $theme_locations = get_nav_menu_locations();
                        if (isset($theme_locations['swal-user-menu-item'])) {
                            $menu_obj = get_term( $theme_locations['swal-user-menu-item'], 'nav_menu' );
                            $submenu_name = isset($menu_obj->slug) ? $menu_obj->slug : '';
                        }
                        

                        foreach ( $menus as $location ) {
                            $args = array(
                            'id'            => 'swal_menu_to_append_'.$location->slug,
                            'name'          => 'swal_menu_to_append[]',
                            'value'         => $swal_menu_to_append,
                            'input_value'   => $location->slug,
                            'label'         => esc_html__($location->name),
                            'label_class'   => 'sw-right-label',
                                );
                            if ( $location->slug != $submenu_name ) {
                                 echo '<div class="clear margin-bottom-5 ">';
                                        swal_checkbox_ios_style( $args );
                                echo '</div>';
                            } 
                                    
                        }
                   
                    ?>

                    <?php
                        if (!$check_menu_exists) {
                            echo '<div class="sw-info-box sw-info-box-info hide" id="swal_menu_alert"><p>';
                            printf( esc_html__( 'This navigation menu has not a menu yet, please go to %s and create one.', 'sw-ajax-login' ) , '<a href="'. $homeurl.'/wp-admin/nav-menus.php">'. esc_html__('Appearance -> Menus','sw-ajax-login').'</a>' );
                            echo '</p></div>';
                            
                        }
                    ?>
                    <p class="description">
                    <?php esc_html_e('Select the menu you want to add the item \'Login\'. It\'s usually the header menu, but you can select any other menu.' ,'sw-ajax-login');
                     ?>
                </p>
                <?php
                
                ?>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_menu_item_text"><?php esc_html_e('Login menu text','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_menu_item_text" id="swal_menu_item_text" class="floatL margin-right settings-select">
                        <?php
                            $item = array();
                            $item[] = __("Login",'sw-ajax-login');
                            $item[] = __("Login/Register",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_menu_item_text, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <p class="clear description">
                    <?php esc_html_e('Select the text that will appear on the login menu item.' ,'sw-ajax-login');
                     ?></p>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_user_thumbnail_style"><?php esc_html_e('Thumbnail Style','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_user_thumbnail_style" id="swal_user_thumbnail_style" class="settings-select">
                        <?php
                            $item = array();
                            $item[] = __("Don't show thumbnail",'sw-ajax-login');
                            $item[] = __("Squared",'sw-ajax-login');
                            $item[] = __("Rounded",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_user_thumbnail_style, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <p class="description">
                            <?php esc_html_e('This is how the user thumbnail will appear on the main menu when the user is logged','sw-ajax-login'); ?>
                        </p>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_menu_item_style"><?php esc_html_e("Once logged show on main menu:",'sw-ajax-login') ?></label></th>
                <td>
                        <?php 
                            $item = array();
                            $item[] = __("Logout link",'sw-ajax-login');
                            $item[] = __("Username",'sw-ajax-login');
                            $item[] = __("Name Surname",'sw-ajax-login');
                            $item[] = __("Surname Name",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<label class="sw-label-radio-buttons"><input type="radio" id="swal_menu_item_style'.$key.'" name="swal_menu_item_style" value="'.$key.'"'.checked( $swal_menu_item_style, $key,false ).'> '.$value.'</label>';
                                } 
                        ?>
                        <p class="clear description">
                        <?php esc_html_e('Note: if name or surname are not available will display the username.' ,'sw-ajax-login');
                         ?></p> 
                    </td>
                </tr>

                <tr valign="top">
                <th scope="row"><label for="swal_menu_item_link_to"><?php esc_html_e('Menu item links to:','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_menu_item_link_to" id="swal_menu_item_link_to" class="floatL margin-right settings-select">
                        <?php
                            $item = array();
                            $item[] = __("No link",'sw-ajax-login');
                            $item[] = __("Custom link",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_menu_item_link_to, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <div id="menu-item-custom-link" class="clear padding-top hide text_long">
                        <code><?php echo $homeurl ?>/</code>
                        <input placeholder="<?php esc_html_e('Insert page','sw-ajax-login'); ?>" type="text" id="swal_menu_item_custom_link_to" name="swal_menu_item_custom_link_to" class="regular-text code text_long settings-select" value="<?php echo $swal_menu_item_custom_link_to; ?>">
                        <p class="clear description"><?php esc_html_e('If the field is empty no link will be applied to the menu item.','sw-ajax-login') ?></p>
                    </div>
                </td>
                </tr>
            </table>
            <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_6',true,array('id' => 'submit_tab_6' )); ?>
    <?php
}

?>