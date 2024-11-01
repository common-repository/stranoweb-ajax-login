<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/*
 * Facebook Login
 */

function swal_ajax_facebook_login() {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

	$client_id = esc_attr(get_option('swal_facebook_id')); // Facebook APP Client ID
	$client_secret = esc_attr(get_option('swal_facebook_secret_key')); // Facebook APP Client secret
	$redirect_uri = home_url(); // URL of page/file that processes a request

	$fb = new Facebook\Facebook([
	  'app_id' => $client_id,
	  'app_secret' => $client_secret,
	  'default_graph_version' => 'v3.2',
	  ]);

	$helper = $fb->getJavaScriptHelper();

	try {
	  $accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
		$output = json_encode(array(
				'loggedin'=>false, 
				'message'=> __('Graph returned an error: ','sw-ajax-login') . $e->getMessage(),
				));
		echo $output;
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
		$output = json_encode(array(
				'loggedin'=>false, 
				'message'=> __('Facebook SDK returned an error: ','sw-ajax-login') . $e->getMessage(),
				));
		echo $output;
	  exit;
	}

	if (! isset($accessToken)) {
		$output = json_encode(array(
				'loggedin'=>false, 
				'message'=> __('No cookie set or no OAuth data could be obtained from cookie.','sw-ajax-login'),
				));
		echo $output;
	  exit;
	}

	// Logged in
	$_SESSION['fb_access_token'] = (string) $accessToken;


	// if ID and email exist, we can try to create new WordPress user or authorize if he is already registered
	if ( isset( $_POST['id'] ) && isset( $_POST['email'] ) ) {

		$userdata = array(
				'user_login'  =>  sanitize_email($_POST['email']),
				'user_pass'   =>  wp_generate_password(), // random password, you can also send a notification to new users, so they could set a password themselves
				'user_email' => sanitize_email($_POST['email']),
				'first_name' => sanitize_user($_POST['first_name']),
				'last_name' => sanitize_user($_POST['last_name']),
				'social' => 'facebook',
				'social_link' => sanitize_user($_POST['link']),
				'profile_picture' => swal_get_facebook_user_avatar(intval($_POST['id']),'normal'),
				//'profile_picture' => sanitize_text_field($_POST['picture[data][url]']),
				'echo_json' => true,
			);

		swal_social_login_insert_user($userdata);

	  }

	}

	die();
 
}


/*
 * Get Facebook user avatar
 */
function swal_get_facebook_user_avatar($fbId,$type){
        $json = file_get_contents('https://graph.facebook.com/v2.5/'.$fbId.'/picture?type='.$type.'&redirect=false');
        $picture = json_decode($json, true);
        return $picture['data']['url'];
}



?>
