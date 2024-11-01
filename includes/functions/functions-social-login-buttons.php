<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 *
 * SHORTCODE Social Login Buttons
 *
 */
if ( !shortcode_exists( 'swal_socials_login_buttons' ) ) {
	add_shortcode( 'swal_socials_login_buttons', 'swal_add_socials_login_buttons');
}

// Get the social logins list
if(class_exists( 'Layers_SwAjaxLogin' )) {
	$swal_socials = Layers_SwAjaxLogin::swal_get_social_logins();
} elseif (class_exists( 'Layers_SwAjaxLogin_free' )) {
	$swal_socials = Layers_SwAjaxLogin_free::swal_get_social_logins();
}

foreach ($swal_socials as $social) {
	if ( function_exists( 'swal_'.$social.'_signin_button' ) ) {
		add_action('swal_frontend_social_login_buttons','swal_'.$social.'_signin_button');
	}
}


function swal_add_socials_login_buttons() {

	//if user is already logged in then return
	if ( is_user_logged_in()) return;

	$swal_social_icons_theme        = intval(get_option('swal_social_icons_theme',SWAL_SOCIAL_ICONS_THEME));
    

	?>

	<div class="swal-login-networks theme<?php echo $swal_social_icons_theme ?> swal-clear">
		<?php
          	/**
	         *
	         * This hook is for Social login settings
	         *
	         */
	        do_action('swal_frontend_social_login_buttons');
           ?>
	</div>
	<?php
	
}



/**
 *
 * Social Login Buttons position
 *
 */

function swal_socials_login_buttons_position($swal_social_icons_position = 0,$swal_popup_layout_style ='',$title_position = 1) {

	$swal_add_socials_login       	= intval(get_option('swal_add_socials_login'));
	$swal_add_socials_login 		= intval(get_option('swal_add_socials_login'));
	$swal_add_native_socials_login  = intval(get_option('swal_add_native_socials_login'));

	if ( !$swal_add_socials_login ) {
		return;
	}

	//if social logins is not selected then return
	if (!$swal_add_socials_login) return;

		 echo  '<div class="swal-clear">';
		 	if ($title_position == 1) {
		    		echo '<h4>' . esc_html__('or','sw-ajax-login') . '</h4>';
		    	}

		    	//shortcode per login socials ACCESS PRESS SOCIAL LOGIN LITE, $swal_add_native_socials_login must be true
			    	if ( shortcode_exists( 'apsl-login-lite') && $swal_add_native_socials_login ) {
			    	
			    		echo do_shortcode('[apsl-login-lite]');
			    	}

			    	//Native socials login buttons
			    	swal_add_socials_login_buttons();

			 if ($title_position == 2) {
		    		echo '<h4>' . esc_html__('or','sw-ajax-login') . '</h4>';
		    	}
			
		    echo '</div>';
	
}



/**
 *
 * Social Login buttons
 * @since 1.7.4 the buttons are added via action
 */

/**
 *
 * Facebook
 *
 */
function swal_facebook_signin_button() {

	$swal_add_socials_login       		= intval(get_option('swal_add_socials_login'));
	$swal_add_native_facebook_login     = intval(get_option('swal_add_native_facebook_login'));
	$client_id 							= esc_attr(get_option('swal_facebook_id')); // Facebook APP Client ID

	if ( !$swal_add_socials_login || !$swal_add_native_facebook_login || !$client_id) return;

				$redirect_uri 						 = home_url(); // URL of page/file that processes a request
				
				$params = array(
					'client_id'     => $client_id,
					'redirect_uri'  => $redirect_uri,
					'response_type' => 'code',
					'scope'         => 'email'
				);
				 
				$fb_login_url = 'https://www.facebook.com/dialog/oauth?' . urldecode( http_build_query( $params ) );
				?>
          <div class="social-networks">
            <a href="<?php echo $fb_login_url; ?>" class="fb-login-button swal-login-button" title="<?php esc_html_e('Sign in with Facebook','sw-ajax-login'); ?>">
                <div class="swal-icon-block icon-facebook">
                    <i class="fa fa-facebook"></i><span class="swal-long-login-text"><?php esc_html_e('Sign in with Facebook','sw-ajax-login'); ?></span>
                </div>
            </a>
          </div>
<?php
}



/**
 *
 * X / Twitter
 *
 */
function swal_twitter_signin_button() {

	$swal_add_socials_login       		= intval(get_option('swal_add_socials_login'));
	$swal_add_native_twitter_login      = intval(get_option('swal_add_native_twitter_login'));
	$swal_twitter_id 					= esc_attr(get_option('swal_twitter_id')); // Twitter APP Client ID

	if ( !$swal_add_socials_login || !$swal_add_native_twitter_login || !$swal_twitter_id) return;

          		$twitter_login_url = SWAL_WEBSITE_PATH.'/?action=swal-twlogin';

				?>
      <div class="social-networks">
        <a href="<?php echo $twitter_login_url; ?>" class="twitter-login-button swal-login-button" title="<?php esc_html_e('Sign in with Twitter','sw-ajax-login'); ?>">
            <div class="swal-icon-block icon-twitter">
                <i class="fa fa-twitter-x"></i><span class="swal-long-login-text"><?php esc_html_e('Sign in with X','sw-ajax-login'); ?></span>
            </div>
        </a>
      </div>
<?php
}



/**
 *
 * Google
 *
 */
function swal_google_signin_button() {

	$swal_add_socials_login       		= intval(get_option('swal_add_socials_login'));
	$swal_add_native_google_login       = intval(get_option('swal_add_native_google_login'));
	$swal_google_id          			= esc_attr(get_option('swal_google_id'));

	if ( !$swal_add_socials_login || !$swal_add_native_google_login || !$swal_google_id) return;

          		$google_redirect_url = SWAL_WEBSITE_PATH;
				$google_login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . 
						urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . 
						urlencode($google_redirect_url) . '&response_type=code&client_id=' . 
						$swal_google_id . '&access_type=online&'.
						'state=swal_redirect_to%3D'.swal_page_to_redirect();
				?>
          <div class="social-networks">
            <a href="<?php echo $google_login_url; ?>" class="google-login-button swal-login-button" title="<?php esc_html_e('Sign in with Google','sw-ajax-login'); ?>">
                <div class="swal-icon-block icon-google">
                    <i class="signin-google"></i><span class="swal-long-login-text"><?php esc_html_e('Sign in with Google','sw-ajax-login'); ?></span>
                </div>
            </a>
          </div>
<?php
}



/**
 *
 * Linkedin
 *
 */
function swal_linkedin_signin_button() {

	$swal_add_socials_login       		 = intval(get_option('swal_add_socials_login'));
	$swal_add_native_linkedin_login      = intval(get_option('swal_add_native_linkedin_login'));
	$swal_linkedin_id                  	 = esc_attr(get_option('swal_linkedin_id'));

	if ( !$swal_add_socials_login || !$swal_add_native_linkedin_login || !$swal_linkedin_id) return;

          		$linkedin_redirect_url = SWAL_WEBSITE_PATH;
          		$linkedin_login_url = 'https://www.linkedin.com/oauth/v2/authorization' . 
          				'?response_type=code&client_id='.$swal_linkedin_id.
          				'&redirect_uri='.urlencode($linkedin_redirect_url).
          				'&state='. swal_generateRandomString().
          				'&scope=r_liteprofile%20r_emailaddress';

				?>
          <div class="social-networks">
            <a href="<?php echo $linkedin_login_url; ?>" class="linkedin-login-button swal-login-button" title="<?php esc_html_e('Sign in with Linkedin','sw-ajax-login'); ?>">
                <div class="swal-icon-block icon-linkedin">
                    <i class="fa fa-linkedin"></i><span class="swal-long-login-text"><?php esc_html_e('Sign in with Linkedin','sw-ajax-login'); ?></span>
                </div>
            </a>
          </div>
<?php
}



/**
 *
 * Apple ID
 *
 */
function swal_apple_signin_button() {

	$swal_add_socials_login       	= intval(get_option('swal_add_socials_login'));
	$swal_add_native_apple_login    = intval(get_option('swal_add_native_apple_login'));
    $swal_apple_id                  = esc_attr(get_option('swal_apple_id'));

    if ( !$swal_add_socials_login || !$swal_add_native_apple_login || !$swal_apple_id) return;

          		$apple_redirect_url = SWAL_WEBSITE_PATH;
          		$apple_login_url = 'https://appleid.apple.com/auth/authorize' . 
          				'?response_type=code id_token&client_id='.$swal_apple_id.
          				'&redirect_uri='.urlencode($apple_redirect_url).
          				'&state='. swal_generateRandomString().
          				'&scope=name%20email';

				?>
          <div class="social-networks">
      		<a href="<?php echo $apple_login_url; ?>" class="apple-login-button swal-login-button" title="<?php esc_html_e('Sign in with Apple','sw-ajax-login'); ?>">
                <div class="swal-icon-block icon-apple">
                    <i class="fa fa-apple"></i><span class="swal-long-login-text"><?php esc_html_e('Sign in with Apple','sw-ajax-login'); ?></span>
                </div>
            </a>
          </div>
<?php
}



/**
 *
 * Amazon
 *
 */
function swal_amazon_signin_button() {

	$swal_add_socials_login       	= intval(get_option('swal_add_socials_login'));
	$swal_add_native_amazon_login    = intval(get_option('swal_add_native_amazon_login'));
    $swal_amazon_id                  = esc_attr(get_option('swal_amazon_id'));

    if ( !$swal_add_socials_login || !$swal_add_native_amazon_login || !$swal_amazon_id) return;

    $uri_to_redirect = str_replace(home_url(), "", swal_page_to_redirect());

    $amazon_login_url = SWAL_WEBSITE_PATH.'/?action=swal-amzlogin' .
    						'&state='. swal_generateRandomString().' '.$uri_to_redirect;
  		
				?>
          <div class="social-networks">
      		<a href id="LoginWithAmazon" class="amazon-login-button swal-login-button" title="<?php esc_html_e('Sign in with amazon','sw-ajax-login'); ?>">
                <div class="swal-icon-block icon-amazon">
                    <i class="fa fa-amazon"></i><span class="swal-long-login-text"><?php esc_html_e('Sign in with Amazon','sw-ajax-login'); ?></span>
                </div>
            </a>
         </div>
         <script type="text/javascript">
		    document.getElementById("LoginWithAmazon").onclick=function(){return options={},options.scope="profile",options.scope_data={profile:{essential:!1}},amazon.Login.authorize(options,"<?php echo $amazon_login_url ?>"),!1};
		</script>
<?php
}


/**
 *
 * Amazon needs this tag and script to be added after <body> open
 *
 */
add_action('wp_footer', 'swal_add_amazon_code_on_body_open');

function swal_add_amazon_code_on_body_open() {

	$swal_add_socials_login       	= intval(get_option('swal_add_socials_login'));
	$swal_add_native_amazon_login    = intval(get_option('swal_add_native_amazon_login'));
    $swal_amazon_id                  = esc_attr(get_option('swal_amazon_id'));

    // if none of the settings are true return
    if (!$swal_add_socials_login || !$swal_add_native_amazon_login || !$swal_amazon_id) return;

    // js is minified
	?>
	<script type="text/javascript">
		!function(){var n=document.body.firstChild,a=document.createElement("div");a.id="amazon-root",n.parentNode.insertBefore(a,n)}(),window.onAmazonLoginReady=function(){amazon.Login.setClientId("<?php echo $swal_amazon_id; ?>")},function(n){var a=n.createElement("script");a.type="text/javascript",a.async=!0,a.id="amazon-login-sdk",a.src="https://assets.loginwithamazon.com/sdk/na/login1.js",n.getElementById("amazon-root").appendChild(a)}(document);
	</script>
    <?php

}

?>