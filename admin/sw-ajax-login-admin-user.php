<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

// add the GDPR checkbox to new user form
add_action('user_new_form', 'swal_usermeta_gdpr_consent_checkbox',20);

add_action( 'user_register', 'swal_save_gdpr_consent_checkbox' );

// add the field to user's own profile editing screen
add_action('edit_user_profile','swal_usermeta_consent',20);
 
// add the field to user profile editing screen
add_action('show_user_profile','swal_usermeta_consent',20);

// Adds a GDPR consent column to the user display dashboard.
add_filter('manage_users_columns', 'swal_add_user_gdpr_column' );

// Populates the SWAL user columns.
add_action('manage_users_custom_column', 'swal_show_user_gdpr_data', 10, 3 );

// Make our "Registration date" column sortable
add_filter('manage_users_sortable_columns', 'swal_make_registered_column_sortable' );

// Adds activate/deactivate links to user actions links
add_filter('user_row_actions', 'swal_user_action_links', 10, 2);


/**
 * The GDPR checkbox on new user screens.
 *
 * @param $user WP_User user object
 */
function swal_usermeta_gdpr_consent_checkbox($user) {

    ?>
    <div class="tabscontent">
    <h3><?php esc_html_e('GDPR consent','sw-ajax-login') ?></h3>
    <table class="form-table">
        <?php 

        echo '<tr>
                    <th></th>
                    <td><input type="checkbox" id="user_gdpr_consent" name="user_gdpr_consent" value="1"/><label for="user_gdpr_consent"> ' . esc_html__('I have the user\'s permission to collect and store his\her data.','sw-ajax-login') . '</label></td>
                </tr>';
        ?>
    </table>
    <?php
}

/**
 * The field on the editing screens.
 *
 * @param $user WP_User user object
 */
function swal_usermeta_consent($user) {

    $user_id        = $user->ID;
    $gdpr_consent   = esc_attr(get_user_meta($user_id, 'user_gdpr_consent', true));

    //$current_screen = get_current_screen();

    ?>
    <div class="tabscontent">
    <h3><?php esc_html_e('GDPR consent','sw-ajax-login') ?></h3>
    <div class="form-table">
    	<?php 

        if ($gdpr_consent) {
            $gdpr_consent_message = '<span class="swal-gdpr-column">'.esc_html__('User gives GDPR consent', 'sw-ajax-login' ).'</span>';
        } else {
            $gdpr_consent_message = esc_html__('User doesn\'t give GDPR consent', 'sw-ajax-login' );
        }
        echo $gdpr_consent_message;
        ?>
    </div>
    <?php
}


/**
 * Save the GDPR consent
 *
 * @param $user_id
 *
 * @return void
 */
function swal_save_gdpr_consent_checkbox($user_id) {

    if (isset($_POST['user_gdpr_consent'])) {

        update_user_meta( $user_id, 'user_gdpr_consent', intval( $_POST['user_gdpr_consent'] ) );

    }
}
 



/**
  * Adds a GDPR consent column to the user display dashboard.
  *
  * @param  $columns    The array of columns that are displayed on the user dashboard
  * @return         The updated array of columns now including GDPR consent.
  */
function swal_add_user_gdpr_column( $columns ) {

    $columns['user_registered'] = __( 'Registered', 'sw-ajax-login' );
    $columns['user_from'] = __( 'From', 'sw-ajax-login' );
    $columns['user_gdpr_consent'] = __( 'Consent', 'sw-ajax-login' );
    return $columns;

 } 




/**
  * Populates the SWAL user columns.
  *
  * @param  $value      An empty string
  * @param  $column_name    The name of the column to populate
  * @param  $user_id    The ID of the user for which we're working with
  * @return         The GDPR consent associated with the user
  */
function swal_show_user_gdpr_data( $value, $column_name, $user_id ) {

    switch ($column_name) {
      case 'user_gdpr_consent':
        $gdpr_consent   = esc_attr(get_user_meta($user_id, 'user_gdpr_consent', true));

        if ($gdpr_consent) {
                    $value = '<span class="swal-gdpr-column">'.esc_html__('GDPR', 'sw-ajax-login' ).'</span>';
                } 
        break;
      
      case 'user_registered':

        $date_format = 'j M, Y H:i';

          $udata = get_userdata( $user_id );
          $registered = date( $date_format, strtotime( get_the_author_meta( 'registered', $user_id ) ) ) ;
          $value = $registered;
        break;

      case 'user_from':

        $social = esc_attr(get_user_meta($user_id, 'social', true));
        if ($social) {
            $value = swal_get_social_icon($social);
            break;
        }

        // Get the social logins list
        if(class_exists( 'Layers_SwAjaxLogin' )) {
            $socials = Layers_SwAjaxLogin::swal_get_social_logins();
        } elseif (class_exists( 'Layers_SwAjaxLogin_free' )) {
            $socials = Layers_SwAjaxLogin_free::swal_get_social_logins();
        }
        

        foreach ($socials as $social) {
            if ( metadata_exists( 'user', $user_id, $social ) ) {

                $value = swal_get_social_icon($social);
                break;
            } else {
                $value = swal_get_social_icon();
            }
        }
        break;
    }

    return $value;
 }


/*
 * Make our "Registration date" column sortable
 * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param} 
 */
function swal_make_registered_column_sortable( $columns ) {

    return wp_parse_args( array( 'user_registered' => 'registered' ), $columns );
}



/**
 *
 * Filter the user row edit links
 *
 */
function swal_user_action_links($actions, $user_object) {

    if (!class_exists( 'Layers_SwAjaxLogin' )) {
        return $actions;
    }

    $user_id    = $user_object->ID;
    $roles      = ( array ) $user_object->roles;

    if (!swal_is_admin_validation_enabled()) {
        return $actions;
    }

    // Don't show the links to users that can't edit users. Administrators are the only that can't be deactivated
    if ( get_current_user_id() === $user_id || !current_user_can( 'edit_user', $user_id ) || $roles[0] == 'administrator') {
        return $actions;
    }

    $swal_account_activated = esc_attr(get_user_meta($user_id, 'swal_account_activated', true));

    $site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
    $url     = 'site-users-network' === get_current_screen()->id ? add_query_arg( array( 'id' => $site_id ), 'site-users.php' ) : 'users.php';

    if ($swal_account_activated) {
        $url = wp_nonce_url( add_query_arg( array(
                'action' => 'swal_deactivate',
                'user'   => $user_id,
            ), $url ), 'swal-deactivate-users' );
        $url = '<a class="swal_deactivate" href="'.  esc_url( $url ) . '">' . __( 'Deactivate', 'sw-ajax-login' ) . '</a>';
    } else {
        $url = wp_nonce_url( add_query_arg( array(
                'action' => 'swal_activate',
                'user'   => $user_id,
            ), $url ), 'swal-activate-users' );
        $url = '<a class="swal_activate" href="'.  esc_url( $url ) . '">' . __( 'Activate', 'sw-ajax-login' ) . '</a>';
    }

    $actions['swal_activate_action'] = $url;
    return $actions;
}



?>