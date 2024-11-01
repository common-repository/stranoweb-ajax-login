<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**********************************/
/** Ajax actions                 **/
/**********************************/

//load login, register, lost password forms
add_action( 'wp_ajax_nopriv_getLoginForms', 'getLoginForms' );
add_action( 'wp_ajax_getLoginForms', 'getLoginForms' );
// Enable the user with no privileges to run swal_ajax_login() in AJAX
//add_action( 'wp_ajax_nopriv_ajaxlogin', 'swal_ajax_login' );
// Enable the user with no privileges to run ajax_forgotPassword() in AJAX
add_action( 'wp_ajax_nopriv_ajaxforgotpassword', 'ajax_forgotPassword' );

add_action( 'wp_ajax_nopriv_ajaxlogout', 'swal_ajax_logout' );
add_action( 'wp_ajax_nopriv_swal-fblogin', 'swal_ajax_facebook_login' );
add_action( 'wp_ajax_nopriv_swal-twlogin', 'swal_ajax_twitter_login' );

add_action( 'wp_ajax_nopriv_reset_pass', 'reset_pass_callback' );
add_action( 'wp_ajax_reset_pass', 'reset_pass_callback' );

// WooCommerce
add_action( 'wp_ajax_nopriv_swal-getstates', 'swal_getstates' );




// Generate frontend forms 
function getLoginForms() {


    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 

    	$act = 'ajax';

       // show login, register, forgot password forms
    	echo '<div id="sw-wrapper-ajax-login">';
		echo swal_account_forms($act);
		echo '</div>';
    }
die();

}



// WooCommerce get states from country code 
function swal_getstates() {


    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 

    	$swal_register_fields   			= get_option('swal_register_field');
    	$swal_add_register_icons    		= intval(get_option('swal_add_register_icons'));

    	if ($swal_add_register_icons) {
			    	$inputicons = ' swal-input-icons';	
			    }

	    if (is_array($swal_register_fields) && !empty($swal_register_fields)) {
	        $fields = '';

	        $args = $swal_register_fields;

	        foreach($args as $key) {

	        	// Show only state field
	        	if ($key['field_type'] == 'wc-state') {
	        		$fields .= swal_show_register_custom_fields( $key , $inputicons );
	        	}
	                
	        }
	    }

    	echo $fields;
    }
die();

}

?>