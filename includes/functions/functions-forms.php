<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}



/**
 *
 * Redirect to custom login page
 *
 */

add_action( 'init','swal_redirect_login_page',100 );

function swal_redirect_login_page(){


	/**
	 *
	 * Option to don't disable wp-login.php page
	 *
	 * @since 1.9.3
	 *
	 */
	$swal_enable_wp_login_page          = intval(get_option('swal_enable_wp_login_page'));

	if ($swal_enable_wp_login_page) {
		return;
	}

    // Store for checking if this page equals wp-login.php
    $page_viewed = basename($_SERVER['PHP_SELF']);
    //$page_viewed = basename($_SERVER['REQUEST_URI']);

    $users_can_register  	= intval(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));

    $home = home_url();
    
    // permalink to the custom login page
    $login_page  = wp_login_url();
    $register_page  = wp_registration_url();
    $lostpassword_page  = wp_lostpassword_url();

    $current_page = rtrim(sw_curPageURL_no_vars(), '/');

    $action = '';


    // If the user is logged-in and try to go to login/register or logout pages check in what page must be redirected
    if (is_user_logged_in()) {

    	switch ($current_page) {
    		case rtrim($login_page, '/'):
    		case rtrim($register_page, '/'):
    		case rtrim($lostpassword_page, '/'):
    			swal_check_logged_user_to_redirect();
    			break;
    	}
    }


    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {

    	if (isset($_GET['action'])) {
	    	$action = $_GET['action'];
	    }

    	switch ($action) {
		    	case 'register':
		    		if ($users_can_register) {
			    		exit( wp_redirect( $register_page ));
			    	} else {
			    		exit( wp_redirect( $home ));
			    	}
			    	break;
			    case 'lostpassword':
			    	exit( wp_redirect( $lostpassword_page ));
			    	break;
			    case 'logout':
			    	break;
			    case 'rp':

				    $key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
				    $login = isset($_GET['login']) ? sanitize_text_field($_GET['login']) : '';

			    	$reset_password_url = swal_resetpassword_url() . "?key=$key&login=" . rawurlencode( $login );

			    	if ($key && $login) {
			    		exit( wp_redirect( $reset_password_url ));
			    	}
			    	break;
			    default:
			    	exit( wp_redirect( $login_page ));
			    	break;
			    }

    }
}

/**
 *
 * Filter the login URL
 *
 */
add_filter( 'login_url', 'swal_login_page', 105, 3 );
function swal_login_page( $login_url, $redirect, $force_reauth ) {

	$login_page = '';

	$swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));

	// In case of custom pages
	if ($swal_pagina_account_default == 2) {

		$swal_pagina_account_login    = intval(get_option('swal_pagina_account_login'));
		$login_page = get_page_link($swal_pagina_account_login);

	} else {

		$login_page  = home_url(esc_url('/login/'));

	}

    return $login_page;
}

/**
 *
 * Filter the lost password URL
 *
 */
add_filter( 'lostpassword_url', 'swal_lost_password_page', 105, 1 );
function swal_lost_password_page( $lostpassword_url ) {

	$lostpassword_page = '';

	$swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));
	
	// In case of custom pages
	if ($swal_pagina_account_default == 2) {

		$swal_pagina_account_forgot_password    = intval(get_option('swal_pagina_account_forgot_password'));
		$lostpassword_page = get_page_link($swal_pagina_account_forgot_password);

	} else {

		$swal_permalink = swal_get_permalink() ? '/'.swal_get_permalink() : '';
		$lostpassword_page  = home_url(esc_url($swal_permalink.'/forgot_password/'));

	}

    return $lostpassword_page;
}

/**
 *
 * Filter the register URL
 *
 */
add_filter( 'register_url', 'swal_register_page', 105, 1 );
function swal_register_page( $register_url ) {

	$register_page = '';

	$swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));

	// In case of custom pages
	if ($swal_pagina_account_default == 2 && get_option('swal_pagina_account_register')) {

		$swal_pagina_account_register    = intval(get_option('swal_pagina_account_register'));
		$register_page = get_page_link($swal_pagina_account_register);

	} else {

		$swal_permalink = swal_get_permalink() ? '/'.swal_get_permalink() : '';
		$register_page  = home_url(esc_url($swal_permalink.'/register/'));

	}
    
    return $register_page;
}

/**
 *
 * Filter the logout URL
 *
 */


function swal_logout_url() {

	$swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));
	
	// In case of custom pages
	if ($swal_pagina_account_default == 2) {

		$swal_pagina_account_logout    = intval(get_option('swal_pagina_account_logout'));
		$logout_page = $swal_pagina_account_logout ? get_page_link($swal_pagina_account_logout) : '';

	} else {

		$swal_permalink = swal_get_permalink() ? '/'.swal_get_permalink() : '';
		$logout_page  = home_url(esc_url($swal_permalink.'/logout/'));

	}

    return $logout_page;
}



/**
 *
 * Filter the logout redirect URL
 *
 */

//add_filter( 'logout_redirect', 'swal_logout_redirect_url', 10, 3 );
function swal_logout_redirect_url($redirect_to, $requested_redirect_to, $user) {

	$requested_redirect_to = swal_page_to_redirect_logout();

    return $requested_redirect_to;
}


/**
 *
 * SWAL function to return Reset Password URL
 *
 */
function swal_resetpassword_url() {

	$swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));
	
	// In case of custom pages
	if ($swal_pagina_account_default == 2) {

		$swal_pagina_account_reset_password    = intval(get_option('swal_pagina_account_reset_password'));
		$resetpassword_page = $swal_pagina_account_reset_password ? get_page_link($swal_pagina_account_reset_password) : '';

	} else {

		$swal_permalink = swal_get_permalink() ? '/'.swal_get_permalink() : '';
		$resetpassword_page  = home_url(esc_url($swal_permalink.'/resetpassword/'));

	}

    return $resetpassword_page;
}

/**
 *
 * Remove register URL if new user registration is disallowed
 *
 */
add_filter('register','no_register_link');
function no_register_link($url){
	$users_can_register  = intval(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
	if (!$users_can_register) {
		return '';
	} else {
		return $url;
	}
}


//add_filter( 'login_redirect', 'my_login_redirect', 10, 1 );
function my_login_redirect( $redirect_to ) {

        return swal_page_to_redirect();

}



/**
 *
 * if logged-in user goes to login/register/forgot password pages then redirect 
 * or show a message
 *
 * if not logged-in user goes to logout page it will be redirected to login page
 *
 */
add_action( 'template_redirect','swal_check_logged_user_redirect' );

function swal_check_logged_user_redirect(){

    $act = get_query_var( 'act');
    $slug = get_query_var( 'slug');

    $users_can_register  	= intval(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
    
    // permalink to the custom login page
    $login_page  = wp_login_url();

    if ($slug == 'sw-account') {

    	//if new users are not allowed registering then redirect to the homepage
    	if ($act == 'register' && !$users_can_register) {
    		exit( wp_redirect( home_url() ));
    	} else if ($act == 'login' || $act == 'register' || $act == 'forgot_password') {
    		swal_check_logged_user_to_redirect();
    	} else if ($act == 'logout' && !is_user_logged_in()) {

    		exit( wp_redirect( $login_page ));
    	}
	}
    return;

}




/**
 * Add recaptcha js to footer
 */

add_action( 'wp_print_footer_scripts', 'swal_add_captcha_js_to_footer' );

function swal_add_captcha_js_to_footer() {

	$users_can_register  	= esc_attr(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
	$swal_add_recaptcha  	= intval(get_option('swal_add_recaptcha'));
	$swal_recaptcha_version = intval( get_option('swal_recaptcha_version', 0) );

	if (!is_user_logged_in() && $users_can_register && $swal_add_recaptcha) {

		if ($swal_recaptcha_version) {
			// reCAPTCHA v3
			$swal_recaptcha_key         = esc_attr(get_option('swal_recaptcha_v3_key'));
		
			echo '<script src="https://www.google.com/recaptcha/api.js?render='.$swal_recaptcha_key.'"></script>';
		} else {
			// reCAPTCHA v3
			echo '<script src="https://www.google.com/recaptcha/api.js?onload=InitializeRecaptcha&#038;render=explicit" async defer></script>';
		}
	}
}

/**
 *
 * login, register, lost password forms
 *
 */

if ( !shortcode_exists( 'swal_account_forms' ) ) {
	add_shortcode( 'swal_account_forms', 'swal_account_forms');
}

function swal_account_forms($act) {


	global $sw_login_json;

	$users_can_register  		= intval(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
	$swal_popup_layout_style	= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
	$swal_add_overlay_login		= intval(get_option('swal_add_overlay_login',SWAL_ADD_OVERLAY_LOGIN));
	$swal_login_intro_text_link     = get_option('swal_login_intro_text_link') ? esc_html(get_option('swal_login_intro_text_link')) : '';
	$swal_register_intro_text_link     = get_option('swal_register_intro_text_link') ? esc_html(get_option('swal_register_intro_text_link')) : __(SWAL_REGISTER_INTRO_TEXT_LINK,'sw-ajax-login');
	
	//logout overlay
	$swal_add_overlay_logout    = esc_attr(get_option('swal_add_overlay_logout'));
	$swal_class_header_tabs 	= ($swal_popup_layout_style >= 6 && $users_can_register) ? ' swal_form_tabs_wrapper' : '';

	// Layout with Tabs
	$swal_tabs_link_style       = intval(get_option('swal_tabs_link_style')) ? ' swal_form_tabs_blocks' : '';
   
    $swal_icon_login 			= intval(get_option('swal_add_icons_to_tabs')) ? '<i class="fa fa-sign-in fa-lg"></i>' : '';
    $swal_icon_register 	    = intval(get_option('swal_add_icons_to_tabs')) ? '<i class="fa fa-user-plus fa-lg"></i>' : '';

    $swal_tabs_contrast 		= ($swal_popup_layout_style == 7) ? ' swal_tabs_contrast' : '';
    $swal_popup_close_icon_position      = intval(get_option('swal_popup_close_icon_position')) ? ' swal-close-button-outside' : '';
	

	echo '<div class="wrapper-ajax-forms'.$swal_class_header_tabs . $swal_popup_close_icon_position.'">
			
			<div class="wrapper-close-popup">
					<i class="icon-close close-popup">X</i>
				</div>';
		
		/**
		 *
		 * Show Login & Register Tabs layout only to not logged in user
		 *
		 */
		if (($swal_popup_layout_style == 6 || $swal_popup_layout_style == 7) && !is_user_logged_in() && $users_can_register) {
			echo '<div class="swal_form_tabs'.$swal_tabs_link_style . $swal_tabs_contrast. '">
					<ul>
						<li><a href="'. wp_login_url() .'" class="pop_login">'.$swal_icon_login.' '. $swal_register_intro_text_link .'</a></li>
						<li><a href="'. wp_registration_url() .'" id="pop_signup" data-content="main">'.$swal_icon_register.' '. $swal_login_intro_text_link .'</a></li>
					</ul>
				</div>';
		}

		/**
		 *
		 * Login Form
		 *
		 */

		if (($act == 'login' || !$act || $act == 'ajax')) {

			echo swal_show_login_form($act);
		}

		/**
		 *
		 * Register Form
		 *
		 */

		if ($act == 'register' || $act == 'ajax') {
		
			echo swal_show_register_form($act);

		}

		/**
		 *
		 * Forgot Password Form
		 *
		 */

		if ($act == 'forgot_password' || $act == 'ajax') {

			echo swal_show_forgot_password_form($act);

		}

		/**
		 *
		 * Reset password
		 *
		 */

		if ($act == 'resetpassword') {

			echo swal_show_reset_password_form();
		 }


		/**
		 *
		 * Logout
		 *
		 */

		if ($act == 'logout' || $act == 'ajax' || !$act) {

			// check if user is already logged out redirect to login page
			echo swal_show_logout_form();

		} 
	echo '</div>';
}




/**
 *
 * Login Form Full
 *
 */


add_shortcode( 'swal_show_login_form', 'swal_add_wrapper_to_login_form' );

/**
 *
 * Add a wrapper to login when showed by shortcode
 * It's necessary to indentify which form is by shortcode
 *
 * @since 1.8.6
 *
 */
function swal_add_wrapper_to_login_form($act = null) {

	$output = '<div id="swal-login-form-wrapper">';
	$output .= swal_show_login_form($act);
	$output .= '</div>';

	return $output;

}

function swal_show_login_form($act = null) {

	$output = '';

		if (is_user_logged_in() && $act != 'ajax') {
			$output = swal_logged_in_message();
			return $output;

		} else if (!is_user_logged_in()) {


		/**
		 *
		 * Get the options
		 *
		 */
		$swal_popup_layout_style	= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
		$swal_add_overlay_login		= intval(get_option('swal_add_overlay_login',SWAL_ADD_OVERLAY_LOGIN));
		$swal_social_icons_position = intval(get_option('swal_social_icons_position',SWAL_SOCIAL_ICONS_POSITION));

		//Login background image
		$options           	= esc_attr(get_option('swal_ajax_login_background'));
		$swal_add_socials_login = intval(get_option('swal_add_socials_login'));
		$swal_add_native_socials_login       = intval(get_option('swal_add_native_socials_login'));
		$logintext          = wpautop(html_entity_decode(strip_tags(get_option('swal_text_login'))));

		/**
		 *
		 * Here you can filter the form text
		 *
		 */
		$logintext 		= apply_filters('swal_login_text', $logintext);

		$image_attributes 	= wp_get_attachment_image_src( $options, 'large');
		$alignment 			= swal_alignment_radiobuttons(intval(get_option('swal_login_bg_image_alignment')));

        $src = $image_attributes ? $image_attributes[0] : '';

        $style_full_background = '';
        $style_single_background = '';
        $overlay = '';
        $overlaytextcolor = '';


        //layout settings 
        if ($swal_popup_layout_style == 1 || $swal_popup_layout_style == 7) {
        	if ($src) {
					$style_single_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
					if ($swal_add_overlay_login) {
						$overlay = ' sw-ajax-login-overlay';
						$overlaytextcolor = ' sw-ajax-login-text-contrast';
						}
					}
        if ($swal_popup_layout_style == 4 || $swal_popup_layout_style == 5 || $swal_popup_layout_style == 7) {
        	if ($src) {
					$style_full_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
					$style_full_background .= ' class="icon-close-contrast"';
					if ($swal_add_overlay_login) {
						$overlay = ' sw-ajax-login-overlay';
						$overlaytextcolor = ' sw-ajax-login-text-contrast';
						}
					}

		//layout with image on right
		$revlayout = '';
		if ($swal_popup_layout_style == 3 || $swal_popup_layout_style == 5) {
			$revlayout = ' ajax-forms-rev';
		}

		// Check if user just updated password
		$passwordupdated = isset( $_GET['password'] );

		if ( $passwordupdated == 'changed' ) {
		    $output .= '<p class="login-info success">'.esc_html__( 'Your password has been changed. You can sign in now.', 'sw-ajax-login' ).'</p>';
		}

				
	$output .= '<div id="wrapper-login"'. $style_full_background .'>
					<div class="inner-form-ajax-forms'. $revlayout .'"'. $style_single_background .'>
						<div class="inner-form-wrapper'. $overlay . $overlaytextcolor .'">';

			/**
			 *
			 * Append Login Form
			 *
			 */
			$output .= swal_show_login_form_only($act);

	$output .= '</div>
			</div>';


			$style = '';
			$overlay = '';
			if ($swal_popup_layout_style && $swal_popup_layout_style > 1 && $swal_popup_layout_style < 6) {
				if (($swal_popup_layout_style == 2 || $swal_popup_layout_style == 3) && $src) {
					$style = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
			$output .= '<div class="inner-text-ajax-forms'. $revlayout .'"'.$style.'>';
				if ($logintext || $swal_popup_layout_style == 4 || $swal_popup_layout_style == 5) {
					if ($swal_add_overlay_login) {
						$overlay = '<div class="sw-ajax-login-overlay-wrapper"></div>';
						}
					$output .= $overlay;
					$output .= '<div class="ajax-forms-main-text">';
					$output .= $logintext;

					// add hook for action
			    	ob_start();
			    	do_action( 'swal_login_form_text' );
			    	$output .= ob_get_clean();
			    	
			    	//socials login buttons
				    if ($swal_social_icons_position == 3 ) {

				    	//show buttons
			    		ob_start();
			    		swal_socials_login_buttons_position($swal_social_icons_position,$swal_popup_layout_style,0);
			    		$output .= ob_get_clean();
				    }

					$output .= '</div>';
					}
					$output .= '<div class="swal-clear"></div>
				</div>';
			}

			$output .= '<div class="swal-clear"></div>
		</div>';

		}
		return $output;

}


/**
 *
 * Login Form Only
 *
 */


add_shortcode( 'swal_show_login_form_only', 'swal_show_login_form_only' );

function swal_show_login_form_only($atts='', $act = null) {

	// normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    $value = shortcode_atts( array(
        'hidelink' => 'false',
    ), $atts );

	$output = '';

		if (is_user_logged_in() && $act != 'ajax') {
			$output = swal_logged_in_message();
			return $output;

		} else if (!is_user_logged_in()) {

	global $sw_login_json;
	$json = json_decode($sw_login_json);

	/**
	 *
	 * Get the options
	 *
	 */
	$swal_login_intro_text          = esc_html(get_option('swal_login_intro_text',__(SWAL_LOGIN_INTRO_TEXT,'sw-ajax-login')));
	$swal_login_intro_text_link     = esc_html(get_option('swal_login_intro_text_link',__(SWAL_LOGIN_INTRO_TEXT_LINK,'sw-ajax-login')));
	$swal_login_form_title     	 	= get_option('swal_login_form_title') ? esc_html(get_option('swal_login_form_title')) : __(SWAL_LOGIN_FORM_TITLE,'sw-ajax-login');
	$swal_login_button     	 	 	= get_option('swal_login_button') ? esc_html(get_option('swal_login_button')) : __(SWAL_LOGIN_BUTTON,'sw-ajax-login');
	$swal_login_forgot_password_text     = get_option('swal_login_forgot_password_text') ? esc_html(get_option('swal_login_forgot_password_text')) : __(SWAL_LOGIN_FORGOT_PASSWORD_TEXT,'sw-ajax-login');
	$users_can_register             = esc_attr(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
	$swal_social_icons_position 	= intval(get_option('swal_social_icons_position',SWAL_SOCIAL_ICONS_POSITION));
	$swal_popup_layout_style		= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
	$swal_add_login_icons       	= intval(get_option('swal_add_login_icons'));
	$swal_add_password_showhide     = intval(get_option('swal_add_password_showhide'));
	$swal_login_remember_credentials     = esc_attr(get_option('swal_login_remember_credentials',SWAL_LOGIN_REMEMBER_CREDENTIALS));

	// Get where to redirect the user after login
	$swal_redirect_after_login           = intval(get_option('swal_redirect_after_login',SWAL_REDIRECT_AFTER_LOGIN));
	$swal_custom_redirect_after_login    = esc_attr(get_option('swal_custom_redirect_after_login'));
	$swal_custom_redirect_after_login 	 = ltrim($swal_custom_redirect_after_login, '/');

		$overlaytextcolor = '';

		$output .= '<form id="swal-login" class="ajax-auth" action="" method="post">';

				// Show registration link if enabled
				if ($users_can_register && $value['hidelink'] == 'false' && $swal_popup_layout_style < 6 && ($swal_login_intro_text || $swal_login_intro_text_link)) {
					
				    $output .= '<h3>'. $swal_login_intro_text .' <a href="'. wp_registration_url() .'" id="pop_signup" data-content="main" class="sw-open-register">'. $swal_login_intro_text_link .'</a></h3>
				    <hr />';
			    }

			    /**
				 *
				 * This hook fires before the form title
				 *
				 */
			    ob_start();
		    	do_action( 'swal_forms_before_title' );
		    	$output .= ob_get_clean();
			     
			    $output .= '<h2>'. $swal_login_form_title .'</h2>';


			    
			    ob_start();
		    	do_action( 'swal_login_form_before_input_fields' );
		    	$output .= ob_get_clean();


			    	$show = '';
			    	$message = '';

			    	if ($json) {
				    	if (!$json->loggedin) {
				    		$show = ' show-status';
				    		$message = $json->message;
				    	}
				    }

			    $output .= '<p class="status'.$show.'">'. $message .'</p>';
			    $output .= '<div id="swal_login_form_message" class="swal_confirm_msg swal_login_form_message"></div>';

			    // Whitelist the login nonce for Litespeed Cache plugin
			    if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
				    LiteSpeed_Cache_API::nonce_action( 'ajax-login-nonce' );
				}

			    //create security nonce field
			    $output .= wp_nonce_field('ajax-login-nonce', 'swal_login_security');

			    //socials login buttons between input fields and submit button
			    if ($swal_social_icons_position == 1 ) {
			    	//show buttons
			    	ob_start();
			    	swal_socials_login_buttons_position($swal_social_icons_position,$swal_popup_layout_style,2);
			    	$output .= ob_get_clean();
			    }

			    $inputicons = '';
			    if ($swal_add_login_icons) {
			    	$inputicons = ' swal-input-icons';	
			    }

			    $inputshowhide = '';
			    if ($swal_add_password_showhide) {
				    $inputshowhide = ' add_showhide';
				}

				
				/**
			     *
			     * If username field is disabled shows only 'email' on placeholder
			     *
			     * @since 1.8.6
			     *
			     */
			    $swal_disable_username_field            = intval(get_option('swal_disable_username_field'));
			    $username_placeholder 	= esc_html__('Username or Email','sw-ajax-login');
			    $input_type 			= 'text';

			    $username_placeholder 	= !$swal_disable_username_field ? $username_placeholder : esc_html__('Email','sw-ajax-login');
			    $input_type 			= !$swal_disable_username_field ? $input_type : 'email';


			    $output .= '<input type="hidden" name="action" value="login"/>
			    				<div class="swal-input-fields-wrapper swal-login-fields">
			    					<label for="username" class="swal-label">'. $username_placeholder .'</label>
								    <div class="swal-field-wrapper'. $inputicons .'">
									    <input placeholder="'. $username_placeholder .'" id="username" type="'.$input_type.'" class="required" name="log">';
								    	if ($swal_add_login_icons) {
								    	$output .= '<i class="fa fa-user fa-lg" aria-hidden="true"></i>';
								    	}
						$output .= '</div>
									<label for="password" class="swal-label">'. esc_html__('Password','sw-ajax-login') .'</label>
								    <div class="swal-field-wrapper'. $inputicons .'">
									    <input placeholder="'. esc_html__('Password','sw-ajax-login') .'" id="password" type="password" class="required'. $inputshowhide .'" name="pwd">';
										if ($swal_add_login_icons) {
								    	$output .= '<i class="fa fa-unlock fa-lg" aria-hidden="true"></i>';
								    	}
								    	if ($swal_add_password_showhide) {
								    	$output .= '<span toggle="#password" class="fa fa-lg fa-eye field-icon swal-toggle-password"></span>';
								    	}
						$output .= '</div>
								</div>';
			
				/**
				 * Fires following the 'Password' field in the login form.
				 *
				 * @since 2.1.0
				 */
				ob_start();
		    	do_action( 'login_form' );
		    	$output .= ob_get_clean();

				//$output .= '<p><label for="wfls-token">2FA Code <a href="javascript:void(0)" class="wfls-2fa-code-help wfls-tooltip-trigger" title="The 2FA Code can be found within the authenticator app you used when first activating two-factor authentication. You may also use one of your recovery codes."><i class="dashicons dashicons-editor-help"></i></a><br/><input type="text" name="wfls-token" id="wfls-token" aria-describedby="wfls-token-error" class="input" value="" size="6" autocomplete="off"/></label></p>';

		    	
		    	$location = home_url();

		    	/*
				if ($swal_redirect_after_login == 1) {
					$location = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
				} else if ($swal_redirect_after_login == 2 && $swal_custom_redirect_after_login) {
					$location = home_url($swal_custom_redirect_after_login);
				} else if ($swal_redirect_after_login == 3) {

					$location = admin_url();
				}
				*/
				

				$location = swal_page_to_redirect();

				//check if have to add redirect hidden input field
				if ($location) {
					$output .= '<input type="hidden" id="redirect_to" name="redirect_to" value="'.$location.'"/>';
				}
			

				if ($swal_login_remember_credentials == 2) {
						$output .= '<div class="rememberme-content">
										<div class="checkboxStyle">
											<input type="checkbox" id="rememberme" name="rememberme" value="forever"/>
											<label for="rememberme"></label>
										</div>
		                    			<label for="rememberme" class="sw-right-label">'.__('Remember me','sw-ajax-login').'</label>
									</div>';
					}

				// add hook for action
				ob_start();
		    	do_action( 'swal_login_form_below_input_fields' );
		    	$output .= ob_get_clean();
		    	
		    	
				//socials login buttons between input fields and submit button
			    if ($swal_social_icons_position == 2 ) {
			    	//show buttons
			    	ob_start();
			    	swal_socials_login_buttons_position($swal_social_icons_position,$swal_popup_layout_style,2);
			    	$output .= ob_get_clean();
			    }
				
				$output .= '<button class="submit_button" type="submit" value="'. $swal_login_button .'">'. $swal_login_button .'</button>';

		    	// add hook for action
		    	ob_start();
		    	do_action( 'swal_login_form_below_button' );
		    	$output .= ob_get_clean();
		    	

			    //socials login buttons below submit button
			    if ($swal_social_icons_position == 0 || ($swal_social_icons_position == 3 && ($swal_popup_layout_style == 0 || $swal_popup_layout_style == 1))) {

			    	//show buttons
			    	ob_start();
			    	swal_socials_login_buttons_position($swal_social_icons_position,$swal_popup_layout_style,1);
			    	$output .= ob_get_clean();
			    }

			    
			    $output .= '<div class="swal-clear">
						    	<a id="pop_forgot" class="text-link load-on-target-auth sw-open-forgot-password" href="'. wp_lostpassword_url() .'" data-content="main">'. $swal_login_forgot_password_text .'</a>
							</div>
							
							<div class="swal-clear"></div>';

								/**
								 *
								 * This hook fires at the end of the forms
								 *
								 */
								ob_start();
						    	do_action( 'swal_login_end_of_form' );
						    	$output .= ob_get_clean();

								ob_start();
						    	do_action( 'swal_end_of_forms' );
						    	$output .= ob_get_clean();
			    	
			$output .= '</form>';
		}

	return $output;
}



/**
 *
 * Register Form
 *
 */

add_shortcode( 'swal_show_register_form', 'swal_add_wrapper_to_register_form' );

/**
 *
 * Add a wrapper to register when showed by shortcode
 * It's necessary to indentify which form is by shortcode
 *
 * @since 1.8.6
 *
 */
function swal_add_wrapper_to_register_form($act = null) {

	$output = '<div id="swal-register-form-wrapper">';
	$output .= swal_show_register_form($act);
	$output .= '</div>';

	return $output;

}

function swal_show_register_form($act = null) {

	$output = '';

		$users_can_register                = esc_attr(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));

		if (is_user_logged_in() && $act != 'ajax') {

			$output = swal_logged_in_message();
			return $output;

		} else if (!is_user_logged_in() && $users_can_register) {

		

		/**
		 *
		 * Get the options
		 *
		 */
		$swal_popup_layout_style	= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
		$swal_add_overlay_login		= intval(get_option('swal_add_overlay_login',SWAL_ADD_OVERLAY_LOGIN));


		//Register background image
		$options           = esc_attr(get_option('swal_ajax_register_background'));
		$registertext       = wpautop(html_entity_decode(strip_tags(get_option('swal_register_login'))));

		/**
		 *
		 * Here you can filter the form text
		 *
		 */
		$registertext 		= apply_filters('swal_register_text', $registertext);

		$image_attributes = wp_get_attachment_image_src( $options, 'large');
		$alignment 			= swal_alignment_radiobuttons(intval(get_option('swal_register_bg_image_alignment')));
        $src = $image_attributes ? $image_attributes[0] : '';
		$style_full_background = '';
        $style_single_background = '';
        $overlay = '';
        $overlaytextcolor = '';

        if ($swal_popup_layout_style == 1 || $swal_popup_layout_style == 7) {
        	if ($src) {
					$style_single_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
				}
					if ($swal_add_overlay_login) {
						$overlay = ' sw-ajax-login-overlay';
						$overlaytextcolor = ' sw-ajax-login-text-contrast';
						}
					}
        if ($swal_popup_layout_style == 4 || $swal_popup_layout_style == 5) {
        	if ($src) {
					$style_full_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
				}
					$style_full_background .= ' class="icon-close-contrast"';
					if ($swal_add_overlay_login) {
						$overlay = ' sw-ajax-login-overlay';
						$overlaytextcolor = ' sw-ajax-login-text-contrast';
						}
					}

		//layout with image on right
		$revlayout = '';
		if ($swal_popup_layout_style == 3 || $swal_popup_layout_style == 5) {
			$revlayout = ' ajax-forms-rev';
		}
		

	$output .= '<div id="wrapper-register"'. $style_full_background .'>
				<div class="inner-form-ajax-forms'. $revlayout .'"' . $style_single_background .'>
					<div class="inner-form-wrapper'. $overlay . $overlaytextcolor .'">';

			/**
			 *
			 * Append Register Form
			 *
			 */				
			$output .= swal_show_register_form_only($act);

	$output .= '</div>
			</div>';

		
			$style = '';
			$overlay = '';
			if ($swal_popup_layout_style && $swal_popup_layout_style > 1 && $swal_popup_layout_style < 6) {
				if (($swal_popup_layout_style == 2 || $swal_popup_layout_style == 3) && $src) {
					$style = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
		$output .= '<div class="inner-text-ajax-forms'. $revlayout .'"'.$style.'>';
				if ($registertext || $swal_popup_layout_style == 4 || $swal_popup_layout_style == 5) {
					if ($swal_add_overlay_login) {
						$overlay = '<div class="sw-ajax-login-overlay-wrapper"></div>';
						}
			$output .= $overlay;
				$output .= '<div class="ajax-forms-main-text">'. $registertext;
					/**
					 *
					 * This hook fires after form text
					 *
					 */
					ob_start();
			    	do_action( 'swal_register_form_text' );
			    	$output .= ob_get_clean();
			    	
					$output .= '</div>';
					}
				$output .= '<div class="swal-clear"></div>
						</div>';
			}

		$output .= '<div class="swal-clear"></div>
			</div>';

		}
		return $output;
	
	}


/**
 *
 * Register Form Only
 *
 */

add_shortcode( 'swal_show_register_form_only', 'swal_show_register_form_only' );

function swal_show_register_form_only($atts='', $act = null) {

	$errors = new WP_Error();

	// normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    $value = shortcode_atts( array(
        'hidelink' => 'false',
    ), $atts );

	$output = '';
	$fields = '';

		$users_can_register         			= esc_attr(get_option('users_can_register',SWAL_DISABLE_NEW_USER_REGISTRATION));
		

		if (is_user_logged_in() && $act != 'ajax') {

			$output = swal_logged_in_message();
			return $output;

		} else if (!is_user_logged_in() && $users_can_register) {

		global $sw_login_json;

		$json = json_decode($sw_login_json);

		/**
		 *
		 * Get the options
		 *
		 */
		$swal_popup_layout_style			= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
		$swal_register_intro_text          	= esc_html(get_option('swal_register_intro_text',__(SWAL_REGISTER_INTRO_TEXT,'sw-ajax-login')));
		$swal_register_intro_text_link    	= esc_html(get_option('swal_register_intro_text_link',__(SWAL_REGISTER_INTRO_TEXT_LINK,'sw-ajax-login')));
		$swal_register_form_title     	 	= get_option('swal_register_form_title') ? esc_html(get_option('swal_register_form_title')) : __(SWAL_REGISTER_FORM_TITLE,'sw-ajax-login');
		$swal_register_button     	 	 	= get_option('swal_register_button') ? esc_html(get_option('swal_register_button')) : __(SWAL_REGISTER_BUTTON,'sw-ajax-login');
		$swal_register_form_type             = intval( get_option('swal_register_form_type', 0) );
		$swal_no_password_text               = get_option('swal_no_password_text') ? esc_html(get_option('swal_no_password_text')) : __(SWAL_NO_PASSWORD_TEXT,'sw-ajax-login');

		$swal_add_register_icons    			= intval(get_option('swal_add_register_icons'));
		$swal_add_register_password_showhide    = intval(get_option('swal_add_register_password_showhide'));
		$swal_disable_username_field         	= intval(get_option('swal_disable_username_field'));
		$swal_social_icons_position 		= intval(get_option('swal_social_icons_position',SWAL_SOCIAL_ICONS_POSITION));

		//Terms & Conditions
	    $swal_add_terms_link		= intval(get_option('swal_add_terms_link'));
	    $swal_termsconditions_link_to    = intval(get_option('swal_termsconditions_link_to'));
	    $swal_termsconditions_text      = esc_html(get_option('swal_termsconditions_text'));
	    $swal_termsconditionsintro_text      = esc_html(get_option('swal_termsconditionsintro_text'));

	    //if consent fields are empty use the default texts
	    if (!$swal_termsconditions_text) {
	    	$swal_termsconditions_text = esc_html__(SWAL_GDPR_CONSENT_TEXT,'sw-ajax-login');
	    }
	    if (!$swal_termsconditionsintro_text) {
	    	$swal_termsconditionsintro_text = esc_html__(SWAL_GDPR_CONSENTINTRO_TEXT,'sw-ajax-login');
	    }

	    $swal_add_recaptcha         = intval(get_option('swal_add_recaptcha'));
		$swal_recaptcha_version 	= intval( get_option('swal_recaptcha_version', 0) );
	    $swal_recaptcha_key         = esc_attr(get_option('swal_recaptcha_key'));

		// Redirect after signup
	    $page_to_redirect = swal_page_to_redirect_register();


	    /**
		 *
		 * Filter the register form classes
		 *
		 */
	    $additional_class = '';
		$additional_class = apply_filters('swal_register_form_additional_classes', $additional_class);

		$output .= '<form id="swal-register" class="ajax-auth'.$additional_class.'"  action="" method="post">';

		// Check if link to login must be hidden
		if ($value['hidelink'] == 'false' && $swal_popup_layout_style < 6 && ($swal_register_intro_text || $swal_register_intro_text_link)) {
			$output .= '<h3>'. $swal_register_intro_text .' <a href="'. wp_login_url() .'" class="pop_login sw-open-login">'. $swal_register_intro_text_link .'</a></h3>
					    <hr />';
		}
				    
				    	/**
						 *
						 * This hook fires before the form title
						 *
						 */
				    	ob_start();
				    	do_action( 'swal_forms_before_title' );
				    	$output .= ob_get_clean();
				 
						$output .= '<h2>'. $swal_register_form_title .'</h2>';

						ob_start();
				    	do_action( 'swal_register_form_before_input_fields' );
				    	$output .= ob_get_clean();
				    
				    	$show = '';
				    	$message = '';

				    	/**
						 *
						 * Display errors list, they are visible if JS is disabled
						 *
						 */
				    	if ($json) {

					    	if (!$json->loggedin) {
					    		$show = ' show-status';

					    		$message = '<ul>';
					    		foreach ($json->message as $key => $value) {

					    			$message .= '<li>'.esc_html($value).'</li>';
					    		}
					    		$message .= '</ul>';
					    		
					    	}
					    }

		$output .= '<div class="status'.$show.'">'. $message .'</div>
			    		<div id="swal_register_form_message" class="swal_confirm_msg"></div>';

			    // Whitelist the login nonce for Litespeed Cache plugin
			    if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
				    LiteSpeed_Cache_API::nonce_action( 'ajax-register-nonce' );
				}

			    $output .= wp_nonce_field('ajax-register-nonce', 'signonsecurity');

			    $inputicons = '';
			    if ($swal_add_register_icons) {
			    	$inputicons = ' swal-input-icons';	
			    }

			    $inputshowhide = '';
			    if ($swal_add_register_password_showhide) {
				    $inputshowhide = ' add_showhide';
				}
		
		$output .= '<input type="hidden" name="action" value="register"/>';

			    if ($page_to_redirect) {
			   	$output .= '<input type="hidden" id="swal_register_page_to_redirect" name="swal_register_page_to_redirect" value="'.$page_to_redirect.'"/>';
			    }
			
		$output .= '<div>
					    <div id="swal_wrapper_register_fields" class="swal-input-fields-wrapper">';

				/**
				 *
				 * Hide this field if the option is enabled
				 *
				 * @since 1.8.6
				 *
				 */
				if (!$swal_disable_username_field) {
					$fields .= '<label for="signonname" class="swal-label">'. esc_html__('Username','sw-ajax-login') .'</label>
							<div class="swal-field-wrapper'. $inputicons .'">   
							    <input placeholder="'. esc_html__('Username','sw-ajax-login') .'" id="signonname" type="text" name="signonname" class="required">';
								if ($swal_add_register_icons) {
						    	$fields .= '<i class="fa fa-user fa-lg" aria-hidden="true"></i>';
						    	}
					$fields .= '</div>';
				}
				
				
					$fields .= '<label for="email" class="swal-label">'. esc_html__('Email','sw-ajax-login') .'</label>
							<div class="swal-field-wrapper'. $inputicons .'">
							    <input placeholder="'. esc_html__('Email','sw-ajax-login') .'" id="email" type="text" class="required email" name="email">';
								if ($swal_add_register_icons) {
						    	$fields .= '<i class="fa fa-envelope fa-lg" aria-hidden="true"></i>';
						    	}
				$fields .= '</div>';

				/**
				 *
				 * Filter the register fields
				 *
				 */
				$fields = apply_filters('swal_register_form_fields', $fields, $inputicons);

		$output .= $fields;


		/**
		 *
		 * Check if has to add password fields
		 *
		 */

		if (!$swal_register_form_type) {

				$output .= '<label for="signonpassword" class="swal-label">'. esc_html__('Password','sw-ajax-login') .'</label>
							<div class="swal-field-wrapper'. $inputicons .'">
							    <input placeholder="'. esc_html__('Password','sw-ajax-login') .'" id="signonpassword" type="password" class="required'. $inputshowhide .'" name="signonpassword">';
								if ($swal_add_register_icons) {
						    	$output .= '<i class="fa fa-unlock fa-lg" aria-hidden="true"></i>';
						    	}
						    	if ($swal_add_register_password_showhide) {
						    	$output .= '<span toggle="#signonpassword" class="fa fa-lg fa-eye field-icon swal-toggle-password"></span>';
						    	}
				$output .= '</div>
							<label for="password2" class="swal-label">'. esc_html__('Confirm Password','sw-ajax-login') .'</label>
							<div class="swal-field-wrapper'. $inputicons .'">
							    <input placeholder="'. esc_html__('Confirm Password','sw-ajax-login') .'" type="password" id="password2" class="required" name="password2">';
								if ($swal_add_register_icons) {
						    	$output .= '<i class="fa fa-unlock fa-lg" aria-hidden="true"></i>';
						    	}
				$output .= '</div>';
			} 


				/**
				 * Fires extra fields in the user registration form.
				 *
				 * @since 1.2.2
				 */		
				ob_start();
		    	do_action( 'register_form_extra_fields' );
		    	$output .= ob_get_clean();
								

				

				/**
				 * Fires following the 'Email' field in the user registration form.
				 *
				 * @since 2.1.0
				 */
				ob_start();
		    	do_action( 'register_form' );
		    	$output .= ob_get_clean();

		    	/**
				 * Fires at the end of the new user account registration form.
				 *
				 * @since 3.0.0
				 *
				 * @param WP_Error $errors A WP_Error object containing 'user_name' or 'user_email' errors.
				 */
		    	ob_start();
				do_action( 'signup_extra_fields', $errors );
				$output .= ob_get_clean();
				

				if ($swal_add_terms_link) {

				//build the link
					if ($swal_termsconditions_link_to) {
						$swal_termsconditionsintro_text = '<a href="'.get_page_link($swal_termsconditions_link_to).'" title="'.$swal_termsconditionsintro_text.'">'.$swal_termsconditionsintro_text.'</a>';
					}


		$output .= '<div class="privacy-policy-consent-container swal-clear">
					<div class="checkboxStyle">
						<input name="swal_privacy_policy_consent" id="swal_privacy_policy_consent" value="1" type="checkbox" class="required"/>
						<label for="swal_privacy_policy_consent"></label>
					</div>
				    <label for="swal_privacy_policy_consent" class="gdpr-consent-label">'. $swal_termsconditions_text .' '. $swal_termsconditionsintro_text .'</label>
				</div>';
			    	}	

				if ($swal_add_recaptcha) {

		$output .= '<input type="hidden" id="swal_recaptcha_register_version" name="swal_recaptcha_register_version" value="'. $swal_recaptcha_version .'"/>';

					// Check reCAPTCHA version to add
					if (!$swal_recaptcha_version) {
			
		$output .= '<div class="swal-clear recaptcha-container">
				        <div id="myCaptcha" class="g-recaptcha" data-sitekey="'. $swal_recaptcha_key .'"></div>
				    </div>';
			
					}
			    }
		/**
		 *
		 * If it's form type without password fields shows the instructions
		 *
		 */
		if ($swal_register_form_type)  {

				$output .= '<div class="swal-instruction-text">'. $swal_no_password_text . '</div>';
			}
			
		$output .= '<div class="swal-clear">
						<input type="hidden" id="wp-submit" name="wp-submit" value="1"/>
						<button class="submit_button" type="submit" value="'. $swal_register_button .'">'. $swal_register_button .'</button>
				    </div>';

				    /**
					 *
					 * This hook fires after register submit button
					 *
					 */
					ob_start();
			    	do_action( 'swal_register_after_submit_button' );
			    	$output .= ob_get_clean();

		$output .= '</div><!-- Close swal_wrapper_register_fields -->

			</div>

				<div class="swal-clear"></div>';
				
					/**
					 *
					 * This hook fires at the end of the forms
					 *
					 */
					ob_start();
			    	do_action( 'swal_register_end_of_form' );
			    	$output .= ob_get_clean();

					ob_start();
			    	do_action( 'swal_end_of_forms' );
			    	$output .= ob_get_clean();

			 

		$output .= '</form>';
		}

		return $output;
}


/**
 *
 * Forgot Password Form
 *
 */

add_shortcode( 'swal_show_forgot_password_form', 'swal_add_wrapper_to_forgot_password_form' );

/**
 *
 * Add a wrapper to forgot password when showed by shortcode
 * It's necessary to indentify which form is by shortcode
 *
 * @since 1.8.6
 *
 */
function swal_add_wrapper_to_forgot_password_form($act = null) {

	$output = '<div id="swal-forgot-password-form-wrapper">';
	$output .= swal_show_forgot_password_form($act);
	$output .= '</div>';

	return $output;

}

function swal_show_forgot_password_form($act = null) {

	$output = '';

		if (is_user_logged_in() && $act != 'ajax') {
			$output .= esc_html__( 'You are already logged in, to recover your password you have first to logout.', 'sw-ajax-login' );
			return $output;

		} else if (!is_user_logged_in()) {


		/**
		 *
		 * Get the options
		 *
		 */
		$swal_popup_layout_style	= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
		$swal_add_overlay_login		= intval(get_option('swal_add_overlay_login',SWAL_ADD_OVERLAY_LOGIN));

		$options           			= esc_attr(get_option('swal_ajax_forgot_password_background'));
		$forgotpasswordtext         = wpautop(html_entity_decode(strip_tags(get_option('swal_forgot_password_login'))));

		/**
		 *
		 * Here you can filter the form text
		 *
		 */
		$forgotpasswordtext 		= apply_filters('swal_forgotpassword_text', $forgotpasswordtext);

		$image_attributes = wp_get_attachment_image_src( $options, 'large');
		$alignment 			= swal_alignment_radiobuttons(intval(get_option('swal_password_bg_image_alignment')));
        $src = $image_attributes ? $image_attributes[0] : '';
        $style_full_background = '';
        $style_single_background = '';
        $overlay = '';
        $overlaytextcolor = '';

        if ($swal_popup_layout_style == 1 || $swal_popup_layout_style == 7) {
        	if ($src) {
					$style_single_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
					if ($swal_add_overlay_login) {
						$overlay = ' sw-ajax-login-overlay';
						$overlaytextcolor = ' sw-ajax-login-text-contrast';
						}
					}
        if ($swal_popup_layout_style == 4 || $swal_popup_layout_style == 5) {
        	if ($src) {
					$style_full_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
					$style_full_background .= ' class="icon-close-contrast"';
					if ($swal_add_overlay_login) {
						$overlay = ' sw-ajax-login-overlay';
						$overlaytextcolor = ' sw-ajax-login-text-contrast';
						}
					}
		//layout with image on right
		$revlayout = '';
		if ($swal_popup_layout_style == 3 || $swal_popup_layout_style == 5) {
			$revlayout = ' ajax-forms-rev';
		}
			
	$output .= '<div id="wrapper-forgot_password"'. $style_full_background .'>
				<div class="inner-form-ajax-forms'. $revlayout .'"' . $style_single_background .'>
					<div class="inner-form-wrapper'. $overlay . $overlaytextcolor .'">';

				/**
				 *
				 * Append Forgot Password Form
				 *
				 */			
				$output .= swal_show_forgot_password_form_only($act);

	$output .= '</div>
			</div>';


			$style = '';
			$overlay = '';
			if ($swal_popup_layout_style && $swal_popup_layout_style > 1 && $swal_popup_layout_style < 6) {
				if ($swal_popup_layout_style == 2 || $swal_popup_layout_style == 3) {
					$style = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
					}
			$output .= '<div class="inner-text-ajax-forms'. $revlayout .'"'.$style.'>';
				if ($forgotpasswordtext || $swal_popup_layout_style == 4 || $swal_popup_layout_style == 5) {
					if ($swal_add_overlay_login) {
						$overlay = '<div class="sw-ajax-login-overlay-wrapper"></div>';
						}
					$output .= $overlay;
					$output .= '<div class="ajax-forms-main-text">';
					$output .= $forgotpasswordtext;

					/**
					 *
					 * This hook fires at the end of the form text
					 *
					 */
					ob_start();
			    	do_action( 'swal_forgotpassword_form_text' );
			    	$output .= ob_get_clean();

					$output .= '</div>';
					}
					$output .= '<div class="swal-clear"></div>';
			$output .= '</div>';
			}
		
			$output .= '<div class="swal-clear"></div>
		</div>';

		}
		return $output;
	}



/**
 *
 * Forgot Password Form Only
 *
 */

add_shortcode( 'swal_show_forgot_password_form_only', 'swal_show_forgot_password_form_only' );

function swal_show_forgot_password_form_only($atts='', $act = null) {

	// normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    $value = shortcode_atts( array(
        'hidelink' => 'false',
    ), $atts );

	$output = '';

		if (is_user_logged_in() && $act != 'ajax') {
			$output .= '<p>'.esc_html__( 'You are already logged in, to recover your password you have first to logout.', 'sw-ajax-login' ).'</p>';
			return $output;

		} else if (!is_user_logged_in()) {

		global $sw_login_json;
		$json = json_decode($sw_login_json);

		/**
		 *
		 * Get the options
		 *
		 */
		$swal_popup_layout_style			 = intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
		$swal_forgot_pwd_intro_text          = esc_html(get_option('swal_forgot_pwd_intro_text',__(SWAL_FORGOT_PWD_INTRO_TEXT,'sw-ajax-login')));
		$swal_forgot_pwd_intro_text_link     = esc_html(get_option('swal_forgot_pwd_intro_text_link',__(SWAL_FORGOT_PWD_INTRO_TEXT_LINK,'sw-ajax-login')));
		$swal_forgot_pwd_form_title     	 = get_option('swal_forgot_pwd_form_title') ? esc_html(get_option('swal_forgot_pwd_form_title')) : __(SWAL_FORGOT_PWD_FORM_TITLE,'sw-ajax-login');
		$swal_forgot_pwd_button     	 	 = get_option('swal_forgot_pwd_button') ? esc_html(get_option('swal_forgot_pwd_button')) : __(SWAL_FORGOT_PWD_BUTTON,'sw-ajax-login');
		$swal_add_forgot_password_icons      = intval(get_option('swal_add_forgot_password_icons'));

		$output .= '<form id="forgot_password" class="ajax-auth" action="" method="post">';

		// Check if link to login must be hidden
		if ($value['hidelink'] == 'false' && $swal_popup_layout_style < 6 && ($swal_forgot_pwd_intro_text || $swal_forgot_pwd_intro_text_link)) {
			$output .= '<h3>'. $swal_forgot_pwd_intro_text .' <a href="'. wp_login_url() .'" class="pop_login sw-open-login">'. $swal_forgot_pwd_intro_text_link .'</a></h3>
					    <hr />';
		}
			    
			    	/**
					 *
					 * This hook fires before the form title
					 *
					 */
			    	ob_start();
			    	do_action( 'swal_forms_before_title' );
			    	$output .= ob_get_clean();

					$output .= '<h2>'. $swal_forgot_pwd_form_title .'</h2>';

					ob_start();
			    	do_action( 'swal_forgot_password_form_before_input_fields' );
			    	$output .= ob_get_clean();
			
				    	$show = '';
				    	$message = '';

				    	if ($json) {
					    	if (!$json->loggedin) {
					    		$show = ' show-status';
					    	} else {
					    		$show = ' show-status success';
					    	}
					    	$message = $json->message;
					    }

				$output .= '<p class="status'.$show.'">'. $message .'</p>';

				// Whitelist the login nonce for Litespeed Cache plugin
			    if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
				    LiteSpeed_Cache_API::nonce_action( 'ajax-forgot-nonce' );
				}

				$output .= wp_nonce_field('ajax-forgot-nonce', 'forgotsecurity');

				    $inputicons = '';
				    if ($swal_add_forgot_password_icons) {
				    	$inputicons = ' class="swal-input-icons"';	
				    }

		$output .= '<input type="hidden" name="action" value="forgot_password"/>
				    <div class="swal-input-fields-wrapper">
				    	<label for="user_login" class="swal-label">'. esc_html__('Username or Email','sw-ajax-login') .'</label>
					    <div'. $inputicons .'>
						    <input placeholder="'. esc_html__('Username or Email','sw-ajax-login') .'" id="user_login" type="text" class="required" name="user_login">';
					   		if ($swal_add_forgot_password_icons) {
					    	$output .= '<i class="fa fa-envelope fa-lg" aria-hidden="true"></i>';
					    	}
			$output .= '</div>
					</div>';
				
					/**
					 * Fires inside the lostpassword form tags, before the hidden fields.
					 *
					 * @since 2.1.0
					 */
					ob_start();
			    	do_action( 'lostpassword_form' );
			    	$output .= ob_get_clean();
		
			$output .= '<div class="swal-clear">
					   		<button class="submit_button" type="submit" value="'. $swal_forgot_pwd_button .'">'. $swal_forgot_pwd_button .'</button>
					    </div>

					<div class="swal-clear"></div>';
					
						/**
						 *
						 * This hook fires at the end of the forms
						 *
						 */
						ob_start();
				    	do_action( 'swal_forgot_password_end_of_form' );
				    	$output .= ob_get_clean();

						ob_start();
				    	do_action( 'swal_end_of_forms' );
				    	$output .= ob_get_clean();
		
			$output .= '</form>';
		}

		return $output;
}



/**
 *
 * Reset Password
 *
 */

add_shortcode( 'swal_show_reset_password_form', 'swal_show_reset_password_form' );

function swal_show_reset_password_form() {

	$output = '';

	global $sw_login_json;
	$json = json_decode($sw_login_json);

	//if user is already logged show the message and return function
	if (is_user_logged_in()) {

			$output .= esc_html__('You are already logged in, to recover your password you have first to logout.','sw-ajax-login');
			return $output;

		} else {

			if (!isset($_GET['key']) || !isset($_GET['login'])) {
					$output .= '<p class="status show-status error">'. esc_html__('Sorry, you need a valid key and login.','sw-ajax-login').'</p>';
					
					//show forgot password form
					$output .= swal_show_forgot_password_form();

					return $output;
			}

		$swal_min_password_length           = intval(get_option('swal_min_password_length',SWAL_MIN_PASSWORD_LENGTH));




	$output .= '<div id="resetPassword">
					<!--this check on the link key and user login/username-->';
				
				$errors = new WP_Error();
				$user = check_password_reset_key($_GET['key'], $_GET['login']);

				if ( is_wp_error( $user ) ) {
					if ( $user->get_error_code() === 'expired_key' )
						$errors->add( 'expiredkey', __( 'Sorry, that key has expired. Please try again.','sw-ajax-login' ) );
					else
						$errors->add( 'invalidkey', __( 'Sorry, that key does not appear to be valid.','sw-ajax-login' ) );
				}

				// display error message and Forgot Password form instead of Reset password
				if ( $errors->get_error_code() ) {
					$output .= '<p class="status show-status error">'. $errors->get_error_message( $errors->get_error_code() ).'</p>';
					$output .= swal_show_forgot_password_form();
					return $output;
					}


	$output .= '<form id="resetPasswordForm" class="ajax-auth" method="post">
					<h2>'. esc_html__('Reset Password','sw-ajax-login') .'</h2>';

			    	$show = '';
			    	$message = '';

			    	if ($json) {
				    	if (!$json->loggedin) {
				    		$show = ' show-status';
				    	} else {
				    		$show = ' show-status success';
				    	}
				    	$message = $json->message;
				    }

			$output .= '<p id="message" class="status'.$show.'">'. $message .'</p>';

			// Whitelist the reset password nonce for Litespeed Cache plugin
			    if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
				    LiteSpeed_Cache_API::nonce_action( 'rs_user_reset_password_action' );
				}
						// this prevent automated script for unwanted spam
			$output .= wp_nonce_field( 'rs_user_reset_password_action', 'resetsecurity' );

			$output .= '<input type="hidden" name="action" value="resetpassword"/>

					<input type="hidden" name="user_key" id="user_key" value="'. esc_attr( $_GET['key'] ) .'" />
					<input type="hidden" name="user_login_reset" id="user_login_reset" value="'. esc_attr( $_GET['login'] ) .'" />

					<div class="swal-input-fields-wrapper">
					<div>
						<label for="pass1" class="swal-label">'. esc_html__('New password','sw-ajax-login') .'</label>
						<input placeholder="'. esc_html__('New password','sw-ajax-login') .'" type="password" name="pass1" id="pass1" class="required" value="" autocomplete="off" />
					</div>
					<div>
						<label for="pass2" class="swal-label">'. esc_html__('Confirm new password','sw-ajax-login') .'</label>
						<input placeholder="'. esc_html__('Confirm new password','sw-ajax-login') .'" type="password" name="pass2" id="pass2" class="required" value="" autocomplete="off" />
					</div>
					</div>
					<div id="password-strength"></div>
					<p class="description indicator-hint">';

						if (!$swal_min_password_length) {
							$output .= esc_html__('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).','sw-ajax-login');
						} else {
							$output .= sprintf( esc_html__( 'Hint: The password has to be at least %d characters long. ', 'sw-ajax-login' ), $swal_min_password_length );
							$output .= esc_html__('To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).','sw-ajax-login');
						}
					 	
		$output .= '</p>

					<br class="swal-clear" />';

					/**
					 * Fires following the 'Strength indicator' meter in the user password reset form.
					 *
					 * @since 3.9.0
					 *
					 * @param WP_User $user User object of the user whose password is being reset.
					 */
					ob_start();
			    	do_action( 'resetpass_form', $user );
			    	$output .= ob_get_clean();
					
					
		$output .= '<div class="swal-clear">
						<button class="submit_button" type="submit" name="wp-submit" id="wp-submit" value="'. esc_html__('Reset Password','sw-ajax-login') .'">'. esc_html__('Reset Password','sw-ajax-login') .'</button>
					</div>
				</form>
			</div>';
	}
	return $output;
}



/**
 *
 * Logout
 *
 */

add_shortcode( 'swal_show_logout_form', 'swal_show_logout_form' );

function swal_show_logout_form() {

	$output = '';

		if (is_user_logged_in()) {

			$swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
  			$swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');

			$swal_popup_layout_style	= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
			$swal_add_overlay_logout		= intval(get_option('swal_add_overlay_logout'));
			//logout background image
			$options           = esc_attr(get_option('swal_ajax_logout_background'));
			$logouttext       = wpautop(html_entity_decode(strip_tags(get_option('swal_logout_login'))));

			$logouttext 		= apply_filters('swal_logout_text', $logouttext);

			$image_attributes = wp_get_attachment_image_src( $options, 'large');
			$alignment 			= swal_alignment_radiobuttons(intval(get_option('swal_logout_bg_image_alignment')));
	        $src = $image_attributes ? $image_attributes[0] : '';
			$style_full_background = '';
	        $overlay = '';
	        $overlaytextcolor = '';

	        if ($swal_popup_layout_style && $swal_popup_layout_style < 6) {

	        	if ($src) {
							$style_full_background = ' style="background: url('.$src.') '.$alignment.'; background-size: cover;"';
							}
						
						if ($swal_add_overlay_logout) {
							
							$overlay = ' sw-ajax-login-overlay';
							$overlaytextcolor = ' sw-ajax-login-text-contrast';
							if ($swal_add_overlay_logout) { 
								$overlay = ' sw-ajax-logout-overlay';
								$overlaytextcolor = ' sw-ajax-logout-text-contrast';
							}
							}
						}

			//global $page_to_redirect_logout;

			//get redirect to
			$location = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

			if (isset($_POST['redirect_to'])) {
		    	$location = $_POST['redirect_to'];
		    }


		    //impostazione per redirect pagina dopo il logout
		    $swal_redirect_after_logout           = intval(get_option('swal_redirect_after_logout',SWAL_REDIRECT_AFTER_LOGOUT));
			$swal_custom_redirect_after_logout    = esc_attr(get_option('swal_custom_redirect_after_logout'));
	    	$swal_custom_redirect_after_logout    = ltrim($swal_custom_redirect_after_logout, '/');

			$page_to_redirect = home_url();
			if ($swal_redirect_after_logout == 1) {
				$page_to_redirect = $location;
			} else if ($swal_redirect_after_logout == 2) {
				$page_to_redirect = esc_url(home_url($swal_custom_redirect_after_logout));
			}


	
	$output .= '<div'. $style_full_background .'>
				<div class="wrapper-logout'. $overlay .'">
					<div class="ajax-auth'. $overlaytextcolor .'">
					'. $logouttext .'
					<p><a href="'. wp_logout_url( $page_to_redirect ) .'" id="logout-button" class="submit_button">'. $swal_menu_item_logout_custom_text .'</a> <a href="#" class="swal-clear text-link float-none margin-top close-popup">'. esc_html__('Cancel','sw-ajax-login') .'</a></p>

					</div>
				</div>
			</div>';

			}
	return $output;
	}


/**
 *
 * Mailster: Check if Mailster plugin class exists, then filter the checkbox in register form
 *
 **/
if ( class_exists( 'MailsterSubscribers' ) ) {
	add_filter( 'mailster_register_form_signup_field', 'swal_mailster_register_form_signup_field', 10, 1 );
}

function swal_mailster_register_form_signup_field($output) {

	$output = '<div class="swal-radio-wrapper">
					<div class="swal-clear">
		                <div class="checkboxStyle">
			                <input type="checkbox" id="swalmailster_user_newsletter_signup" name="mailster_user_newsletter_signup" value="1" '. checked( mailster_option( 'register_signup_checked' ), true, false ) . ' />
			                <label for="swalmailster_user_newsletter_signup"></label>
			            </div><label for="swalmailster_user_newsletter_signup">' . mailster_text( 'newsletter_signup' ) . '</label>
		       		</div>
		       	</div>';
	
	return $output;
}


/**
 *
 * Logged in message for Login, register, forgot password pages
 *
 **/
function swal_logged_in_message() {
	$output = '<p>'. esc_html__( 'You are logged in as {USERNAME}. Not you?', 'sw-ajax-login' ) .' <a href="'.swal_logout_url().'" class="open_logout">'. esc_html__( 'Logout', 'sw-ajax-login' ) .'</a></p>';

	$args = array(
					'username'	=> swal_get_user_menu_item_content(false),
					);
	$output = swal_replace_placeholders($output,$args);
	return $output;
}


?>