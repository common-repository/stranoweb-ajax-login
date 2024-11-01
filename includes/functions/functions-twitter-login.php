<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/*
 * Twitter Login
 */
require_once SWAL_PLUGIN_PATH . '/src/twitteroauth/autoload.php';


use Abraham\TwitterOAuth\TwitterOAuth;


add_action( 'init', 'swal_twitter_get_oauth_token', 9 );


/*
 * Request Twitter oauth_token
 */
function swal_twitter_get_oauth_token() {

if(isset($_GET["action"])) {
		if($_GET["action"] == "swal-twlogin") {


	$client_id = esc_attr(get_option('swal_twitter_id')); // twitter APP Client ID
	$client_secret = esc_attr(get_option('swal_twitter_secret_key')); // twitter APP Client secret
	$redirect_uri_login = home_url();
	$redirect_to = remove_query_arg('action',swal_page_to_redirect());
	$redirect_uri = add_query_arg( array(
							    'login' => 'twitter',
							    'redirect_to' => $redirect_to
							), home_url() );

	$config = array(
		  'consumer_key' => $client_id,
		  'consumer_secret' => $client_secret,
		  'url_login'         => $redirect_uri_login,
	      'url_callback'      => $redirect_uri
		  );


	// create TwitterOAuth object
		$twitteroauth = new TwitterOAuth($config['consumer_key'], $config['consumer_secret']);

		//die($config['url_callback']);

	// request token of application
		$request_token = $twitteroauth->oauth(
		    'oauth/request_token', array(
		        'oauth_callback' => $config['url_callback']
		    )
		);

	set_transient( 'oauth_token_' . $_COOKIE['swal_visitor_unique_value'], $request_token['oauth_token'], 300 ); // 5 min cache
	set_transient( 'oauth_token_secret_' . $_COOKIE['swal_visitor_unique_value'], $request_token['oauth_token_secret'], 300 ); // 5 min cache
	
	// generate the URL to make request to authorize our application
		$url = $twitteroauth->url(
		    'oauth/authorize', [
		        'oauth_token' => $request_token['oauth_token']
		    ]
		);


		// and redirect
		header('Location: '. $url);


		die();

	}
	}
}


add_action( 'init', 'swal_twitter_login', 10 );

function swal_twitter_login() {
//verify if twitter login is ok
if(isset($_GET["login"])) {
		if($_GET["login"] == "twitter") {

	$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

	$redirect_uri_login = swal_page_to_redirect(); 
	
	 
	if (empty($oauth_verifier) ||
		empty(get_transient( 'oauth_token_' . $_COOKIE['swal_visitor_unique_value'] )) ||
		empty(get_transient( 'oauth_token_secret_' . $_COOKIE['swal_visitor_unique_value'] ))
	) {
	    // something's missing, go and login again
	    header('Location: ' . $redirect_uri_login);
	} else {


	$client_id = esc_attr(get_option('swal_twitter_id')); // twitter APP Client ID
	$client_secret = esc_attr(get_option('swal_twitter_secret_key')); // twitter APP Client secret
	

	$config = array(
		  'consumer_key' => $client_id,
		  'consumer_secret' => $client_secret,
		  );

	// connect with application token
	$connection = new TwitterOAuth(
	    $config['consumer_key'],
	    $config['consumer_secret'],
	    get_transient( 'oauth_token_' . $_COOKIE['swal_visitor_unique_value'] ),
	    get_transient( 'oauth_token_secret_' . $_COOKIE['swal_visitor_unique_value'] )
	);

	
	// request user token
	$access_token = $connection->oauth(
	    'oauth/access_token', [
	        'oauth_verifier' => $oauth_verifier
	    ]
	);

	// connect with user token
	$connection = new TwitterOAuth(
		$config['consumer_key'], 
		$config['consumer_secret'], 
		$access_token['oauth_token'], 
		$access_token['oauth_token_secret']
		);

	//get user credentials
	$userInfo = $connection->get('account/verify_credentials', [
					'include_email' => 'true',
					'skip_status' => 'true'
		]
	);

	// Delete transients
	delete_transient( 'oauth_token_' . $_COOKIE['swal_visitor_unique_value'] );
	delete_transient( 'oauth_token_secret_' . $_COOKIE['swal_visitor_unique_value'] );


	$name = explode(" ",$userInfo->name);
    $first_name = isset($name[0])?$name[0]:'';
    $last_name = isset($name[1])?$name[1]:'';
    $user_email = $userInfo->email;
    $profileLink = 'https://twitter.com/'.$userInfo->screen_name;
    $profile_picture = $userInfo->profile_image_url_https;

    $parts = explode("@", $user_email);
	$email_username = $parts[0];

	$username = $email_username .'twt'.$profileLink;

	
	$userdata = array(
				'user_login'  =>  sanitize_user($username),
				'user_pass'   =>  wp_generate_password(), // random password, you can also send a notification to new users, so they could set a password themselves
				'user_email' => sanitize_email($user_email),
				'first_name' => sanitize_user($first_name),
				'last_name' => sanitize_user($last_name),
				'social' => 'twitter',
				'social_link' => sanitize_user($profileLink),
				'profile_picture' => sanitize_text_field($profile_picture),
				'echo_json' => false,
			);

	//login if user already exists or insert if user not exists
	swal_social_login_insert_user($userdata);

	//remove all twitter query_args and reload the page
	//$redirect_uri = esc_url( remove_query_arg( array('login', 'oauth_token', 'oauth_verifier'), swal_page_to_redirect() ) );
	$redirect_uri = esc_url(filter_input(INPUT_GET, 'redirect_to')); 
	wp_redirect( $redirect_uri);
	die ();
	}
	}
 }
}

?>
