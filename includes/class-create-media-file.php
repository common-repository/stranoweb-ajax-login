<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Create the image attachment and return the new media upload id.
 *
 * @author Joshua David Nelson, josh@joshuadnelson.com
 *
 * @since 03.29.2017 updated to a class, utilizing code from Takuro Hishikawa's gist linked below.
 *
 * @see https://gist.github.com/hissy/7352933
 * 
 * @see http://codex.wordpress.org/Function_Reference/wp_insert_attachment#Example
 * 
 * @link https://joshuadnelson.com/programmatically-add-images-to-media-library/
 */
class JDN_Create_Media_File {
 
	 var $post_id;
	 var $image_url;
	 var $wp_upload_url;
	 var $attachment_id;
	 
	 /**
	 * Setup the class variables
	 */
	 public function __construct( $image_url, $post_id = 0 ) {
	 
	 // Setup class variables
	 $this->image_url = esc_url( $image_url );
	 $this->post_id = absint( $post_id );
	 $this->wp_upload_url = $this->get_wp_upload_url();
	 $this->attachment_id = $this->attachment_id ?: false;
	 
	 return $this->create_image_id();
	 
	 }
	 
	 /**
	 * Set the upload directory
	 */
	 private function get_wp_upload_url() {
	 $wp_upload_dir = wp_upload_dir();
	 return isset( $wp_upload_dir['url'] ) ? $wp_upload_dir['url'] : false;
	 }
 
	 /**
	 * Create the image and return the new media upload id.
	 *
	 * @see https://gist.github.com/hissy/7352933
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_insert_attachment#Example
	 */
	 public function create_image_id() {
	 
	 if( $this->attachment_id )
	 return $this->attachment_id;
	 
	 if( empty( $this->image_url ) || empty( $this->wp_upload_url ) )
	 return false;
	 
	 $filename = basename( $this->image_url );
	 
	 $upload_file = wp_upload_bits( $filename, null, file_get_contents( $this->image_url ) );
	 
	 if ( ! $upload_file['error'] ) {
		 $wp_filetype = wp_check_filetype( $filename, null );
		 $attachment = array(
				 'post_mime_type' => $wp_filetype['type'],
				 'post_parent' => $this->post_id,
				 'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				 'post_content' => '',
				 'post_status' => 'inherit'
		 );
		 $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $this->post_id );
	 
		 if( ! is_wp_error( $attachment_id ) ) {
		 
			 require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
			 require_once( ABSPATH . 'wp-admin/includes/media.php' );
			 
			 $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
			 wp_update_attachment_metadata( $attachment_id, $attachment_data );
			 
			 $this->attachment_id = $attachment_id;
			 
			 return $attachment_id;
		 }
	 }
	 
	 return false;
	 
	 } // end function get_image_id
}

?>