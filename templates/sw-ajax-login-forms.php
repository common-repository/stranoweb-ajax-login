<?php
/*
Template Name: Forms login, register, lost password
*/

$act = get_query_var( 'act' );


add_filter( 'the_title', 'swal_template_forms_title', 10, 2 );

function swal_template_forms_title( $title, $id = null ) {

	global $act;

	if ( get_post_type($id) == 'post') {

		switch ($act) {
		    case '':
		        $title = esc_html__('Login','sw-ajax-login');
		        break;
		    case 'register':
		    	$title = esc_html__('Register','sw-ajax-login');
		        break;
		    case 'forgot_password':
		    	$title = esc_html__('Forgot Password','sw-ajax-login');
		        break;
		    default:
		    	$title = esc_html__('Login','sw-ajax-login');
		    }
    }

    return $title;
}

//mostra l'header
get_header();

?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		
		<div id="swal-no-ajax-content" class="entry-content">
		<?php 

			swal_account_forms($act);

			?>
		</div><!-- .entry-content -->
	</main><!-- .site-main -->
</div><!-- .content-area -->


<?php
//get_sidebar();

get_footer();
?>