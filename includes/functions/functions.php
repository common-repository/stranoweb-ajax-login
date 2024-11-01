<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}



// Add login buttons to WooCommerce login form
add_action( 'woocommerce_login_form', 'swal_add_socials_login_buttons_to_woocommerce',101);

// Add support to shortcodes to form text
add_filter('swal_login_text', 'do_shortcode');
add_filter('swal_register_text', 'do_shortcode');
add_filter('swal_forgotpassword_text', 'do_shortcode');
add_filter('swal_logout_text', 'do_shortcode');


function swal_add_socials_login_buttons_to_woocommerce()
{
    $swal_add_social_login_to_woocommerce       = esc_attr(get_option('swal_add_social_login_to_woocommerce'));
    if ($swal_add_social_login_to_woocommerce) {
    	swal_add_socials_login_buttons();
    }
}
 


function swal_ajax_logout(){

	echo json_encode(array(
				'loggedin'=>true, 
				'message'=> __('Logout successful, redirecting...','sw-ajax-login'),
				)); 
	wp_clear_auth_cookie();
    die();
}





/**
 *
 * NO AJAX functions
 *
 * The following function groups the 4 sub functions for Login, Register, Lost Password, Reset Password
 *
 */
add_action( 'init', 'swal_auth_user_no_ajax' );

function swal_auth_user_no_ajax() {

	/**
	 * Check if has to disable nonce as workaround for some caching plugins
	 *
	 * Since version: 1.6.0
	 *
	 */ 
	//$swal_disable_nonces                = intval(get_option('swal_disable_nonces'));
	$swal_enable_nonces                = intval(get_option('swal_enable_nonces'));

	/**
	 * Verify if the request arrive via POST
	 */ 
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

		$action = $_POST['action'];

		/**
		 * Login function
		 */ 

		if ( $action == 'ajaxlogin' || $action == 'login') {
			//if function is called from normal POST request check nonce in the regular way
			if ( $action == 'ajaxlogin' ) {

				// check the nonce in case of ajax request
				if ($swal_enable_nonces) {
					check_ajax_referer( 'ajax-login-nonce', 'swal_login_security' );
				}

			} else if ( $action == 'login' ) {

				$nonce = $_POST['swal_login_security'];
				if ($swal_enable_nonces) {
					if ( ! wp_verify_nonce( $nonce, 'ajax-login-nonce' ) ) {
					     return; 
					}
				}

			}

			auth_user_login_no_ajax($action,$_POST['log'],$_POST['pwd'],'login');
		}

		/**
		 * Register function
		 */
		if ( $action == 'ajaxregister' || $action == 'register') {
			swal_register_no_ajax($action);
		}

		/**
		 * Forgot Password function
		 */
		if ( $action == 'ajaxforgotpassword' || $action == 'forgot_password') {
			ajax_forgotPassword();
		}

		/**
		 * Reset Password function
		 */
		if ( $action == 'ajaxresetpassword' || $action == 'resetpassword') {
			reset_pass_callback();
		}

	}

}

/**
 *
 * Login function for no ajax login
 */
function auth_user_login_no_ajax($action,$user_login,$password,$login,$nologin = false) {


		//first clear all auth cookies
		wp_clear_auth_cookie();

		$swal_login_successful_text           = get_option('swal_login_successful_text') ? esc_attr(get_option('swal_login_successful_text')) : __(SWAL_LOGIN_SUCCESSFUL_TEXT,'sw-ajax-login');
	    $swal_register_successful_text        = get_option('swal_register_successful_text') ? esc_attr(get_option('swal_register_successful_text')) : __(SWAL_REGISTER_SUCCESSFUL_TEXT,'sw-ajax-login');
	    $swal_redirecting_text                = get_option('swal_redirecting_text') ? esc_attr(get_option('swal_redirecting_text')) : __(SWAL_REDIRECTING_TEXT,'sw-ajax-login');

		switch ($login) {
			case 'registration':
				$success_text = $swal_register_successful_text;
				break;
			
			default: // default is 'login'
				$success_text = $swal_login_successful_text;
				break;
		}

		//if login is called by ajax call and user is already logged-in die the function
	    if ( is_user_logged_in() && $action == 'ajaxlogin' ) {
	    	$output = json_encode(array(
				'loggedin'=>true, 
				'message'=> $success_text.', '.$swal_redirecting_text,
				));
			die($output);
			return;
		}

		/**
		 * IMPORTANT
		 *
		 * check if need to perform login (there's an option on register to disable auto login)
		 *
		 */
		if ($nologin) {
			$output = json_encode(array(
				'loggedin'=>true, 
				'message'=> $success_text,
				));
			$GLOBALS['sw_login_json'] = $output;
			die($output);
			return;

		}

		/**
	     *
	     * If no username option is enabled accept only valid email as username,
	     * otherwise return error
	     *
	     * @since 1.8.6
	     *
	     */
	    $swal_disable_username_field            = intval(get_option('swal_disable_username_field'));

	    if ( $swal_disable_username_field && !is_email( $user_login ) ) {
	    	$output = json_encode(array(
				'loggedin'=>false, 
				'message'=> __('You entered a not valid email address','sw-ajax-login'),
				));
			die($output);
			return;
		}



		//get remember credentials option
		$swal_login_remember_credentials     = intval(get_option('swal_login_remember_credentials',SWAL_LOGIN_REMEMBER_CREDENTIALS));


		$info = array();
	    $info['user_login'] = $user_login;
	    $info['user_password'] = $password;

	    //check if it has to remember credentials
	    if ($swal_login_remember_credentials == 0) {
	    	$info['remember'] = true;
	   	} else if ($swal_login_remember_credentials == 1) {
	    	$info['remember'] = false;
	   	} else if ($swal_login_remember_credentials == 2) {
	   		if (isset($_POST['rememberme'])) {
	   			if ($_POST['rememberme']) {
	   				$info['remember'] = true;
	   			} else {
	   				$info['remember'] = false;
	   			}
	   			
	   		}
	    	
	   	}
		
	   	// Login
		$user_signon = wp_signon( $info );

	    if ( is_wp_error($user_signon) ){

	    	$error_string = $user_signon->get_error_message();

			$output = json_encode(array(
				'loggedin'=>false,
				'message'=> __($error_string,'sw-ajax-login')
				));
	    } else {
	    	wp_set_current_user($user_signon->ID);
		    // The next line *really* seemed to help!
		    do_action('set_current_user');
			

			$output = json_encode(array(
				'loggedin'=>true, 
				'message'=> $success_text.', '.$swal_redirecting_text,
				));

			// Get redirect settings
			$page_to_redirect = swal_page_to_redirect();
			
			//if login arrive from no ajax request then redirect
			if ( $action == 'login' ) {

				wp_safe_redirect($page_to_redirect);
				exit();

			}
	    }


	    //if login is called by ajax call die the function
	    if ( $action == 'ajaxlogin' ) {

			die($output);

		} else if ( $action == 'login' ) {

			$GLOBALS['sw_login_json'] = $output;
		}
}


/**
 *
 * Register function for no ajax login
 */
function swal_register_no_ajax($action) {

    $errors = new WP_Error();


	$swal_add_terms_link    			= intval(get_option('swal_add_terms_link'));
	$swal_register_no_email_to_user     = intval( get_option('swal_register_no_email_to_user', 0) );
	$swal_register_form_type            = intval( get_option('swal_register_form_type', 0) );

	/**
	 * Check if has to disable nonce as workaround for some caching plugins
	 *
	 * @since 1.6.0
	 *
	 */ 
	//$swal_disable_nonces                = intval(get_option('swal_disable_nonces'));
	$swal_enable_nonces                = intval(get_option('swal_enable_nonces'));

	
	//if function is called from normal POST request check nonce in the regular way
	if ( $action == 'ajaxregister' ) {

		// check the nonce in case of ajax request, die if not valid
		if ($swal_enable_nonces) {
			check_ajax_referer( 'ajax-register-nonce', 'signonsecurity' );
		}


	} else if ( $action == 'register' ) {

		$nonce = $_POST['signonsecurity'];
		if ($swal_enable_nonces) {
			if ( ! wp_verify_nonce( $nonce, 'ajax-register-nonce' ) ) {
			     return; 
			}
		}
	}



	/**
     *
     * If username field is disabled create the username by the first part of email address
     * If the username already exists add incremental number as suffix.
     *
     * @since 1.8.6
     *
     */
    $swal_disable_username_field            = intval(get_option('swal_disable_username_field'));

    // Nonce is checked, get the POST data and sign user on
	$user_email 			= sanitize_email( $_POST['email']);

    $sanitized_user_login = !$swal_disable_username_field ? sanitize_user($_POST['signonname']) : swal_create_username_from_email( $user_email );


	$info = array();

  	$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = $sanitized_user_login;
    $info['user_pass'] = isset($_POST['signonpassword']) ? sanitize_text_field($_POST['signonpassword']) : '';
    $info['user_pass2'] = isset($_POST['password2']) ? sanitize_text_field($_POST['password2']) : '';


	/**
	 * Filters the email address of a user being registered.
	 *
	 * @since WP 2.1.0
	 *
	 * @param string $user_email The email address of the new user.
	 */
	$info['user_email'] = apply_filters( 'user_registration_email', $user_email );

	$user_gdpr_consent = !empty($_POST['swal_privacy_policy_consent']) ? intval( $_POST['swal_privacy_policy_consent']) : '';


	// Get redirect settings
	$page_to_redirect = swal_page_to_redirect();

	$swal_min_password_length           = intval(get_option('swal_min_password_length',SWAL_MIN_PASSWORD_LENGTH));
	$swal_add_recaptcha                 = intval(get_option('swal_add_recaptcha'));
	

	/**
	 *
	 * Check for errors
	 *
	 */

	//$output = array();

		if ( !$info['user_login'] ) {
				$message = __('You have to enter a username.','sw-ajax-login');
	        	$errors->add( 'empty_username', $message );
	    }  elseif ( ! validate_username( $info['user_login'] ) ) {
	    		$message = __('This username is invalid because it uses illegal characters. Please enter a valid username.','sw-ajax-login');
				$errors->add( 'invalid_username', $message );
				$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = $sanitized_user_login = '';
		}
		if ( !is_email( $info['user_email'] )) {
				$message = __('You have to enter a valid email address.','sw-ajax-login');
	        	$errors->add( 'invalid_email', $message );
	    } 
	    if ( (strlen( $info['user_pass'] ) < $swal_min_password_length) && !$swal_register_form_type) {
	    		$message = sprintf(__('Password must be at least %d characters long.','sw-ajax-login'),$swal_min_password_length);
	        	$errors->add( 'too_short_password', $message );
	    }
	    if ( $swal_add_recaptcha && !swal_verify_recaptcha() ) {
        	// Recaptcha check failed, display error
        		$message = __('reCAPTCHA error','sw-ajax-login');
    	 		$errors->add( 'invalid_recaptcha', $message );
    	}
    	if ( $swal_add_terms_link && !$user_gdpr_consent ) {
        	// GDPR check failed, display error
        		$message = __('You have to agree to the use of your personal data','sw-ajax-login');
    	 		$errors->add( 'invalid_gdpr', $message );
	    }
	    if ( ($info['user_pass'] != $info['user_pass2']) && !$swal_register_form_type) {
	    	// Passwords don't match
	    		$message = __('The passwords do not match.','sw-ajax-login');
	        	$errors->add( 'passwords_dont_match', $message );

	    }


	/**
	 * Filters the errors encountered when a new user is being registered.
	 *
	 * The filtered WP_Error object may, for example, contain errors for an invalid
	 * or existing username or email address. A WP_Error object should always be returned,
	 * but may or may not contain errors.
	 *
	 * If any errors are present in $errors, this will abort the user's registration.
	 *
	 * @since 2.1.0
	 *
	 * @param WP_Error $errors               A WP_Error object containing any errors encountered
	 *                                       during registration.
	 * @param string   $sanitized_user_login User's username after it has been sanitized.
	 * @param string   $user_email           User's email.
	 */
	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->has_errors() ) {

		$output = json_encode(array('loggedin'=>false, 'message'=> $errors->get_error_messages()));

		// If there are errors encode into json
	    if ( $action == 'ajaxregister' ) {

			die($output);

		} else if ( $action == 'register' ) {

			$GLOBALS['sw_login_json'] = $output;
		}

		return $errors;
	}

	

	/**
	 *
	 * No errors then proceed
	 *
	 */


		// Generate random password if register form is without password fields
		if ($swal_register_form_type) {
			$info['user_pass'] = wp_generate_password();
		}
	
	    	
		// Register the user
    	$user_register = wp_insert_user( $info );

    	if ( is_wp_error($user_register) ){	

    		$errors  = $user_register->get_error_messages();
				
				$output = json_encode(array('loggedin'=>false, 'message'=> $errors));

				//if login is called by ajax call die the function
			    if ( $action == 'ajaxregister' ) {

						die($output);

				} else if ( $action == 'register' ) {

					$GLOBALS['sw_login_json'] = $output;
				}

				return $errors;
		} 

	 	/**
    	 *
    	 * adds meta user 'user_gdpr_consent'
    	 *
    	 */
	 	update_user_meta( $user_register, 'user_gdpr_consent', $user_gdpr_consent );

	 	/**
    	 *
    	 * adds action for extra content
    	 *
    	 */
	 	//do_action( 'user_register', $user_id );

    	/**
    	 *
    	 * send an email to the administrators and to the new user (if option enabled)
    	 *
    	 */
    	wp_new_user_notification( $user_register );
    	
    	// if not disabled send email to the new user
    	if (swal_send_default_email_to_user_after_registration()) {

	    	$to = $info['user_email'];
	    	$subject = swal_new_user_email_body_subject($info['user_login']);
			
			// Check which email template send out
			if (!$swal_register_form_type) {
				// Default SWAL email template
				$message = swal_create_custom_email(swal_new_user_email_body($info['user_login']));
			} else {
				// Random password template
				$message = swal_create_custom_email(swal_new_user_no_password_email_body($info['user_login'],$info['user_pass']));
			}
		
			$headers = swal_create_headers();

	    	wp_mail( $to, $subject, $message, $headers );
	    }

	    // Check for autologin, if have to use username or email
	    $username = $swal_disable_username_field ? $user_email : $info['nickname'];

    	//if registration is ok then login depending on ajax or no-ajax request
    	if ( $action == 'ajaxregister' ) {


     	 	auth_user_login_no_ajax('ajaxlogin',$username,$info['user_pass'],'registration', swal_register_no_autologin());
     	} else if ( $action == 'register' ) {


     	 	auth_user_login_no_ajax('login',$username,$info['user_pass'],'registration', swal_register_no_autologin());
     	}     

	

    	return;
}


/**
 *
 * Check if has to send default email to user after registration
 * return true if no email out or user validation (option 2 or 3) are enabled
 *
 * @since 1.8.0
 *
 * @return bool false = send / true = not send
 */
function swal_send_default_email_to_user_after_registration() {

	$swal_register_no_email_to_user     = intval( get_option('swal_register_no_email_to_user', 0) );

	if ($swal_register_no_email_to_user || swal_is_validation_enabled() >= 2) {
		return false;
	}

	return true;
}


/**
 *
 * Forgot Password function
 *
 */

function ajax_forgotPassword(){

	/**
	 * Check if has to disable nonce as workaround for some caching plugins
	 *
	 * Since version: 1.6.0
	 *
	 */ 
	//$swal_disable_nonces                = intval(get_option('swal_disable_nonces'));
	$swal_enable_nonces                = intval(get_option('swal_enable_nonces'));

	//if request doesn't come from POST method die 
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

		$action = $_POST['action'];
	} else {
		die();
	}
	 
	 //if function is called from normal POST request check nonce in the regular way
	if ( $action == 'ajaxforgotpassword' ) {

		// First check the nonce, if it fails the function will break
		if ($swal_enable_nonces) {
	    	check_ajax_referer( 'ajax-forgot-nonce', 'security' );
	    }

	} else if ( $action == 'forgot_password' ) {

		$nonce = $_POST['forgotsecurity'];
		if ($swal_enable_nonces) {
			if ( ! wp_verify_nonce( $nonce, 'ajax-forgot-nonce' ) ) {
			     return; 
			}
		}

	}
	
	global $wpdb;
	
	$account = $_POST['user_login'];
	
	if( empty( $account ) ) {
		$error = __('Enter an username or e-mail address.','sw-ajax-login');
	} else {
		if(is_email( $account )) {
			if( email_exists($account) ) 
				$get_by = 'email';
			else	
				$error = __('There is no user registered with that email address.','sw-ajax-login');			
		}
		else if (validate_username( $account )) {
			if( username_exists($account) ) 
				$get_by = 'login';
			else	
				$error = __('There is no user registered with that username.','sw-ajax-login');				
		}
		else
			$error = __('Invalid username or e-mail address.','sw-ajax-login');		
	}	
	
	if(empty ($error)) {

		// Get user data by field and data, fields are id, slug, email and login
		$user = get_user_by( $get_by, $account );

		$key  = get_password_reset_key( $user );
			
		// Check if the key is ok
		if (!is_wp_error($key)) {
			$to = $user->user_email;
			// Get email subject
			$subject = swal_forgot_password_email_body_flow_subject();

			$first_name = $user->first_name ? $user->first_name : $user->user_login;
			
			$message = swal_create_custom_email( swal_forgot_password_email_body_flow($first_name,$user->user_login,$key) );
		
			$headers = swal_create_headers();
			
			/**
	    	 *
	    	 * send the email to user
	    	 *
	    	 */
			$mail = wp_mail( $to, $subject, $message, $headers );

			if( $mail ) 
				$success = __('Check your email address for your new password.','sw-ajax-login');
			else
				$error = __('System is unable to send you mail containg your new password.','sw-ajax-login');	
		} else {
			$error = __('System is unable to generate your reset password key, please contact the admin.','sw-ajax-login');
		}						
			
	}
	
	if( ! empty( $error ) )
		$output = json_encode(array('loggedin'=>false, 'message'=>$error));
			
	if( ! empty( $success ) )
		$output = json_encode(array('loggedin'=>true, 'message'=>$success));

				
	//die the function if forgot password is called by ajax call

		if ( $action == 'ajaxforgotpassword' ) {

			die($output);

		} else if ( $action == 'forgot_password' ) {

			$GLOBALS['sw_login_json'] = $output;

		}
}



/*
 *	Process reset password
 */
function reset_pass_callback() {

	/**
	 * Check if has to disable nonce as workaround for some caching plugins
	 *
	 * Since version: 1.6.0
	 *
	 */ 
	//$swal_disable_nonces                = intval(get_option('swal_disable_nonces'));
	$swal_enable_nonces                = intval(get_option('swal_enable_nonces'));

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

		$action = $_POST['action'];
	} else {
		die();
	}

	$swal_min_password_length           = intval(get_option('swal_min_password_length',SWAL_MIN_PASSWORD_LENGTH));

    //if function is called from normal POST request check nonce in the regular way
	if ( $action == 'reset_pass' ) {
		// First check the nonce, if it fails the function will break
		if ($swal_enable_nonces) {
	    	check_ajax_referer( 'rs_user_reset_password_action', 'resetsecurity' );
	    }

	} else if ( $action == 'resetpassword' ) {

		$nonce = $_POST['resetsecurity'];
		if ($swal_enable_nonces) {
			if ( ! wp_verify_nonce( $nonce, 'rs_user_reset_password_action' ) ) {
			     return; 
			}
		}

	}

	$swal_show_password_reset_password_email = esc_attr(get_option('swal_show_password_reset_password_email'));

	$errors = new WP_Error();

	$pass1 	= $_POST['pass1'];
	$pass2 	= $_POST['pass2'];
	$key 	= $_POST['user_key'];
	$login 	= $_POST['user_login_reset'];

	$user = check_password_reset_key( $key, $login );

	// check to see if user added some string
	if( empty( $pass1 ) || empty( $pass2 ) ) {
		$error = __( 'Password is required field','sw-ajax-login' );
		$errors->add( 'password_required', $error );
		$output = json_encode(array('loggedin'=>false, 'message'=>$error));
	}
	
	if ( strlen($pass1) < $swal_min_password_length) {
		$error = sprintf(__('Password must be at least %d characters long.','sw-ajax-login'),$swal_min_password_length);
		$errors->add( 'password_min_length', $error );
    	$output = json_encode(array('loggedin'=>false, 'message'=>$error) );

	}

	// is pass1 and pass2 match?
	if ( isset( $pass1 ) && $pass1 != $pass2 ) {
		$error = __( 'The passwords do not match.','sw-ajax-login' );
		$errors->add( 'password_reset_mismatch', $error );
		$output = json_encode(array('loggedin'=>false, 'message'=>$error));
		}

	do_action( 'validate_password_reset', $errors, $user );

	if ( ( ! $errors->get_error_code() ) && isset( $pass1 ) && !empty( $pass1 ) ) {

		reset_password($user, $pass1);
			
			$to = $user->user_email;

			// Get email subject
			$subject = swal_reset_password_email_body_subject();

			$first_name = $user->first_name ? $user->first_name : $user->user_login;
			
			$message = swal_create_custom_email( swal_reset_password_email_body($first_name,$pass1,$swal_show_password_reset_password_email) );
		
			$headers = swal_create_headers();
			
			/**
	    	 *
	    	 * send the email to user
	    	 *
	    	 */
			$mail = wp_mail( $to, $subject, $message, $headers );


		$error = __( 'Your password has been reset. Redirecting to login.','sw-ajax-login' );
		$errors->add( 'password_reset', $error );
		$output = json_encode(array('loggedin'=>true, 'message'=>$error));
	}

		

	//die the function if forgot password is called by ajax call

		if ( $action == 'reset_pass' ) {

			die($output);

		} else if ( $action == 'resetpassword' ) { //non ajax call

			$GLOBALS['sw_login_json'] = $output;

			$json = json_decode($output);

			//if password has been successfully reset redirect to login
			if ($json->loggedin) {
				wp_redirect(wp_login_url().'?password=changed');
			}

		}

}



/**
 *
 * Generate reset password URL including username and temporary key
 *
 */

function swal_generate_reset_password_url($account) {

	
	global $wpdb;
	
	
	if( empty( $account ) ) {
		$error = __('Enter an username or e-mail address.','sw-ajax-login');

	} else {
		if(is_email( $account )) {
			if( email_exists($account) ) 
				$get_by = 'email';
			else	
				$error = true;			
		}
		else if (validate_username( $account )) {
			if( username_exists($account) ) 
				$get_by = 'login';
			else	
				$error = true;				
		}
		else
			$error = true;			
	}

	// if error then return
	if ($error) {
		return false;
	}
	
	if(empty ($error)) {

		// Get user data by field and data, fields are id, slug, email and login
		$user = get_user_by( $get_by, $account );

		$key  = get_password_reset_key( $user );
			
		$reset_url = swal_resetpassword_url()."?key=$key&login=" . rawurlencode( $user->user_login );

		return $reset_url;
			
	}
	

}



//Elenco radio buttons per allineamento background image
if ( ! function_exists( 'swal_set_alignment_radiobuttons' ) ) {
function swal_set_alignment_radiobuttons($id, $option) {
        $item = array();
        $item[] = __('Top Left','sw-ajax-login');
        $item[] = __('Top Center','sw-ajax-login');
        $item[] = __('Top Right','sw-ajax-login');
        $item[] = __('Middle Left','sw-ajax-login');
        $item[] = __('Middle Center','sw-ajax-login');
        $item[] = __('Middle Right','sw-ajax-login');
        $item[] = __('Bottom Left','sw-ajax-login');
        $item[] = __('Bottom Center','sw-ajax-login');
        $item[] = __('Bottom Right','sw-ajax-login');
        foreach($item as $key => $value) {
                echo '<input type="radio" id="'.$id.$key.'" name="'.$id.'" value="'.$key.'"'.checked( $option, $key,false ).'>
                <label class="swal-alignment '.$id.$key.'" for="'.$id.$key.'">'.$value.'</label>';
            } 
         }
   }

//Stile allineamento background image da page option
if ( ! function_exists( 'swal_alignment_radiobuttons' ) ) {
function swal_alignment_radiobuttons($option) {
		$alignment = '';
        switch ($option) {
		    case 0:
		        $alignment = 'left top';
		        break;
		    case 1:
		    	$alignment = 'center top';
		        break;
		    case 2:
		    	$alignment = 'right top';
		        break;
		    case 3:
		    	$alignment = 'left center';
		        break;
		    case 4:
		    	$alignment = 'center center';
		        break;
		    case 5:
		    	$alignment = 'right center';
		        break;
		    case 6:
		    	$alignment = 'left bottom';
		        break;
		    case 7:
		    	$alignment = 'center bottom';
		        break;
		    case 8:
		    	$alignment = 'right bottom';
		        break;
		    default:
		    	$alignment = 'center center';
		    }
		    return $alignment;
         }
   }

//Testo voce Login nel menu date le 2 options
if ( ! function_exists( 'swal_menu_login_text' ) ) {
function swal_menu_login_text($texttype,$customtext) {
		$text = '';
        $text1 = __("Login",'sw-ajax-login');
        $text2 = __("Login/Register",'sw-ajax-login');
        switch ($texttype) {
		    case 0:
		        $text = $text1;
		        break;
		    case 1:
		    	$text = $text2;
		        break;
		    case 2:
		    	if ($customtext) {
		    		$text = $customtext;
		    	} else {
		    		$text = $text1;
		    	}
		        break;
		    default:
		    	$text = $text1;
		    }
		    return $text;
         }
   }

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string Filtered title.
 */
function swal_filter_wp_title( $title, $sep ) {
    global $paged, $page;
 
    if ( is_feed() )
        return $title;
 
    // Add the site name.
    $title .= ' '.$sep.' '.get_bloginfo( 'name' );
 
    // Add the site description for the home/front page.
    /*
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";
        */
 
    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 )
        $title = "$title $sep " . sprintf( __( 'Page %s', 'sw-ajax-login' ), max( $paged, $page ) );
 
    return $title;
}


function swal_filter_title() {

	$act = get_query_var( 'act' );

	switch ($act) {
		    	case 'login':
			    	$title = swal_filter_wp_title( __( 'Login', 'sw-ajax-login' ), '-' );
			    	return $title;
			    	break;
			    case 'register':
			    	$title = swal_filter_wp_title( __( 'Register', 'sw-ajax-login' ), '-' );
			    	return $title;
			    	break;
			    case 'forgot_password':
			    	$title = swal_filter_wp_title( __( 'Forgot Password', 'sw-ajax-login' ), '-' );
			    	return $title;
			    	break;
			    case 'resetpassword':
			    	$title = swal_filter_wp_title( __( 'Reset Password', 'sw-ajax-login' ), '-' );
			    	return $title;
			    	break;
			    case 'logout':
			    	$title = swal_filter_wp_title( __( 'Logout', 'sw-ajax-login' ), '-' );
			    	return $title;
			    	break;
			    }
		return;

}

add_filter( 'pre_get_document_title', 'swal_filter_title', 1 );



/**
 *
 * get user details from social and login if alredy exists
 * or insert as new user if not exists.
 * 
 */
function swal_social_login_insert_user($user_data = array()) {

		$swal_use_social_profile_picture       	= esc_attr(get_option('swal_use_social_profile_picture'));
		$swal_disable_new_user_registration  	= intval(get_option('swal_disable_new_user_registration',SWAL_DISABLE_NEW_USER_REGISTRATION));

		/**
		 *
		 * if no user with this email, create him
		 *
		 */
		if( !email_exists( $user_data['user_email']) ) {

			if ($swal_disable_new_user_registration) {
				if ($user_data['echo_json']) {
				$output = json_encode(array(
					'loggedin'=>false, 
					'message'=> __('Sorry, new user registration is not allowed...','sw-ajax-login'),
					));
				echo $output;
				exit;
				}
			}

			$userdata = array(
				'user_login'  =>  $user_data['user_login'],
				'user_pass'   =>  wp_generate_password(), // random password, you can also send a notification to new users, so they could set a password themselves
				'user_email' => $user_data['user_email'],
				'first_name' => $user_data['first_name'],
				'last_name' => $user_data['last_name'],
			);
			$user_id = wp_insert_user( $userdata );

			if ($user_data['social']) {
				//update_user_meta( $user_id, $user_data['social'], $user_data['social_link'] ); // Deprecated
				update_user_meta( $user_id, 'social', $user_data['social'] );
			}

			if ($user_data['profile_picture']) {
				update_user_meta( $user_id, 'profile_picture', $user_data['profile_picture'] );
			}

			/**
	    	 *
	    	 * adds meta user 'user_gdpr_consent'
	    	 *
	    	 */
		 	update_user_meta( $user_id, 'user_gdpr_consent', true );

			//send an email to the administrators
    		wp_new_user_notification( $user_id, '' );

    		// authorize the user and redirect him to admin area
    		if (!swal_register_no_autologin()) {
    			wp_set_auth_cookie( $user_id, true );
    		}
			

			if ($user_data['echo_json']) {

				if (!swal_register_no_autologin()) { 
					$output = json_encode(array(
						'loggedin' =>true, 
						'message' => __('Login successful, redirecting...','sw-ajax-login'),
						'execlogin' => true,
						));
				} else {
					$output = json_encode(array(
						'loggedin' =>true, 
						'message' => __('Registration successful','sw-ajax-login'),
						'execlogin' => false,
						));
				}
			echo $output;
			exit;
			}

		} else {

		/**
		 *
		 * user exists, so we need just get his ID
		 *
		 */
			$user = get_user_by( 'email', $user_data['user_email'] );
			$user_id = $user->ID;

			//update user image to sync it with the one from social
			if ($user_data['profile_picture'] && $swal_use_social_profile_picture) {
				update_user_meta( $user_id, 'profile_picture', $user_data['profile_picture'] );
			}

			// authorize the user
			wp_set_auth_cookie( $user_id, true );

			if ($user_data['echo_json']) {
			$output = json_encode(array(
				'loggedin'=>true, 
				'message'=> __('Login successful, redirecting...','sw-ajax-login'),
				'execlogin' => true,
				));
			echo $output;
			exit;
			}
		}

		// authorize the user and redirect him to admin area
		/*
		if( $user_id ) {
			wp_set_auth_cookie( $user_id, true );

			if ($user_data['echo_json']) {
			$output = json_encode(array(
				'loggedin'=>true, 
				'message'=> __('Login successful, redirecting...','sw-ajax-login'),
				));
			echo $output;
			exit;
			}
		}
		*/


}


/**
 *
 * replace avatar with social profile image if available
 *
 */

add_filter('get_avatar', 'swal_new_insert_avatar', 101, 5);



function swal_new_insert_avatar($avatar, $id_or_email, $size = 96, $default = '', $alt = false) {

	 $swal_use_social_profile_picture       = esc_attr(get_option('swal_use_social_profile_picture'));

	 if (!$swal_use_social_profile_picture) return $avatar;

  $id = 0;

  if (is_numeric($id_or_email)) {

    $id = $id_or_email;

  } else if (is_string($id_or_email)) {

    $u = get_user_by('email', $id_or_email);

    if ($u)
    $id = $u->id;

  } else if ( is_object($id_or_email) ) {
     if ($id_or_email instanceof \WP_User) {
         $id = $id_or_email->ID;
     } elseif ($id_or_email instanceof \WP_Post) {
         $id = $id_or_email->post_author;
     } else {
         $id = $id_or_email->user_id;
     }
 } 

  if ($id == 0) return $avatar;

  $pic = get_user_meta($id, 'profile_picture', true);

  if (!$pic || $pic == '') return $avatar;

  $avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);

  return $avatar;

}


/**
 * Checks that the reCAPTCHA parameter sent with the registration
 * request is valid.
 *
 * @return bool True if the CAPTCHA is OK, otherwise false.
 */
function swal_verify_recaptcha() {
    // This field is set by the recaptcha widget if check is successful
    if ( isset ( $_POST['g-recaptcha-response'] ) ) {
        $captcha_response = $_POST['g-recaptcha-response'];
    } else {
        return false;
    }

    $swal_recaptcha_version            = intval( get_option('swal_recaptcha_version', 0) );
    $swal_recaptcha_v3_threshold       = esc_attr(get_option('swal_recaptcha_v3_threshold', 0.5));
    $swal_recaptcha_secret_key 		= $swal_recaptcha_version ? esc_attr(get_option( 'swal_recaptcha_v3_secret_key' )) : esc_attr(get_option( 'swal_recaptcha_secret_key' ));
 
    // Verify the captcha response from Google
    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        array(
            'body' => array(
                'secret' => $swal_recaptcha_secret_key,
                'response' => $captcha_response
            )
        )
    );
 
    $success = false;
    if ( $response && is_array( $response ) ) {
        $decoded_response = json_decode( $response['body'] );

        // if reCAPTCHA v3
        if ($swal_recaptcha_version == 1) {
	        // if the score is higher of 0.5 then it's not a bot
	        if ($decoded_response->score >= $swal_recaptcha_v3_threshold) {  
	       		$success 	= $decoded_response->success;
       		}
    	} else {
    	// if reCAPTCHA v2
    		$success 	= $decoded_response->success;
    	}

    }
 
    return $success;
}




/**
 *
 * Function for redirecting depending on admin setting
 *
 */

// Redirect settings after LOGIN
if ( ! function_exists( 'swal_page_to_redirect' ) ) {
function swal_page_to_redirect() {

		$swal_redirect_after_login           = intval(get_option('swal_redirect_after_login',SWAL_REDIRECT_AFTER_LOGIN));
		$swal_custom_redirect_after_login    = esc_attr(get_option('swal_custom_redirect_after_login'));
		$swal_custom_redirect_after_login 	 = ltrim($swal_custom_redirect_after_login, '/');
		$swal_custom_redirect_after_login 	 = swal_replace_placeholders_for_redirect($swal_custom_redirect_after_login);

		$page_to_redirect = home_url();
		if ($swal_redirect_after_login == 1) {

			$page_to_redirect = sw_curPageURL();

		} else if ($swal_redirect_after_login == 2) {

			$page_to_redirect = home_url($swal_custom_redirect_after_login);

		} else if ($swal_redirect_after_login == 3) {

			$page_to_redirect = admin_url();
		}

		return $page_to_redirect;
	}
}


// Redirect settings after REGISTER
if ( ! function_exists( 'swal_page_to_redirect_register' ) ) {
function swal_page_to_redirect_register() {

		
		$swal_custom_redirect_after_login    = esc_attr(get_option('swal_custom_redirect_after_login'));
		$swal_redirect_after_register              = intval(get_option('swal_redirect_after_register'));
    	$swal_custom_redirect_after_register    = intval(get_option('swal_custom_redirect_after_register'));

    	// If redirect after registration is the same as Login return false 
		if (!swal_register_no_autologin() && !$swal_redirect_after_register) {
			return false;
		}


	    $swal_register_no_autologin_redirect    = intval( get_option('swal_register_no_autologin_redirect', 0) );
	    $swal_register_no_autologin_redirect_custom_page    = intval( get_option('swal_register_no_autologin_redirect_custom_page') );

		//Get SWAL base page permalink
    	$swal_permalink = swal_get_permalink();

    	$home = home_url();

		$swal_custom_redirect_after_login 	 = ltrim($swal_custom_redirect_after_login, '/');


		if (swal_register_no_autologin()) {
			switch ($swal_register_no_autologin_redirect) {
		    	case 0:
		    		$page_to_redirect = 'show_message';
			    	break;
			    case 1:
		    		$page_to_redirect = 'go_to_login';
			    	break;
			    case 2:
			    	$page_to_redirect = $home;
			    	break;
			    case 3:
			    	$page_to_redirect = get_page_link($swal_custom_redirect_after_register);
			    	break;
			    }

			return $page_to_redirect;
		}

		if ($swal_redirect_after_register) {
			$page_to_redirect = get_page_link($swal_custom_redirect_after_register);
			return $page_to_redirect;
		}

		return false;
		
	}
}


// Redirect settings after LOGOUT
if ( ! function_exists( 'swal_page_to_redirect_logout' ) ) {
function swal_page_to_redirect_logout() {

		$swal_redirect_after_logout           = intval(get_option('swal_redirect_after_logout',SWAL_REDIRECT_AFTER_LOGOUT));
		$swal_custom_redirect_after_logout    = esc_attr(get_option('swal_custom_redirect_after_logout'));
    	$swal_custom_redirect_after_logout    = ltrim($swal_custom_redirect_after_logout, '/');

		$page_to_redirect = home_url();
		if ($swal_redirect_after_logout == 1) {
			$page_to_redirect = sw_curPageURL();
		} else if ($swal_redirect_after_logout == 2) {
			$page_to_redirect = esc_url(home_url().'/'.$swal_custom_redirect_after_logout);
		}

		return $page_to_redirect;
	}
}

//Get the current page URL
function sw_curPageURL() {
 	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
	
	if (!isset($_SERVER['HTTP_HOST'])) {
		$url = '';
	} else {
		$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	
 return $url;
}

//Get the current page URL without query vars
function sw_curPageURL_no_vars() {
 	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
	
 	if (!isset($_SERVER['HTTP_HOST'])) {
		$url = '';
	} else {
		$url = $protocol . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	}

 return $url;
}     

//Get SWAL permalink
function swal_get_permalink() {

	$swal_pagina_account            = esc_attr(get_option('swal_pagina_account'));
    $swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));

    $permalink = swal_check_permalink_for_index() . SWAL_PAGINA_ACCOUNT;

    if ($swal_pagina_account_default == 1 && $swal_pagina_account) {
    	$permalink = swal_check_permalink_for_index() . $swal_pagina_account;
    } else if ($swal_pagina_account_default == 2) {
    	$permalink = '';
    }

    return $permalink;
}

//Check if permalink structure contains /index.php/
if ( ! function_exists( 'swal_check_permalink_for_index' ) ) {
function swal_check_permalink_for_index() {

	$permalink = '';

    $permalink_structure = get_option('permalink_structure');

	if (strpos($permalink_structure, 'index.php') !== false) {
	    $permalink = 'index.php/';
	}

    return $permalink;
}
}


//Check if logged user has to be redirected or not
function swal_check_logged_user_to_redirect() {

	//if user is not logged return function
	if (!is_user_logged_in()) return;

	$swal_logged_in_redirect             	= intval(get_option('swal_logged_in_redirect',SWAL_LOGGED_IN_REDIRECT));
	$swal_logged_in_redirect_custom_page    = esc_attr(get_option('swal_logged_in_redirect_custom_page'));
    $swal_logged_in_redirect_custom_page    = ltrim($swal_logged_in_redirect_custom_page, '/');
    $swal_logged_in_redirect_custom_page    =  home_url($swal_logged_in_redirect_custom_page);

 	if (!$swal_logged_in_redirect) return;

 	if ($swal_logged_in_redirect == 1) {
 		exit( wp_redirect( home_url() ));
 	} else if ($swal_logged_in_redirect == 2 && $swal_logged_in_redirect_custom_page) {
 		exit( wp_redirect( $swal_logged_in_redirect_custom_page ));
 	}


    return;
}

/**
 *
 * Parse CSS removing css comments, @import, @charset and expression
 *
 */

function swal_notify_import_rules_stripped($csstoparse = '') {

    $parser = new CssParser();

    $parser->load_string($csstoparse);
    $parser->parse();

    $parsedcss = strip_tags($parser->glue());

    return $parsedcss;
}



/**
 *
 * Generate a random string
 *
 */
function swal_generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


/**
 *
 * Generate a random string with special characters
 *
 */
function swal_generateComplexRandomString($stringLength = 32){

	//specify characters to be used in generating random string, do not specify any characters that wordpress does not allow in the creation.
	$characters = "0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_[]{}!@$%^*().,>=-;|:?";

	//get the total length of specified characters to be used in generating random string
	$charactersLength = strlen($characters);

	$randomString = '';

	for ($i = 0; $i < $stringLength; $i++) {

		//generate random characters
		$randomCharacter = $characters[rand(0, $charactersLength - 1)];

		//add the random characters to the random string
		$randomString .=  $randomCharacter;
	};

	//sanitize_user, just in case 
	$sanRandomString = sanitize_user($randomString);

	//check that random string contains Uppercase/Lowercase/Intergers/Special Char and that it is the correct length
	if ((preg_match('([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z].*[_\W])', $sanRandomString)==1) && (strlen($sanRandomString)==$stringLength)) {

		//return the random string if it meets the complexity criteria 
		return $sanRandomString;

	} else {

		// if the random string does not meet minimium criteria call function again 
		return call_user_func("generateRandomString",($stringLength) );
	}       

}


/**
 *
 * Sanitize multiple classes separated by space
 *
 */
if( ! function_exists("sanitize_html_classes") ){
    function sanitize_html_classes($classes, $sep = " "){
        $return = "";
 
        if(!is_array($classes)) {
            $classes = explode($sep, $classes);
        }
 
        if(!empty($classes)){
            foreach($classes as $class){
                $return .= sanitize_html_class($class) . " ";
            }
        }
 
        return $return;
    }
}


/**
 *
 * Generate random email
 *
 */
function swal_generate_random_email($username_length) {

			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$char_count = count($characters); 
			$tld = array("com", "net", "biz", "org"); 

				$randomName = ''; 
				for($j=0; $j<$username_length; $j++){
					$randomName .= $characters[rand(0, strlen($characters) -1)];
				}
				$k = array_rand($tld); 
				$extension = $tld[$k]; 
				$fullAddress = $randomName . "@example.".$extension; 
	
		
		return 	$fullAddress;
			
}

/**
 *
 * Get the current URL without querystrings
 *
 */
function swal_get_current_url() {

	$uri = $_SERVER['REQUEST_URI'];
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
	if (!isset($_SERVER['HTTP_HOST'])) {
		$current_url = '';
	} else {
		$current_url = strtok($protocol . $_SERVER['HTTP_HOST'] . $uri,'?');
	}
	return $current_url;	
}

/**
 *
 * Check if is login page
 *
 */
function swal_is_login_page() {

	$is_page = false;
	if (swal_get_current_url() == wp_login_url() || rtrim(swal_get_current_url(), '/') == wp_login_url()) {
		$is_page = true;
	}
	return $is_page;		
}

/**
 *
 * Check if is register page
 *
 */
function swal_is_register_page() {

	$is_page = false;
	if (swal_get_current_url() == wp_registration_url() || rtrim(swal_get_current_url(), '/') == wp_registration_url()) {
		$is_page = true;
	}
	return $is_page;		
}

/**
 *
 * Check if is lost password page
 *
 */
function swal_is_lost_password_page() {

	$is_page = false;
	if (swal_get_current_url() == wp_lostpassword_url() || rtrim(swal_get_current_url(), '/') == wp_lostpassword_url()) {
		$is_page = true;
	}
	return $is_page;		
}

/**
 *
 * Check if is reset password page
 *
 */
function swal_is_reset_password_page() {

	$is_page = false;
	if (swal_get_current_url() == swal_resetpassword_url() || rtrim(swal_get_current_url(), '/') == swal_resetpassword_url()) {
		$is_page = true;
	}
	return $is_page;		
}

/**
 *
 * Check if is any of swal pages
 *
 */
function swal_is_swal_page() {

	$is_page = false;
	if (swal_is_login_page() || swal_is_register_page() || swal_is_lost_password_page() || swal_is_reset_password_page()) {
		$is_page = true;
	}
	return $is_page;		
}


/**
 *
 * Create a new template page 
 *
 */
if ( ! function_exists( 'swal_new_template_page' ) ) {
	
	function swal_new_template_page($args=array()) {

		$r = array(
	            'post_title'    => $args['post_title'],
	            'post_content'  => $args['post_content'],
	            'post_name'     => $args['post_name'],
	            'post_status' 	=> 'publish',
	            'post_type' 	=> 'page',
	        );

		$page_id = wp_insert_post($r);

		add_post_meta( $page_id, '_swal_page_type', $args['page_type'] );

		return $page_id;

	}
}


/**
 *
 * Check if a page exists by custom page template name
 * Return page id if true, false if not exists
 *
 */
if ( ! function_exists( 'swal_check_page_existance_from_template' ) ) {

	function swal_check_page_existance_from_template($page_type) {

		$page_exists = false;

		$args = array(
        'post_type' => 'page',
        'post_status' => array( 'publish', 'draft' ),
        'meta_query' => array(
           array(
               'key' => '_swal_page_type',
               'value' => $page_type,
               'compare' => '=',
           )
       )
	    );
	    $query = new WP_Query($args);

	    // if page exists then return
	    if ( $query->have_posts() ) {

	        $page_exists = $query->post->ID;
	    }

	    wp_reset_postdata();

	    return $page_exists;

	}
}


/**
 *
 * Generate Admin Tabs menu
 *
 */
if ( ! function_exists( 'sw_create_tabs_menu' ) ) {
	function sw_create_tabs_menu ($class = null, array $menu_item = null, $echo = null) {

		$output = '<ul';

		if ($class) {
			$output .= ' class="' .esc_html__($class).'"';
		}
		$output .= '>';	
		
		foreach($menu_item as $item) {
			    $output .= sw_create_tabs_menu_items( $item );
			}

		$output .= '</ul>';

		if ($echo) {
			echo $output;
		} else {
			return $output;
		}
		
	}
}

/**
 *
 * Generate Admin Tabs menu new version
 *
 * @since 1.8.0
 *
 */
if ( ! function_exists( 'sw_create_tabs_menu_new' ) ) {
	function sw_create_tabs_menu_new ($class = null, array $menu_item = null, $echo = null) {

		// Reorder the array by priority parameter
		usort($menu_item, "swal_orderby_priority");


		echo '<div class="tab_orizz_edit" id="tabs_edit">
						<ul';

		if ($class) {
			echo ' class="' .esc_html__($class).'"';
		}
		echo '>';	
		
		foreach($menu_item as $item) {
			    echo  sw_create_tabs_menu_items( $item );
			}

		echo  '</ul>
			</div>
			<div id="sw-ajax-login-admin-form" class="swal-tabscontent">';
		foreach($menu_item as $item) {
			if (isset($item['callback'])) {
				$priority = isset($item['priority']) ? intval($item['priority']) : '';

				echo '<div id="tab-'.$priority.'" class="tab_content">';
					call_user_func( $item['callback']);
				echo '</div>';
			}
			
		}
		echo  '</div>';
		
	}
}

/**
 *
 * Order the tab array by priority
 *
 * @since 1.8.0
 *
 */
function swal_orderby_priority($a, $b) {
	if (!isset($a['priority']) || !isset($b['priority'])) {
	    return 0;
	  }
	if ($a['priority'] == $b['priority']) {
		return 0;
	}
	return ($a['priority'] < $b['priority']) ? -1 : 1;
}


/**
 *
 * Generate Admin Tabs menu items
 *
 */
if ( ! function_exists( 'sw_create_tabs_menu_items' ) ) {
	function sw_create_tabs_menu_items ($args) {


		if ( is_object( $args ) ) {
				$args = get_object_vars( $args );
			}

		$defaults = array(
				'id'     	=> false,
				'class'	 	=> false,
				'title'  	=> false,
				'href'   	=> false,
				'priority'  => false,
				'icon'	 	=> false,
				'callback'  => false,
			);

		$r = wp_parse_args( $args, $defaults );

		$output = '<li';
		if ($r['id']) {
			$output .= ' id="'.esc_html__($r['id']).'"';
		}
		if ($r['class']) {
			$output .= ' class="'.esc_html__($r['class']).'"';
		}
		$output .= '>';
		if ($r['href']) {
			$output .= '<a href="'.esc_html__($r['href']).'">';
		}
		if ($r['priority']) {
			$output .= '<a href="#tab-'.esc_html__($r['priority']).'">';
		}
		if ($r['icon']) {
			$output .= '<i class="'.$r['icon'].'" aria-hidden="true"></i>';
		}
		if ($r['title']) {
			$output .= esc_html__($r['title']);
		}
		if ($r['href'] || $r['priority']) {
			$output .= '</a>';
		}
		$output .= '</li>';

		return $output;

	}
}



/**
 *
 * Generate img, see defaults for args
 *
 */
if ( ! function_exists( 'sw_display_image' ) ) {
	function sw_display_image($args) {

		$defaults = array(
					'image'     	=> false,
					'size'			=> 'large',
					'div_class'	 	=> false,
					'img_class'	 	=> false,
					'img_style'		=> false,
					'alt'	  		=> false,
					'echo'	  		=> false,
				);

		$r = wp_parse_args( $args, $defaults );

	    // get image
	    $options            = esc_attr($r['image']);
	    $image_attributes   = wp_get_attachment_image_src( $options, esc_html($r['size']));
	    $src = isset($image_attributes[0]) ? $image_attributes[0] : '';

	    if (!$options || !$src ) {
	        return;
	    }

	    $output = '';

	    if ($r['div_class']) {
	    	$output = '<div class="'.esc_html($r['div_class']).'">';
	    }

	    $alt 	= $r['alt'] ? ' alt="' . esc_html($r['alt']) . '"' : '';
	    $class 	= $r['img_class'] ? ' class="' . esc_html($r['img_class']) . '"' : '';
	    $style 	= $r['img_style'] ? ' style="' . esc_html($r['img_style']) . '"' : '';

	    	$output .= '<img src="'. $src .'"'. $alt . $class . $style .'/>';
	               
	    if ($r['div_class']) {
	    	$output .= '</div>';
	    }            

	    if ($r['echo']) {
	    	echo $output;
	    } else {
	    	return $output;
	    }
	}
}


/**
 *
 * Replace SWAL placeholders for emails content
 *
 */
function swal_replace_placeholders($string = null, $args = null) {

	$defaults = array(
					'id' 			=> false,
					'username'    	=> '{USERNAME}',
					'first_name'    => '{FIRST_NAME}',
					'last_name'		=> '{LAST_NAME}',
					'new_password'	=> '{NEW_PASSWORD}',
					'reset_password_url'	=> '{RESET_PASSWORD_URL}',
					'activation_code'    	=> '{ACTIVATION_CODE}',
				); 

	$r = wp_parse_args( $args, $defaults );

	// If 
	if (intval($r['id'])) {
		$user     = new WP_User( intval($r['id']) );
	}
	

	$variables = array(
		'{SITE_NAME}' => get_bloginfo( 'name' ),
		'{SITE_NAME_URL}' => '<a href="'.home_url().'">'.get_bloginfo( 'name' ).'</a>',
		'{WEBSITE_URL}' => home_url(),
	    '{LOGIN_PAGE}' => wp_login_url(),
	    '{USERNAME}' => isset($user->user_login) ? esc_html($user->user_login) : esc_html($r['username']),
	    '{FIRST_NAME}' => isset($user->first_name) ? esc_html($user->first_name) : esc_html($r['first_name']),
	    '{LAST_NAME}' => isset($user->last_name) ? esc_html($user->last_name) : esc_html($r['last_name']),
	    '{NEW_PASSWORD}' => esc_html($r['new_password']),
	    '{RESET_PASSWORD_URL}' => esc_url($r['reset_password_url']),
	    '{ACTIVATION_CODE}' => esc_html($r['activation_code']),
	);

	$variables = apply_filters( 'swal_placeholders', $variables );

	$newstring = strtr($string, $variables);

	return $newstring;
}

/**
 *
 * Replace SWAL placeholders for custom redirect field
 * Available placeholder: {username},{userid}
 *
 */
function swal_replace_placeholders_for_redirect($string = null) {

	$current_user = wp_get_current_user();

	$variables = array(
	    '{USERNAME}' => esc_html( $current_user->user_login ),
	    '{USERID}' => esc_html( $current_user->ID ),
	);

	$newstring = strtr($string, $variables);

	return $newstring;
}

/**
 * Turn all URLs in clickable links.
 * 
 * @param string $value
 * @param array  $protocols  http/https, ftp, mail, twitter
 * @param array  $attributes
 * @return string
 */
if ( ! function_exists( 'sw_linkify' ) ) {
 function sw_linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
        }
        
        $links = array();
        
        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
        
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':	
                case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$link</a>") . '>'; }, $value); break;
                case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
                default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            }
        }
        
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
    }
}


/**
 * A custom sanitization function that will take the incoming input, and sanitize
 * the input before handing it back to WordPress to save to the database.
 *
 * @since    1.5.0
 *
 * @param    array    $input        The address input.
 * @return   array    $new_input    The sanitized input.
 */
function swal_sanitize_array_text_field( $input ) {

	// Initialize the new array that will hold the sanitize values
	$new_input = array();

	if (!$input) {
		return;
	}

	// Loop through the input and sanitize each of the values
	foreach ( $input as $key => $val ) {
		$new_input[ $key ] = sanitize_text_field( $val );
	}

	return $new_input;

}


/**
 * Get the user's roles
 * @since 1.5.0
 */
function swal_get_current_user_roles() {
	 if( is_user_logged_in() ) {
		 $user = wp_get_current_user();
		 $roles = ( array ) $user->roles;
		 return $roles; // This returns an array
		 // Use this to return a single value
		 // return $roles[0];
	 } else {
	 	return array();
	 }
}

/**
 *
 * Calculate date difference
* '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
* '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
* '%m Month %d Day'                                            =>  3 Month 14 Day
* '%d Day %h Hours'                                            =>  14 Day 11 Hours
* '%d Day'                                                        =>  14 Days
* '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
* '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
* '%h Hours                                                    =>  11 Hours
* '%a Days                                                        =>  468 Days
 */
function swal_dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);
   
}




/**
 * Get the login menu item content
 *
 * @param $wrapped = true return wrapped element, false returns flat element
 * @since 1.6.0
 */
function swal_get_user_menu_item_content($wrapped = true) {

	$swal_menu_item_style         			 = intval(get_option('swal_menu_item_style',SWAL_MENU_ITEM_STYLE));
	$swal_loggedin_menu_item_custom_text     = esc_html(get_option('swal_loggedin_menu_item_custom_text'));
	$swal_menu_item_text            		 = intval(get_option('swal_menu_item_text',SWAL_MENU_ITEM_TEXT));
  	$swal_menu_item_custom_text     		 = esc_html(get_option('swal_menu_item_custom_text'));
  	$swal_menu_login_text           		 = swal_menu_login_text($swal_menu_item_text,$swal_menu_item_custom_text);

	$swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
  	$swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');

	$swal_add_menu_additional_class     = intval(get_option('swal_add_menu_additional_class'));
	$swal_add_menu_li_a_span_class      = (sanitize_html_classes(get_option('swal_add_menu_li_a_span_class')));
	$li_a_span_additional_class 		= ( $swal_add_menu_additional_class && $swal_add_menu_li_a_span_class )  ? ' class="' . $swal_add_menu_li_a_span_class .'"' : '';

	if ( !is_user_logged_in() ) {

		if (!$wrapped) {
			$displayname  =  $swal_menu_login_text;
		} else {
			$displayname  =  '<span'.$li_a_span_additional_class.'>'.$swal_menu_login_text.'</span>';
		}
		
   		return $displayname;
	}

	//get logged in user details
      $user = wp_get_current_user();

      $nickname = $user->display_name;
      $userfirstname = $user->user_firstname;
      $userlastname = $user->user_lastname;


	$prefix = $swal_loggedin_menu_item_custom_text ? $swal_loggedin_menu_item_custom_text.' ' : '';
        switch ($swal_menu_item_style) {
        	case 0:
	            $displayname = $swal_menu_item_logout_custom_text;
	            break;
	        case 1:
	            $displayname = $prefix . $nickname;
	            break;
	        case 2:
	            if ($userfirstname && $userlastname) {
	              $displayname = $prefix . $userfirstname .' '.$userlastname;
	            } else {
	              $displayname = $prefix . $nickname;
	            }
	            break;
	        case 3:
	            if ($userfirstname && $userlastname) {
	              $displayname = $prefix . $userlastname.' '.$userfirstname;
	            } else {
	              $displayname = $prefix . $nickname;
	            }
	            break;
	        case 4:
	            $displayname = $swal_loggedin_menu_item_custom_text;
	            break;
	        case 5:
	            if ($userfirstname) {
	              $displayname = $prefix . $userfirstname;
	            } else {
	              $displayname = $prefix . $nickname;
	            }
	            break;
          }
   if (!$wrapped) {
			$displayname  =  $displayname;
		} else {
			$displayname  =  '<span'.$li_a_span_additional_class.'>'.$displayname.'</span>';
		}

   return $displayname;
}


/**
 * Get the login menu Avatar
 * @since 1.6.0
 */
function swal_get_user_menu_item_avatar($menu_id = '') {

	$swal_user_thumbnail_style    = intval( get_option('swal_user_thumbnail_style',SWAL_USER_THUMBNAIL_STYLE_DEFAULT) );
    $swal_user_thumbnail_width    = intval( get_option('swal_user_thumbnail_width',SWAL_USER_THUMBNAIL_WIDTH) );

	//get logged user thumbail
      $user_thumnail = '';
      $user_thumnail_class = 'swal-user-thumbail-square';
      $user = wp_get_current_user();

      $menu_id = ($menu_id) ? '-'.$menu_id : '';

      if ($swal_user_thumbnail_style) {
          if ($swal_user_thumbnail_style == 2) {
            $user_thumnail_class = 'swal-user-thumbail-rounded';
          }
          $args = array(
              'class' => $user_thumnail_class,
              'extra_attr' => 'id="swal-user-thumbail'.$menu_id.'"'
            );
          $user_thumnail = get_avatar( $user->ID,$swal_user_thumbnail_width, '', '',$args );
      }


   $user_thumnail = $user_thumnail ? '<span class="swal-thumbnail '.$user_thumnail_class.'">'.$user_thumnail.'</span> ' : ''; 

   return $user_thumnail;
}



/**
 * Get the logged in menu item link
 * @since 1.6.0
 */
function swal_get_logged_in_menu_item_link() {

	$swal_menu_item_style         = intval(get_option('swal_menu_item_style',SWAL_MENU_ITEM_STYLE));
	$swal_menu_item_link_to       = intval(get_option('swal_menu_item_link_to',SWAL_MENU_ITEM_LINK_TO));

	swal_logout_url();

	if (!$swal_menu_item_link_to) {
        	$menuitemlink = '#';
      } else if ($swal_menu_item_link_to == 1) {
        	$swal_menu_item_custom_link_to    = esc_attr(get_option('swal_menu_item_custom_link_to'));
          	$swal_menu_item_custom_link_to    = ltrim($swal_menu_item_custom_link_to, '/');
        	$menuitemlink = home_url($swal_menu_item_custom_link_to);
    }

    if (!$swal_menu_item_style) {
    	$menuitemlink = swal_logout_url();
    }

    return $menuitemlink;
 }




/**
 * 
 * Filter the admin menu metaboxes adding checkboxes for extra options
 * @since 1.6.0
 */

add_action( 'wp_nav_menu_item_custom_fields', 'action_wp_nav_menu_item_custom_fields', 10, 3 ); 

// define the wp_nav_menu_item_custom_fields callback 
function action_wp_nav_menu_item_custom_fields( $item_id, $item, $args ) { 

	// Add extra fields only on login menu item
	if ( in_array( 'sw-open-login', $item->classes ) ) {

		wp_nonce_field( 'swal_custom_menu_meta_nonce', '_swal_custom_menu_meta_nonce_name' );
		$swal_item_avatar = intval(get_post_meta( $item_id, '_swal_menu_item_avatar', true ));
		$swal_logged_in_menu_item_link 	= esc_url(get_post_meta( $item_id, '_swal_logged_in_menu_item_link', true )) ? esc_url(get_post_meta( $item_id, '_swal_logged_in_menu_item_link', true )) : swal_get_logged_in_menu_item_link();

	    ?>
	    <input type="hidden" name="swal-custom-menu-meta-nonce" value="<?php echo wp_create_nonce( 'swal-custom-menu-meta-name' ); ?>" />
	    <input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />
	    <h3><?php esc_html_e( 'When user is logged in:', 'sw-ajax-login'); ?></h3>
	    <p class="field-avatar description description-wide">
	    	<input type="checkbox" id="edit-swal_item_avatar-<?php echo $item_id; ?>" name="swal_menu_item_avatar[<?php echo $item_id; ?>]" value="1" <?php checked( $swal_item_avatar, true, true ) ?>/>
			<label for="edit-swal_item_avatar-<?php echo $item_id; ?>"><?php esc_html_e('Don\'t show the avatar when logged in','sw-ajax-login'); ?></label>
		</p>
		<p class="field-url description description-wide">
			<label for="swal_logged_in_menu_item_link-<?php echo $item_id ;?>">
	            <?php esc_html_e( 'Logged in URL', 'sw-ajax-login'); ?>
	        </label>
	        <input type="text" class="widefat code" name="swal_logged_in_menu_item_link[<?php echo $item_id ;?>]" id="swal_logged_in_menu_item_link-<?php echo $item_id ;?>" value="<?php echo esc_attr( $swal_logged_in_menu_item_link ); ?>" />
	    	<span><?php esc_html_e( 'If you have changed this URL and want to get back to the plugin setting, leave this field empty and save.', 'sw-ajax-login'); ?></span>
	    </p>
	<?php
	}

	// Add extra fields only on register menu item
	if ( in_array( 'sw-open-register', $item->classes ) ) {
	    ?>
		<p class="description description-wide">
			<span class="dashicons dashicons-info"></span> <?php esc_html_e( 'This Menu Item is only visible to NOT logged in users.', 'sw-ajax-login'); ?></span>
	    </p>
	<?php
	}

	// Add extra fields only on Logout menu item
	if ( in_array( 'open_logout', $item->classes ) ) {
	    ?>
		<p class="description description-wide">
			<span class="dashicons dashicons-info"></span> <?php esc_html_e( 'This Menu Item is only visible to logged in users.', 'sw-ajax-login'); ?></span>
	    </p>
	<?php
	}

}; 
         
/**
 * Save the menu item meta
 * 
 * @param int $menu_id
 * @param int $menu_item_db_id
 * @since 1.6.0
 */

add_action( 'wp_update_nav_menu_item', 'swal_nav_update', 10, 2 );

function swal_nav_update( $menu_id, $menu_item_db_id ) {

	// Verify this came from our screen and with proper authorization.
	if ( ! isset( $_POST['_swal_custom_menu_meta_nonce_name'] ) || ! wp_verify_nonce( $_POST['_swal_custom_menu_meta_nonce_name'], 'swal_custom_menu_meta_nonce' ) ) {
		return $menu_id;
	}

	if ( isset( $_POST['swal_menu_item_avatar'][$menu_item_db_id]  ) ) {
		$sanitized_data = intval( $_POST['swal_menu_item_avatar'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, '_swal_menu_item_avatar', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, '_swal_menu_item_avatar' );
	}

	if ( isset( $_POST['swal_logged_in_menu_item_link'][$menu_item_db_id]  ) ) {
		$sanitized_data = esc_url_raw($_POST['swal_logged_in_menu_item_link'][$menu_item_db_id]);
		update_post_meta( $menu_item_db_id, '_swal_logged_in_menu_item_link', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, '_swal_logged_in_menu_item_link' );
	}
}


/**
 * Check if the current menu contains menu items with a specific class
 * @since 1.6.0
 */
function swal_check_if_menu_has_login_item($menu, $class= '') {

    $current_menu = wp_get_nav_menu_items($menu);

    if (!$current_menu) {
    	return false;
    }

    foreach($current_menu as $item) {

          // If Login menu item is present on the selected menu then set the flag to true
          if ( in_array($class, $item->classes ) ) {
            return true;
          }
    }

    return false;
}



/**
 *
 * Update Login menu items 
 * @since 1.6.1
 *
 */
function swal_add_login_menu_item_to_menus() {


    $swal_menu_to_append  = is_array(get_option('swal_menu_to_append')) ? array_map('esc_attr',get_option('swal_menu_to_append')) : explode(',',esc_attr(get_option('swal_menu_to_append')));

    $swal_menu_item_text            = intval(get_option('swal_menu_item_text',SWAL_MENU_ITEM_TEXT));
    $swal_menu_item_custom_text     = esc_html(get_option('swal_menu_item_custom_text'));
    $swal_menu_login_text           = swal_menu_login_text($swal_menu_item_text,$swal_menu_item_custom_text);

    $swal_login_intro_text_link          = esc_html(get_option('swal_login_intro_text_link',__(SWAL_LOGIN_INTRO_TEXT_LINK,'sw-ajax-login')));
    $swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
    $swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');


    // Get all the menus
    $menus = wp_get_nav_menus();

    // counter to check if have to delete the old Login submenu before v 1.6.0
    $x = 0;

    foreach ( $menus as $location ) {

        // If it's one of the selected menus, add Login menu item to it
        if ( in_array( $location->slug, $swal_menu_to_append ) ) {

            // Get the current menu's menu items object
            $current_menu = wp_get_nav_menu_items($location->slug);

            $item_id  = 0;

            foreach($current_menu as $item) {

                // If Login menu item is present on the selected menu then update the settings
                if ( in_array('sw-open-login', $item->classes ) ) {

                    $item_id = $item->ID;

                    $args = array(
                        'menu-item-parent-id'   => $item->menu_item_parent,
                        'menu-item-position'    => $item->menu_order,
                        'menu-item-title'       => $swal_menu_login_text,
                        'menu-item-url'         => wp_login_url(),
                        'menu-item-attr-title'  => $swal_menu_login_text,
                        'menu-item-classes'     => 'swal-menu-item sw-open-login',
                        'menu-item-status'      => 'publish',
                    );
                    
                    wp_update_nav_menu_item( $location->term_id, $item_id, $args );

                    // Move the menu items from the deprecated location 'swal-user-menu-item'
                    $args = array(
                        'current_menu' => $current_menu,
                        'menu_item_parent_id' => $item_id,
                        'menu_id' => $location->term_id,
                        'add_logout' => true,
                	); 
                    swal_move_menu_items_from_old_submenu($args);

                    $x++;
                }

                // If Register menu item is present on the selected menu then update the settings
                if ( in_array('sw-open-register', $item->classes ) ) {
                    $args = array(
                        'menu-item-parent-id'   => $item->menu_item_parent,
                        'menu-item-position'    => $item->menu_order,
                        'menu-item-title'       => $swal_login_intro_text_link,
                        'menu-item-url'         => wp_registration_url(),
                        'menu-item-attr-title'  => $swal_login_intro_text_link,
                        'menu-item-classes'     => 'swal-menu-item sw-open-register',
                        'menu-item-status'      => 'publish',
                    );
                    
                    wp_update_nav_menu_item( $location->term_id, $item->ID, $args );
                }

                // If Register menu item is present on the selected menu then update the settings
                if ( in_array('open_logout', $item->classes ) ) {
                    $args = array(
                        'menu-item-parent-id'   => $item->menu_item_parent,
                        'menu-item-position'    => $item->menu_order,
                        'menu-item-title'       => $swal_menu_item_logout_custom_text,
                        'menu-item-url'         => swal_logout_url(),
                        'menu-item-attr-title'  => $swal_menu_item_logout_custom_text,
                        'menu-item-classes'     => 'swal-menu-item open_logout',
                        'menu-item-status'      => 'publish',
                    );
                    
                    wp_update_nav_menu_item( $location->term_id, $item->ID, $args );
                }
            }

            // If there isn't any Login menu item yet, then add it
            if ( !$item_id ) {
                $args = array(
                    'menu-item-title'       => $swal_menu_login_text,
                    'menu-item-url'         => wp_login_url(),
                    'menu-item-attr-title'  => $swal_menu_login_text,
                    'menu-item-classes'     => 'swal-menu-item sw-open-login',
                    'menu-item-status'      => 'publish',
                );
                $menu_item_parent = wp_update_nav_menu_item( $location->term_id, 0, $args );

                // Add logout item
                $args = array(
                        'menu-item-parent-id'   => $menu_item_parent,
                        'menu-item-title'       => $swal_menu_item_logout_custom_text,
                        'menu-item-url'         => swal_logout_url(),
                        'menu-item-attr-title'  => $swal_menu_item_logout_custom_text,
                        'menu-item-classes'     => 'swal-menu-item open_logout',
                        'menu-item-status'      => 'publish',
                    );
                    
                wp_update_nav_menu_item( $location->term_id, 0, $args );

                // Move the menu items from the deprecated location 'swal-user-menu-item'
                $args = array(
                        'current_menu' => $current_menu,
                        'menu_item_parent_id' => $menu_item_parent,
                        'menu_id' => $location->term_id,
                        'add_logout' => false,
                ); 
                swal_move_menu_items_from_old_submenu($args);
            	$x++;
            }

        }
    }

    return $x;
   
}



/**
 *
 * Check if the old nav menu SW Ajax Login User Menu has menu associated, 
 * if yes then move the menu items to the new ones including the new sw-open-login
 * 
 * @since 1.6.0
 */
function swal_move_menu_items_from_old_submenu($args=array()) {

	$defaults = array(
                        'location' => 'swal-user-menu-item',
                        'current_menu' => '',
                        'menu_item_parent_id' => '',
                        'menu_id' => '',
                        'add_logout' => false,
                ); 
    $r = wp_parse_args( $args, $defaults );

	// Check if nav menu has a menu associated to it
    $has_menu = has_nav_menu($r['location']);

    // if not returns
    if (!$has_menu) {
		return false;
    }

    $swal_menu_item_logout_text          = intval(get_option('swal_menu_item_logout_text'));
    $swal_menu_item_logout_custom_text   = ($swal_menu_item_logout_text && get_option('swal_menu_item_logout_custom_text')) ? esc_html(get_option('swal_menu_item_logout_custom_text')) : esc_html__('Logout','sw-ajax-login');
    
    // Get all locations
    $locations = get_nav_menu_locations();

    // Get object id by location
    $object = wp_get_nav_menu_object( $locations[$r['location']] );

    // Get menu items by menu name
    $menu_items = wp_get_nav_menu_items( $object->name);

    foreach ( $menu_items as $item ) {

    $classes = '';
    if (isset($item->classes)) {
    	$classes = implode(" ",$item->classes);
    }

    	$args = array(
                        'menu-item-parent-id'   => $r['menu_item_parent_id'],
                        'menu-item-position'    => $item->menu_order,
                        'menu-item-title'       => $item->title,
                        'menu-item-url'         => $item->url,
                        'menu-item-attr-title'  => $item->attr_title,
                        'menu-item-classes'     => $classes,
                        'menu-item-status'      => 'publish',
                    );
        // add the menu items as submenu of login menu item
        wp_update_nav_menu_item( $r['menu_id'], 0, $args );
    }

    // Check if the navigation menu where the deprecated submenu has been moved has a logout item.
    // if not then add it.
    
    foreach ( $r['current_menu'] as $item ) {
		if ( in_array('open_logout', $item->classes ) || !$r['add_logout']) {
			return;
		}
	}

    $args = array(
                'menu-item-parent-id'   => $r['menu_item_parent_id'],
                'menu-item-title'       => $swal_menu_item_logout_custom_text,
                'menu-item-url'         => swal_logout_url(),
                'menu-item-attr-title'  => $swal_menu_item_logout_custom_text,
                'menu-item-classes'     => 'swal-menu-item open_logout',
                'menu-item-status'      => 'publish',
            );
                    
    wp_update_nav_menu_item( $r['menu_id'], 0, $args );
 
    return;
}



/**
 *
 * Delete the menu associated to the old 'swal-user-menu-item' navigation menu
 * 
 * @since 1.6.0
 */
function swal_delete_menu_items_from_old_submenu() {

	$status = false;

    // Get all locations
    $locations = get_nav_menu_locations();

    // Get object id by location
    $object = wp_get_nav_menu_object( $locations['swal-user-menu-item'] );

    if ($object) {
    	$status = wp_delete_nav_menu($object->slug);
    }

    return $status;
}







/**
 *
 * Mailpoet Integration
 *
 * @since 1.6.1
 */
add_action( 'user_register', 'swal_add_mailpoet_integration', 100 );

function swal_add_mailpoet_integration($user_id) {

 	if (class_exists(\MailPoet\API\API::class)) {
	  $mailpoet_api = \MailPoet\API\API::MP('v1');

	  // Get available list to subscribe the subscriber
  	  $lists = $mailpoet_api->getLists();

  	  $list_ids = array();

  	  if (is_array($lists)) {
	        foreach ($lists as $ids) {
	        	$list_ids[] = $ids['id'];
	        }
    	}	


	  if (isset($_POST['mailpoet']['subscribe_on_register']) && (bool)$_POST['mailpoet']['subscribe_on_register'] === true) {

	  	// Get user details
	  	$user = get_user_by('id', $user_id);

	  	if (!$user) {
	  		return;
	  	}

	  	$email 			= $user->user_email;
	  	$user_login 	= $user->user_login;
	    $first_name 	= $user->first_name;
	    $last_name 		= $user->last_name;

	    // If first name is empty give it the username
	    $first_name 	= $first_name ? $first_name : $user_login;

		  // Check if subscriber exists. If subscriber doesn't exist an exception is thrown
		  try {
		    $get_subscriber = $mailpoet_api->getSubscriber($email);
		  } catch (\Exception $e) {}

		  try {
		    if (!$get_subscriber) {
		      // Subscriber doesn't exist let's create one
		      $mailpoet_api->addSubscriber([
									        'email' => $email,
									        'first_name' => $first_name,
									        'last_name' => $last_name,
									      ], $list_ids);

		    } else {
		      // In case subscriber exists just add him to new lists
		      $mailpoet_api->subscribeToLists($email, $list_ids);
		    }
		  } catch (\Exception $e) {
		    $error_message = $e->getMessage(); 
		  }
		}

	}
}



/**
 *
 * Version compare, better solution
 *
 */
function swal_version_compare($ver1, $ver2, $operator = null)
{
    $p = '#(\.0+)+($|-)#';
    $ver1 = preg_replace($p, '', $ver1);
    $ver2 = preg_replace($p, '', $ver2);
    return isset($operator) ? 
        version_compare($ver1, $ver2, $operator) : 
        version_compare($ver1, $ver2);
}

/**
 *
 * Minimize CSS
 *
 * @since 1.7.5
 */
function swal_minimizeCSSsimple($css){
	$css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
	$css = preg_replace('/\s{2,}/', ' ', $css);
	$css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
	$css = preg_replace('/;}/', '}', $css);
	return $css;
}


/**
 *
 * Check if no autologin is enabled
 *
 * @since 1.8.0
 *
 */
function swal_register_no_autologin() {

    $swal_register_no_autologin             = intval( get_option('swal_register_no_autologin', false) );
    $swal_register_with_approval    		= intval(get_option('swal_register_with_approval'));

    if ($swal_register_no_autologin && strval($swal_register_with_approval) != '1') {
        return true;
    }
    return false;
}





/**
 *
 * Check if user validation is enabled, for frontend use
 *
 * @since 1.8.0
 *
 */
function swal_is_validation_enabled() {

	$swal_register_no_autologin         = intval(get_option('swal_register_no_autologin', false) );
    $swal_register_with_approval    	= intval(get_option('swal_register_with_approval'));

    if (!$swal_register_no_autologin || strval($swal_register_no_autologin) == '0') {
        return false;
    }

    return $swal_register_with_approval;
}


/**
 *
 * Check if user validation is enabled, for admin use
 *
 * @since 1.8.0
 *
 */
function swal_is_admin_validation_enabled() {

	$swal_register_no_autologin     = intval(get_option('swal_register_no_autologin', false) );
	$swal_register_with_approval    = intval(get_option('swal_register_with_approval'));

	if (!$swal_register_no_autologin) {
		return false;
	}

	if ($swal_register_no_autologin && $swal_register_with_approval) {
		return true;
	}

    return false;
}


/**
 *
 * Get social icon from user meta_data value.
 *
 * @since 1.8.0
 */
function swal_get_social_icon($value='') {

$output = '';

  switch ($value) {
    case 'facebook':
      $output = '<span class="dashicons dashicons-facebook"></span>';
      break;

    case 'twitter':
      $output = '<span class="dashicons dashicons-twitter"></span>';
      break;

    case 'google':
      $output = '<span class="dashicons dashicons-google"></span>';
      break;

    case 'googleplus':
      $output = '<span class="dashicons dashicons-google"></span>';
      break;

    case 'linkedin':
      $output = '<span class="dashicons dashicons-linkedin"></span>';
      break;

    case 'amazon':
      $output = '<span class="dashicons dashicons-amazon"></span>';
      break;
    
    default:
      $output = '<span class="dashicons dashicons-wordpress"></span>';
      break;
  }

  return $output;
}


/**
 *
 * Add image upload buttons
 *
 * @since 1.0.0
 */
function sw_ajax_login_image_uploader( $name, $width, $height ) {

    // Set variables
    $options = esc_attr(get_option( $name ));
    $default_image = SWAL_PLUGIN_ADMIN_IMAGES.'/no-image.jpg';

    if ( !empty( $options ) ) {
        $image_attributes = wp_get_attachment_image_src( $options, array( $width, $height ) );
        $src = $image_attributes[0];
        $value = $options;
    } else {
        $src = $default_image;
        $value = '';
    }

    $text = __( 'Upload', 'sw-ajax-login' );

    // Print HTML field
    echo '
        <div class="upload">
            <img data-src="' . $default_image . '" src="' . $src . '" width="' . $width . 'px" class="swal-admin-img" />
            <div>
                <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
                <button type="submit" class="upload_image_button button">' . $text . '</button>
                <button type="submit" class="remove_image_button button">&times;</button>
            </div>
        </div>
    ';
}


/**
 *
 * Create username from email
 * 
 * if the new username already exists then add a numeric suffix
 *
 * @since 1.8.6
 *
 * @return string 	username
 */
function swal_create_username_from_email( $email ) {

	$username = '';

	$parts = explode("@", $email);
	  if (count($parts) == 2) {
	     $username = esc_attr($parts[0]);
	  }

	$users = new WP_User_Query( array(
	    'search'         => $username .'*',
	    'search_columns' => array(
	        'user_login',
	    ),
	) );
	$users_found = $users->get_total();

	if ( $users_found ) {
		$username = $username . $users_found;
	}

	/**
	 * Filters the username
	 *
	 * @since 1.9.8
	 */
	$username = apply_filters('swal_update_username_from_email', $username, $email);

    return $username;
}

/**
 *
 * Reset value button for admin
 * 
 * @since 1.9.3
 *
 * @return string 	html tags
 */ 
function swal_reset_value_button() {
	$output = '<span class="dashicons dashicons-undo swal-reset-value" title="'.esc_html__('Reset value','sw-ajax-login').'"></span>';

	echo $output;
}
?>