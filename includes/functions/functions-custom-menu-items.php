<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 * Add login/logout item to the main menu
 */
function swal_add_open_login_window_button( $items, $args) {

  $swal_menu_item_text            = intval(get_option('swal_menu_item_text',SWAL_MENU_ITEM_TEXT));
  $swal_menu_item_custom_text     = esc_html(get_option('swal_menu_item_custom_text'));
  $swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
  $swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');
  $swal_loggedin_menu_item_custom_text     = esc_html(get_option('swal_loggedin_menu_item_custom_text'));

  //menu login text
  $swal_menu_login_text           = swal_menu_login_text($swal_menu_item_text,$swal_menu_item_custom_text);

  //li and li->a tags additional classes
  $swal_add_menu_additional_class      = intval(get_option('swal_add_menu_additional_class'));
  $swal_add_menu_li_class         = trim(sanitize_html_classes(get_option('swal_add_menu_li_class')));
  $swal_add_menu_li_a_class       = trim(sanitize_html_classes(get_option('swal_add_menu_li_a_class')));
  $swal_add_menu_li_a_span_class       = trim(sanitize_html_classes(get_option('swal_add_menu_li_a_span_class')));
  $swal_add_menu_li_a_ul_class       = trim(sanitize_html_classes(get_option('swal_add_menu_li_a_ul_class')));

  $li_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_class )  ? ' ' . $swal_add_menu_li_class  : '';
  $li_a_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_class )  ? ' class="' . $swal_add_menu_li_a_class .'"' : '';
  $li_a_span_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_span_class )  ? ' class="' . $swal_add_menu_li_a_span_class .'"' : '';
  $li_a_additional_only_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_class )  ? ' ' . $swal_add_menu_li_a_class  : '';
  $li_a_ul_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_ul_class )  ? ' ' . $swal_add_menu_li_a_ul_class  : '';

  //add it only in selected menu

    if (is_user_logged_in()) {


      //recupero le impostazioni 
    $swal_menu_item_style         = intval(get_option('swal_menu_item_style',SWAL_MENU_ITEM_STYLE));   

    // Get the content and the avatar to display in menu item when user is logged in
    $displayname    = swal_get_user_menu_item_content();
    $user_thumnail  = swal_get_user_menu_item_avatar();
    $menuitemlink   = swal_get_logged_in_menu_item_link();
 

    //impostazioni per il submenu utente
    $args = array( 
            'theme_location' => 'swal-user-menu-item',
            'container' => false,
            'menu_class'=> 'sub-menu'.$li_a_ul_additional_class,
            'echo' => false,
            'fallback_cb' => 'swal_default_menu',
            'walker' => new swal_submenu_walker(),
            );

    
      
      switch ($swal_menu_item_style) {
        case 0:
            $items .= '<li id="menu-item-999789" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-999789 open_logout'.$li_additional_class.'">
                          <a href="'. swal_logout_url().'" id="swal-wrapper-logout-menu-item" class="sw-logout-menu'.$li_a_additional_only_class.'"><span'.$li_a_span_additional_class.'>'.$swal_menu_item_logout_custom_text.'</span></a>
                      </li>';
            break;
        default:
            $items .= '<li id="menu-item-999789" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-999789'.$li_additional_class.'">
                          <a href="'. $menuitemlink .'" id="swal-wrapper-loggedin-menu-item"'.$li_a_additional_class.'>';
            $items .= $user_thumnail . $displayname . '</a>';
            $items .= wp_nav_menu( $args );
            $items .= '</li>';
            break; 
     }
        
    }
    else {
        $items .= '<li class="menu-item sw-open-login'.$li_additional_class.'"><a href="'. wp_login_url() .'" id="swal-wrapper-login-menu-item"'.$li_a_additional_class.'><span'.$li_a_span_additional_class.'>'.esc_html($swal_menu_login_text).'</span></a></li>';
      }

    return $items;
}



/**
 * Display only Login item with shortcode
 *
 * @since 1.6.1
 */
add_shortcode( 'swal_display_login_item', 'swal_display_login_item' );

function swal_display_login_item($atts='') {

  // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    $value = shortcode_atts( array(
        'openlogout' => '',
        'hideavatar' => '',
        'class' => '',
        'loggedintext' => '',
    ), $atts );

    // Get the content and the avatar to display in menu item when user is logged in
    $displayname    = swal_get_user_menu_item_content();
    $menuitemlink   = swal_get_logged_in_menu_item_link();

    $popup = '';
    $addclass = '';

    if (!is_user_logged_in()) {
        $user_thumnail  = '';
        $popup = 'sw-open-login';
    } else {
        $user_thumnail  = (!$value['hideavatar'] == 'true') ? swal_get_user_menu_item_avatar() : '';
        if ($value['openlogout'] == 'true') {
            $popup = 'open_logout';
        }
        

        if ($value['loggedintext']) {
            $displayname = esc_html($value['loggedintext']);
        }
    }

    if ($popup || $value['class']) {
        $addclass = ' class="' . $popup . ' ' . esc_html($value['class']) . '"';
    }

    $item = '<a href="'. $menuitemlink .'"'. $addclass . '>' . $user_thumnail . $displayname . '</a>';

    return $item;
}




/**
 * Assign the login menu item to the selected menu (Deprecated method)
 */
add_action( 'init', 'swal_append_login_menu_item_deprecated',10 );

function swal_append_login_menu_item_deprecated() {
    $swal_menu_to_append    = is_array(get_option('swal_menu_to_append')) ? array_map('esc_attr',get_option('swal_menu_to_append')) : esc_attr(get_option('swal_menu_to_append'));

    if ($swal_menu_to_append ) {
      if (is_array($swal_menu_to_append)) {
        foreach ($swal_menu_to_append as $menu) {

          // if flag is false append in the deprecated way the login menu item 
          if (!swal_check_if_menu_has_login_item($menu,'sw-open-login')) {
            add_filter( 'wp_nav_menu_'.$menu.'_items', 'swal_add_open_login_window_button', 101, 2 );
          }
        }

      } else {
        if (!swal_check_if_menu_has_login_item($swal_menu_to_append,'sw-open-login')) {
            add_filter( 'wp_nav_menu_'.$swal_menu_to_append.'_items', 'swal_add_open_login_window_button', 101, 2 );
          }
      }

    }
}






/**
* Dynamically add a Logout item as sub-menu to the existing menu item logged user in wp_nav_menu
* only if a user is logged in.
*/
  
function swal_dynamic_submenu_logout_link( $items, $args ) {

    $swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
    $swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');
  
    $theme_location = 'swal-user-menu-item';// Theme Location slug
    $new_menu_item_db_id = 99254; // unique id number
     
    if ( $theme_location !== $args->theme_location ) {
        return $items;
    }
    $new_links = array();
  
    if ( is_user_logged_in() ) {
    
        // only if user is logged-in, do sub-menu link
        $item = array(
            'title'            => $swal_menu_item_logout_custom_text,
            'menu_item_parent' => 0,
            'ID'               => $new_menu_item_db_id,
            'db_id'            => $new_menu_item_db_id,
            'url'              => swal_logout_url(),
            'classes'          => array( 
                                      'swal-logout-menu-item'
                                      )// optionally add custom CSS class
        );
  
    $items[] = (object) $item;

     }

    return $items;
}
add_filter( 'wp_nav_menu_objects', 'swal_dynamic_submenu_logout_link', 101, 2 );


/**
 *
 * Provides a default menu featuring the 'Logout' link, if not other menu has been provided.
 *
 */
function swal_default_menu() {

      $swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
      $swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');

  //li and li->a tags additional classes
      $swal_add_menu_additional_class       = intval(get_option('swal_add_menu_additional_class'));
      $swal_add_menu_li_a_ul_class       = sanitize_html_classes(get_option('swal_add_menu_li_a_ul_class'));
      $swal_add_menu_li_a_ul_li_class       = sanitize_html_classes(get_option('swal_add_menu_li_a_ul_li_class'));
      $swal_add_menu_li_a_ul_li_a_class     = sanitize_html_classes(get_option('swal_add_menu_li_a_ul_li_a_class'));

      $li_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_ul_li_class )  ? ' ' . $swal_add_menu_li_a_ul_li_class  : '';
      $li_a_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_ul_li_a_class )  ? ' ' . $swal_add_menu_li_a_ul_li_a_class  : '';
      $li_a_ul_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_ul_class )  ? ' ' . $swal_add_menu_li_a_ul_class  : '';

  $html = '<ul class="sub-menu'.$li_a_ul_additional_class.'">
        <li id="menu-item-99254" class="menu-item menu-item-type-post_type menu-item-object-page open_logout'.$li_additional_class.'">
          <a href="'. swal_logout_url().'" id="swal-wrapper-logout-menu-item" class="sw-logout-menu'.$li_a_additional_class.'">'.$swal_menu_item_logout_custom_text.'</a>
        </li>
       </ul>';
  return $html;
}



/**
 *
 * Extends Walker Nav Menu class with new attributes
 *
 */

class swal_submenu_walker extends Walker_Nav_Menu
{
      function start_el(&$output, $item, $depth=0, $args=array(), $id = 0)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

           //li->ul->li tags additional classes
            $swal_add_menu_additional_class      = intval(get_option('swal_add_menu_additional_class'));
            $swal_add_menu_li_a_ul_li_class      = sanitize_html_classes(get_option('swal_add_menu_li_a_ul_li_class'));
            $swal_add_menu_li_a_ul_li_a_class    = sanitize_html_classes(get_option('swal_add_menu_li_a_ul_li_a_class'));

            $li_additional_class = ( $swal_add_menu_additional_class && $swal_add_menu_li_a_ul_li_class )  ? ' ' . $swal_add_menu_li_a_ul_li_class  : '';

           $class_names = ' class="'. esc_attr( $class_names ) . $li_additional_class. ' menu-item-'. $item->ID . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
           $attributes .= ( $swal_add_menu_additional_class && $swal_add_menu_li_a_ul_li_a_class )  ? ' class="'   . $swal_add_menu_li_a_ul_li_a_class .'"' : '';
           $attributes .= ' id="swal-'   . strtolower(sanitize_file_name(apply_filters( 'the_title', $item->title, $item->ID ))) .'"';

           $prepend = '<strong>';
           $append = '</strong>';
           $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

           if($depth != 0)
           {
                     $description = $append = $prepend = "";
           }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= $description.$args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}



/**
 *
 * Filter the menues, if there's a menu item with a SWAL class then change the text if logged in
 *
 * @since 1.6.0
 */
if ( !class_exists( 'swal_HiJackMenu' ) ) {
    class swal_HiJackMenu {

        public function hijack_menu($objects) {

          $item_id = '';

          $users_can_register               = esc_attr(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
          /**
           * If user isn't logged in, we remove the submenu and return the link as normal
           */

          if ( !is_user_logged_in() ) {
            
            foreach ( $objects as $k=>$object ) {
                if ( in_array( 'sw-open-login', $object->classes ) ) {
                    // Get menu item ID
                    $item_id = $object->ID;
                      $remove_key = array_search( 'menu-item-has-children', $object->classes );
                      unset($objects[$k]->classes[$remove_key]);

                }

            // If user isn't logged in, remove the submenu items with parent ID = $item_id
                if ( $object->menu_item_parent ==  $item_id || in_array( 'open_logout', $object->classes ) ) {
                  unset($objects[$k]);
                }

                // If user is logged in, remove the Register menu item
                if ( in_array( 'sw-open-register', $object->classes ) && !$users_can_register ) {
                  unset($objects[$k]);
                }

            }
            return $objects;
          }

          $swal_menu_item_style                = intval(get_option('swal_menu_item_style',SWAL_MENU_ITEM_STYLE));
          $swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
          $swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');

          // Additional classes
          $swal_add_menu_additional_class     = intval(get_option('swal_add_menu_additional_class'));
          $swal_add_menu_li_class             = sanitize_html_classes(get_option('swal_add_menu_li_class'));


          /**
           * get the redirect URL if the direct logout is enabled
           *
           * @since 1.7.8
           */
          $swal_logout_direct               = intval(get_option('swal_logout_direct'));
          //get redirect to
          $location = rtrim(sw_curPageURL_no_vars(), '/');

          $swal_redirect_after_logout           = intval(get_option('swal_redirect_after_logout',SWAL_REDIRECT_AFTER_LOGOUT));
          $swal_custom_redirect_after_logout    = esc_attr(get_option('swal_custom_redirect_after_logout'));
          $swal_custom_redirect_after_logout    = ltrim($swal_custom_redirect_after_logout, '/');

          $page_to_redirect = home_url();
          if ($swal_redirect_after_logout == 1) {
            $page_to_redirect = $location;
          } else if ($swal_redirect_after_logout == 2) {
            $page_to_redirect = home_url($swal_custom_redirect_after_logout);
          }
        

          /**
           * If user is logged in, we search through the objects for items with the 
           * class sw-open-login and we change the text and url into a logout link
           */
          foreach ( $objects as $k=>$object ) {

            if ( in_array( 'sw-open-login', $object->classes ) ) {

              // Get menu item ID
              $item_id = $object->ID;
              
               // Get the content and the avatar to display in menu item when user is logged in
              $swal_item_avatar = intval(get_post_meta( $item_id, '_swal_menu_item_avatar', true ));
              $menuitemlink  = esc_url(get_post_meta( $item_id, '_swal_logged_in_menu_item_link', true )) ? esc_url(get_post_meta( $item_id, '_swal_logged_in_menu_item_link', true )) : swal_get_logged_in_menu_item_link();

              $displayname    = swal_get_user_menu_item_content();
              $user_thumnail  = (!$swal_item_avatar) ? swal_get_user_menu_item_avatar($object->ID) : ''; // adds menu-item-id to avatar to make it unique
              $displayname    = $swal_menu_item_style ? $user_thumnail . $displayname : $swal_menu_item_logout_custom_text;


              $objects[$k]->title = $displayname;
              $objects[$k]->url = $menuitemlink;
              $objects[$k]->attr_title = '';
              $remove_key = array_search( 'sw-open-login', $object->classes );
              unset($objects[$k]->classes[$remove_key]);

              // Additional classes
              $objects[$k]->classes[] = ($swal_add_menu_additional_class && $swal_add_menu_li_class) ? $swal_add_menu_li_class : '';

              // if the menu item to show is Logout adds the class to open the popup from there
              if (!$swal_menu_item_style) {
                  $objects[$k]->classes[] = 'open_logout';
              }
 
            }

            // If user is logged in, remove the Register menu item
            if ( in_array( 'sw-open-register', $object->classes ) ) {
                  unset($objects[$k]);
                }

            /**
             * if direct logout is enabled replaces the URL with the direct logout link
             *
             * @since 1.7.8
             */
            if (in_array( 'open_logout', $object->classes ) && $swal_logout_direct) {

              $remove_key = array_search( 'open_logout', $object->classes );
              unset($objects[$k]->classes[$remove_key]);

              $objects[$k]->url = html_entity_decode(wp_logout_url( $page_to_redirect ));
            }

          }

          return $objects;
        }
    }
}

$hijackme = new swal_HiJackMenu;

add_filter('wp_nav_menu_objects', array($hijackme, 'hijack_menu'), 10, 2);




/**
 * Add custom attribute and value to a nav menu item's anchor based on CSS class.
 *
 * @since 1.6.0
 */
add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args ) {

    $swal_add_menu_additional_class     = intval(get_option('swal_add_menu_additional_class'));
    $swal_add_menu_li_a_class       = trim(sanitize_html_classes(get_option('swal_add_menu_li_a_class')));

    if ( isset($item->classes[0]) && 'swal-menu-item' === $item->classes[0] && $swal_add_menu_additional_class) {
        
        $atts['class'] = $swal_add_menu_li_a_class;
    }

    return $atts;
}, 9, 3 );

?>