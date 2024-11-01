<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


add_action('swal_admin_header_bar', 'swal_admin_settings_header');


/**
 * 
 * Admin setting sticky header
 * 
 */
function swal_admin_settings_header() {



    $output = '<div id="swal-header-editor">
                    <div class="gc">
                        <div class="sw-grid span-1-3 tablet-1-2 mobile-1-2">
                             <img src="'. SWAL_PLUGIN_IMAGES .'/swal-logo.png' .'" alt="'.esc_html__('StranoWeb Ajax Login - WordPress Plugin','sw-ajax-login') .'"/>
                        </div>
                        <div class="sw-grid span-2-3 tablet-1-2 mobile-1-2">
                            <div class="right">';
                                //<button class="button swal-button-secondary sw-open-register" value="'.esc_html__('Preview','sw-ajax-login') .'">' . esc_html__('Preview','sw-ajax-login') .'</button>
                    $output .= '<button class="button swal-button-primary swal-add-loader swal-submit-form disabled" value="'.esc_html__('Save Changes','sw-ajax-login') .'">'.esc_html__('Save Changes','sw-ajax-login') .'</button>
                            </div>
                        </div>
                    </div>
                </div>';

    echo $output;
}


?>