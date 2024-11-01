<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/*
 * Google+ Login
 */
require_once SWAL_PLUGIN_PATH . '/src/google-login-api.php';


add_action( 'init', 'swal_google_get_oauth_token' );


/*
 * Request Google+ oauth_token
 */
function swal_google_get_oauth_token() {



	$client_id = esc_attr(get_option('swal_google_id')); // google APP Client ID
	$client_secret = esc_attr(get_option('swal_google_secret_key')); // google APP Client secret
	$redirect_uri = home_url(); // URL of page/file that processes a request
	//$redirect_uri = add_query_arg( 'login', 'google', $redirect_uri );

	// Google passes a parameter 'code' in the Redirect Url
	if(isset($_GET['code'])) {
		try {
			$gapi = new GoogleLoginApi();
			
			// Get the access token 
			$data = $gapi->GetAccessToken($client_id, $redirect_uri, $client_secret, $_GET['code']);

			// Access Token
			$access_token = $data['access_token'];
			
			// Get user information
			$userinfo = $gapi->GetUserProfileInfo($access_token);

			
			$name = explode(" ",$userinfo['displayName']);
		    $first_name = isset($name[0])?$name[0]:'';
		    $last_name = isset($name[1])?$name[1]:'';
		    $user_email = $userinfo['emails'][0]['value'];
		    $profileLink = $userinfo['url'];
		    $profile_picture = $userinfo['image']['url'];

			
			$userdata = array(
						'user_login'  =>  sanitize_email($user_email),
						'user_pass'   =>  wp_generate_password(), // random password, you can also send a notification to new users, so they could set a password themselves
						'user_email' => sanitize_email($user_email),
						'first_name' => sanitize_user($first_name),
						'last_name' => sanitize_user($last_name),
						'social' => 'google',
						'social_link' => sanitize_user($profileLink),
						'profile_picture' => sanitize_text_field($profile_picture),
						'echo_json' => false,
					);

			//login if user already exists or insert if user not exists
			swal_social_login_insert_user($userdata);

			//remove all twitter query_args and reload the page
			$redirect_uri = esc_url( remove_query_arg( array('code'), swal_page_to_redirect() ) );
			wp_redirect( $redirect_uri );
			die();

			// You may now want to redirect the user to the home page of your website
			// header('Location: home.php');
		}
		catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
}


?>
