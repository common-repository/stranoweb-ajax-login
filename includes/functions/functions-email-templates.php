<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}



// Add support to shortcodes to custom text
add_filter('swal_reset_password_success_text', 'do_shortcode');


/**
 *
 * Add header and footer to WooCommerce emails
 *
 */

/*
remove_action( 'woocommerce_email_header', array( WC()->mailer(), 'email_header' ) );
remove_action( 'woocommerce_email_footer', array( WC()->mailer(), 'email_footer' ) );
add_action('woocommerce_email_header','swal_html_email_header',100);
add_action('woocommerce_email_footer','swal_html_email_footer',100);
*/

function swal_html_email_header() {

	$swal_email_content_color  	= get_option('swal_email_content_color') ? sanitize_hex_color(get_option('swal_email_content_color')) : SWAL_EMAIL_CONTENT_COLOR;
	$swal_email_container_width = get_option('swal_email_container_width') ? intval(get_option('swal_email_container_width')) : 600;

	echo swal_email_header() . '<div style="width:'.($swal_email_container_width - 60).'px; padding:20px 30px 30px;margin:0 auto;
				color:'.$swal_email_content_color.';line-height:1.5em;" id="email_content">';
}

function swal_html_email_footer() {

	echo '</div>' . swal_email_footer();
}

/**
 *
 * HEADER email
 *
 */
function swal_email_header() {

	$swal_email_body_color            	= get_option('swal_email_body_color') ? sanitize_hex_color(get_option('swal_email_body_color')) : SWAL_EMAIL_BODY_COLOR;
	$swal_email_content_background_color       = get_option('swal_email_content_background_color') ? sanitize_hex_color(get_option('swal_email_content_background_color')) : SWAL_EMAIL_CONTENT_BACKGROUND_COLOR;
	$swal_email_header_color            = get_option('swal_email_header_color') ? sanitize_hex_color(get_option('swal_email_header_color')) : SWAL_EMAIL_HEADER_COLOR;
	$swal_email_header_text_color       = get_option('swal_email_header_text_color') ? sanitize_hex_color(get_option('swal_email_header_text_color')) : SWAL_EMAIL_HEADER_TEXT_COLOR;
	$swal_email_header_image_align      = intval(get_option('swal_email_header_image_align',SWAL_EMAIL_HEADER_IMAGE_ALIGN));
	$swal_email_body_shadow             = intval(get_option('swal_email_body_shadow',SWAL_EMAIL_BODY_SHADOW));
	$swal_email_hide_title              = intval(get_option('swal_email_hide_title'));
	$swal_email_hide_header             = intval(get_option('swal_email_hide_header'));
	$swal_email_container_width         = get_option('swal_email_container_width') ? intval(get_option('swal_email_container_width')) : 600;
	$swal_email_link_color              = get_option('swal_email_link_color') ? sanitize_hex_color(get_option('swal_email_link_color')) : SWAL_LINK_COLOR;
	$swal_email_header_text_size        = get_option('swal_email_header_text_size') ? intval(get_option('swal_email_header_text_size')) : 24;

	// Get the header title
	$titlestyle = ($swal_email_header_image_align == 2) ? ' style="clear:both;display:block;text-align:center;"' : '';
	$header_title = !$swal_email_hide_title ? '<span'.$titlestyle.'>'.get_bloginfo( 'name' ).'</span>' : '';

	// Check where to place the image
	$before_header_img 	= '';
	$header_img 		= '';

	// Check the position to place the image and title
	switch ($swal_email_header_image_align) {
                case 0:
                    $args = array(
									'image'     	=> get_option('swal_email_custom_logo'),
									'div_class'	 	=> false,
									'img_style'	 	=> 'max-width:'.$swal_email_container_width.'px;text-align:center; margin-top: 30px;',
									'echo'	  		=> false,
								);
					$before_header_img = sw_display_image($args);
					$merge_title_img 	= $header_img . $header_title;
                    break;
                case 1:
                    $args = array(
									'image'     	=> get_option('swal_email_custom_logo'),
									'div_class'	 	=> false,
									'img_style'	 	=> 'max-width: 300px; margin-right: 20px; float: left;',
									'echo'	  		=> false,
								);
					$header_img = sw_display_image($args);
					$merge_title_img 	= $header_img . $header_title;
                    break;
                case 2:
                    $args = array(
									'image'     	=> get_option('swal_email_custom_logo'),
									'div_class'	 	=> false,
									'img_style'	 	=> 'display:block;max-width: 300px; margin: 0 auto 20px;',
									'echo'	  		=> false,
								);
					$header_img = sw_display_image($args);
					$merge_title_img 	= $header_img . $header_title;
                    break;
                case 3:
                    $args = array(
									'image'     	=> get_option('swal_email_custom_logo'),
									'div_class'	 	=> false,
									'img_style'	 	=> 'max-width: 300px; margin-right: 20px; float: right;',
									'echo'	  		=> false,
								);
					$header_img = sw_display_image($args);
					$merge_title_img 	= $header_title . $header_img;
                    break;
            }

	

	// Check to add the container shadow
	$shadow 	= $swal_email_body_shadow ? '-webkit-box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.3);
				-moz-box-shadow:    0px 1px 3px 0px rgba(0, 0, 0, 0.3);
   				box-shadow:         0px 1px 3px 0px rgba(0, 0, 0, 0.3);' : '';

	$email = '<html>
	<head>
		
		<title>'. get_bloginfo( 'name' ) .'</title>
		<style type="text/css">
			#email_container p {
				font-size: 14px !important;
				margin-bottom: 20px !important;
			}
			#email_container a {
			  overflow-wrap: break-word;
			  word-wrap: break-word;
			  -ms-word-break: break-all;
			  word-break: break-all;
			  word-break: break-word;
			}
			#email_container h1 {
				padding:0px 0 15px 0; 
				font-weight:400;
				font-size:26px;
				color:#444;
				border-bottom:1px solid #ddd;
				margin-bottom: 20px;
				max-width: 540px;
			}
			#email_container a {
				color:'.$swal_email_link_color.';
			}
			#email_header span {
				max-width: '.($swal_email_container_width - 60).'px;
				display: table-cell;
				vertical-align: middle;
				width: 100%;
			}
			#email_footer p {
				font-size:12px; 
			}
		</style>
	</head>
	<body style="background-color: '.$swal_email_body_color.'; font-family: Helvetica,Arial,sans-serif;">
		<div style="text-align: center;">'
		.$before_header_img.
		'</div>
		<div id="email_container" style="width:'.$swal_email_container_width.'px; background-color: '.$swal_email_content_background_color.'; padding: 0; margin: 30px auto 0;
				border-radius: 4px;'.$shadow.'">';
		// hide or show header
		if (!$swal_email_hide_header) {
			$email .= '<div style="padding: 30px 30px 25px; margin: 0;display:table;width:'.($swal_email_container_width-60).'px;
				font-size:'.$swal_email_header_text_size.'px; font-weight:bold; border-radius: 4px 4px 0 0;
				background:'.$swal_email_header_color.';color:'.$swal_email_header_text_color.';" id="email_header">'. $merge_title_img .'
			<div style="clear:both;"></div>
			</div>';
			}

	return $email;
}


/**
 *
 * BODY wrapper
 *
 */

function swal_email_body_wrapper($content) {

	$swal_email_content_color  	= get_option('swal_email_content_color') ? sanitize_hex_color(get_option('swal_email_content_color')) : SWAL_EMAIL_CONTENT_COLOR;
	$swal_email_container_width = get_option('swal_email_container_width') ? intval(get_option('swal_email_container_width')) : 600;

	$content = '<div style="width:'.($swal_email_container_width-60).'px; padding:20px 30px 30px;margin:0 auto;
				color:'.$swal_email_content_color.';line-height:1.5em;" id="email_content">' . $content. '</div>';

	return $content;

}
		

/**
 *
 * FOOTER wrapper
 *
 */
function swal_email_footer() {

	$swal_email_container_width         = get_option('swal_email_container_width') ? intval(get_option('swal_email_container_width')) : 600;

	$email = '</div>
				<div style="width: '.($swal_email_container_width - 60).'px;text-align:center;padding:20px;margin: 0 auto;" id="email_footer"> 
					<div style="color:#999; line-height:20px;">'. swal_email_footer_content() .'</div>
				</div>
		</body>
	</html>';

	return $email;
}

/**
 *
 * FOOTER email
 *
 */
function swal_email_footer_content($process=false) {

	/**
	 *
	 * Footer text
	 *
	 */
	$swal_footer_default_text = '<p>'.__('You have received this email because you are a member of','sw-ajax-login').' {SITE_NAME}</p>';

	$swal_email_footer                  = html_entity_decode(strip_tags(get_option('swal_email_footer')));
	$swal_custom_email_templates        = intval(get_option('swal_custom_email_templates'));

	$email = ($swal_email_footer && $swal_custom_email_templates) ? wpautop($swal_email_footer) : $swal_footer_default_text;

	$args = array();

	// now format the text replacing the placeholders
	$email = !$process ? sw_linkify(swal_replace_placeholders($email,$args)) : $email;

	return $email;
}


/**
 *
 * MERGE HEADER, CONTENT and FOOTER
 *
 */
function swal_create_custom_email($content) {	

	$message = swal_email_header();
	$message .= swal_email_body_wrapper($content);
	$message .= swal_email_footer();

	return $message;
}

/**
 *
 * HEADERS
 *
 */
function swal_create_headers() {

	// get the site name without www. 
    $sitename = strtolower( $_SERVER['SERVER_NAME'] );
        if ( substr( $sitename, 0, 4 ) == 'www.' ) {
            $sitename = substr( $sitename, 4 );                 
        }
    $emailfrom = 'admin@'.$sitename;

    $swal_email_from = sanitize_email(get_option('swal_email_from'));
			
	if(!(isset($swal_email_from) && is_email($swal_email_from))) {		
		$from = 'admin@'.$sitename; 
	} else {
		$from = $swal_email_from; 
	}

	$sender = 'From: '.get_bloginfo( 'name' ).' <'.$from.'>' . "\r\n";

	$headers[] = 'MIME-Version: 1.0' . "\r\n";
	$headers[] = 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers[] = "X-Mailer: PHP \r\n";
	$headers[] = $sender;

	return $headers;
}




/**
 *
 * BODIES & EMAIL SUBJECTS
 *
 */


/**
 *
 * New user registration subject
 *
 */
function swal_new_user_email_body_subject($username,$process=false,$default=false) {

	/**
	 *
	 * default subject
	 *
	 */
	$default_text = __('{SITE_NAME}, New user {USERNAME} registered.', 'sw-ajax-login' );

	$swal_email_new_user_registration_subject   = html_entity_decode(strip_tags(get_option('swal_email_new_user_registration_subject')));
	$swal_custom_email_templates        		= intval(get_option('swal_custom_email_templates'));

	/**
	 *
	 * Check which text to show
	 *
	 */
	$subject = ($swal_email_new_user_registration_subject && $swal_custom_email_templates && !$default) ? $swal_email_new_user_registration_subject : $default_text;

	// passing arguments to the function that replaces placeholders
	$args = array(
					'username'	=> $username,
					);

	// now format the text replacing the placeholders
	$subject = !$process ? swal_replace_placeholders($subject,$args) : $subject;

	return $subject;
}


/**
 *
 * New user registration content
 *
 */
function swal_new_user_email_body($username,$process=false) {

	/**
	 *
	 * New user default text
	 *
	 */
	$swal_new_user_default_text = '<h2>'.__('Welcome to {SITE_NAME}','sw-ajax-login').'</h2>
									<p>'.__('Your username is:','sw-ajax-login').' <strong>{USERNAME}</strong><br/><br/>
									   '.__('To login to the website please go to: ','sw-ajax-login').' {LOGIN_PAGE}<br/><br/>
									   '.__('Warm regards','sw-ajax-login').',<br>
										{SITE_NAME}
									</p>';

	$swal_email_new_user_registration   = html_entity_decode(strip_tags(get_option('swal_email_new_user_registration')));
	$swal_custom_email_templates        = intval(get_option('swal_custom_email_templates'));

	/**
	 *
	 * Check which text to show
	 *
	 */
	$email = ($swal_email_new_user_registration && $swal_custom_email_templates) ? wpautop($swal_email_new_user_registration) : $swal_new_user_default_text;

	// passing arguments to the function that replaces placeholders
	$args = array(
					'username'	=> $username,
					);

	// now format the text replacing the placeholders
	$email = !$process ? sw_linkify(swal_replace_placeholders($email,$args)) : $email;

	return $email;
}


/**
 *
 * New user registration without password fields content
 *
 */
function swal_new_user_no_password_email_body($username,$password,$process=false) {

	/**
	 *
	 * New user default text
	 *
	 */
	$swal_new_user_default_text = '<h2>'.__('Welcome to {SITE_NAME}','sw-ajax-login').'</h2>
									<p>'.__('Your username is:','sw-ajax-login').' <strong>{USERNAME}</strong><br/><br/>
									   '.__('Your password is:','sw-ajax-login').' <strong>{NEW_PASSWORD}</strong><br/><br/>
									   '.__('To login to the website please go to: ','sw-ajax-login').' {LOGIN_PAGE}<br/><br/>
									   '.__('Warm regards','sw-ajax-login').',<br>
										{SITE_NAME}
									</p>';

	$swal_email_new_user_registration_no_pwd   = html_entity_decode(strip_tags(get_option('swal_email_new_user_registration_no_pwd')));
	$swal_custom_email_templates        = intval(get_option('swal_custom_email_templates'));

	/**
	 *
	 * Check which text to show
	 *
	 */
	$email = ($swal_email_new_user_registration_no_pwd && $swal_custom_email_templates) ? wpautop($swal_email_new_user_registration_no_pwd) : $swal_new_user_default_text;

	// compose the reset password link with username and generated key
	$url = swal_generate_reset_password_url($username);
	
	// passing arguments to the function that replaces placeholders
	$args = array(
					'username'	=> $username,
					'new_password'	=> $password,
					'reset_password_url'	=> $url,
					);


	// now format the text replacing the placeholders
	$email = !$process ? sw_linkify(swal_replace_placeholders($email,$args)) : $email;

	return $email;
}




/**
 *
 * Forgot Password subject
 *
 */
function swal_forgot_password_email_body_flow_subject($process=false,$default=false) {

	/**
	 *
	 * default subject
	 *
	 */
	$default_text = __('{SITE_NAME}, Your new password.', 'sw-ajax-login' );

	$swal_email_forgot_password_subject   = html_entity_decode(strip_tags(get_option('swal_email_forgot_password_subject')));
	$swal_custom_email_templates        		= intval(get_option('swal_custom_email_templates'));

	/**
	 *
	 * Check which text to show
	 *
	 */
	$subject = ($swal_email_forgot_password_subject && $swal_custom_email_templates && !$default) ? $swal_email_forgot_password_subject : $default_text;

	// now format the text replacing the placeholders
	$subject = !$process ? swal_replace_placeholders($subject) : $subject;

	return $subject;
}

/**
 *
 * Forgot Password first email with the link to create a new one
 *
 */
function swal_forgot_password_email_body_flow($user_first_name,$user_login,$key,$process=false) {

	/**
	 *
	 * Forgot Password first email with the link to create a new one
	 *
	 */
	$swal_forgot_password_flow_default_text = '<h1>'.__('Password Reset','sw-ajax-login').'</h1>
                                    <p>'.__( 'Hello {FIRST_NAME}!', 'sw-ajax-login' ) . '<br/><br/>'
                                        .__( 'You asked us to reset your password for your account.', 'sw-ajax-login' ) . '<br/><br/>'
                                        .__( 'If this was a mistake, or you didn\'t ask for a password reset, just ignore this email and nothing will happen.', 'sw-ajax-login' ) . '<br/><br/>'
                                        .__( 'To reset your password, visit the following address:', 'sw-ajax-login' ) . '<br/><br/>
                                        {RESET_PASSWORD_URL}<br/><br/>'
                                        .__( 'Warm regards', 'sw-ajax-login' ) . ',<br>
                                        {SITE_NAME}
                                    </p>';

	$swal_email_forgot_password         = html_entity_decode(strip_tags(get_option('swal_email_forgot_password')));
	$swal_custom_email_templates        = intval(get_option('swal_custom_email_templates'));

	// compose the reset password link with username and generated key
	$url = swal_resetpassword_url()."?key=$key&login=" . rawurlencode( $user_login );

	/**
	 *
	 * Check which text to show
	 *
	 */
	$email = ($swal_email_forgot_password && $swal_custom_email_templates) ? wpautop($swal_email_forgot_password) : $swal_forgot_password_flow_default_text;

	// passing arguments to the function that replaces placeholders
	$args = array(
				'first_name'    => $user_first_name,
				'reset_password_url'	=> $url,
				);
	$email = !$process ? sw_linkify(swal_replace_placeholders($email,$args)) : $email;

	return $email;
}






/**
 *
 * Success email on recovery password subject
 *
 */
function swal_reset_password_email_body_subject($process=false,$default=false) {

	/**
	 *
	 * default subject
	 *
	 */
	$default_text = __('{SITE_NAME}, You have reset your password.', 'sw-ajax-login' );

	$swal_email_reset_password_subject   = html_entity_decode(strip_tags(get_option('swal_email_reset_password_subject')));
	$swal_custom_email_templates         = intval(get_option('swal_custom_email_templates'));

	/**
	 *
	 * Check which text to show
	 *
	 */
	$subject = ($swal_email_reset_password_subject && $swal_custom_email_templates && !$default) ? $swal_email_reset_password_subject : $default_text;

	// now format the text replacing the placeholders
	$subject = !$process ? swal_replace_placeholders($subject) : $subject;

	return $subject;
}

/**
 *
 * Success email on recovery password (the right one)
 *
 */
function swal_reset_password_email_body($user_first_name,$user_password,$swal_show_password_reset_password_email,$process=false) {

	/**
	 *
	 * Success email on recovery password
	 *
	 */
	$swal_forgot_password_success_default_text = '<h1>'.__('Password Reset','sw-ajax-login').'</h1>
									<p>'.__( 'Hello {FIRST_NAME},', 'sw-ajax-login' ).'<br/><br/>
										[if-show-password]'.__('Your new password is:','sw-ajax-login').' <strong>{NEW_PASSWORD}</strong><br/><br/>[/if-show-password]
									   '.__('To login to the website please go to: ','sw-ajax-login').' {LOGIN_PAGE}<br/><br/>
									   '.__('Warm regards','sw-ajax-login').',<br>
										{SITE_NAME}
									</p>';

	$swal_email_reset_password 			= html_entity_decode(strip_tags(get_option('swal_email_reset_password')));
	$swal_custom_email_templates        = intval(get_option('swal_custom_email_templates'));

	/**
	 *
	 * Check which text to show
	 *
	 */
	$email = ($swal_email_reset_password && $swal_custom_email_templates) ? wpautop($swal_email_reset_password) : $swal_forgot_password_success_default_text;

	// passing arguments to the function that replaces placeholders
	$args = array(
					'first_name'    => $user_first_name,
					'new_password'	=> $user_password,
					);

	// now format the text replacing the placeholders
	$email = !$process ? sw_linkify(swal_replace_placeholders($email,$args)) : $email;

	// filter the content, it's necessary to enable shortcodes
	$email = apply_filters('swal_reset_password_success_text', $email);

	return $email;
}



/**
 *
 * Add shortcode for conditional print of the new password
 *
 */
if ( !shortcode_exists( 'if-show-password' ) ) {
	add_shortcode( 'if-show-password', 'swal_show_password_shortcode' );
}

function swal_show_password_shortcode($args,$content = false ){

	$swal_show_password_reset_password_email = esc_attr(get_option('swal_show_password_reset_password_email'));

	if ($swal_show_password_reset_password_email && $content) {
		return $content;
	} else {
		return;
	}

}


/**
 *
 * Add shortcode for conditional print of the random password
 *
 */
if ( !shortcode_exists( 'if-show-random-password' ) ) {
	add_shortcode( 'if-show-random-password', 'swal_show_random_password_shortcode' );
}

function swal_show_random_password_shortcode($args,$content = false ){

	$swal_register_form_type             = intval( get_option('swal_register_form_type', 0) );

	if ($swal_register_form_type && $content) {
		return $content;
	} else {
		return;
	}

}





?>
