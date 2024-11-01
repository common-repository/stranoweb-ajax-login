<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 *
 * This function create modal popup inline stylesheet 
 *
 */

add_action( 'wp_head', 'swal_forms_style' );

function swal_forms_style() {

	//get options
	$swal_popup_layout_style		= intval(get_option('swal_popup_layout_style',SWAL_POPUP_LAYOUT_STYLE));
	$swal_simple_layout_width		= intval(get_option('swal_simple_layout_width',SWAL_SIMPLE_LAYOUT_WIDTH));
	$swal_double_layout_width		= intval(get_option('swal_double_layout_width',SWAL_DOUBLE_LAYOUT_WIDTH));
	$swal_popup_border_radius       = get_option('swal_popup_border_radius') ? intval(get_option('swal_popup_border_radius')) : SWAL_POPUP_BORDER_RADIUS;
	$swal_form_width                = intval(get_option('swal_form_width',SWAL_FORM_WIDTH));
	$swal_customize_popup			= intval(get_option('swal_customize_popup'));
	$swal_popup_color				= sanitize_hex_color(get_option('swal_popup_color',SWAL_POPUP_COLOR));
	$swal_popup_text_color          = get_option('swal_popup_text_color') ? sanitize_hex_color(get_option('swal_popup_text_color')) : SWAL_POPUP_TEXT_COLOR;
	$swal_popup_secondary_text_color     = sanitize_hex_color(get_option('swal_popup_secondary_text_color',SWAL_POPUP_SECONDARY_TEXT_COLOR));
	$swal_popup_image_text_color    = sanitize_hex_color(get_option('swal_popup_image_text_color',SWAL_POPUP_IMAGE_TEXT_COLOR));
	$swal_main_overlay_color    	= sanitize_hex_color(get_option('swal_main_overlay_color',SWAL_MAIN_OVERLAY_COLOR));
	$swal_main_overlay_opacity  	= intval(get_option('swal_main_overlay_opacity',SWAL_MAIN_OVERLAY_OPACITY));
	$swal_user_thumbnail_width      = intval(get_option('swal_user_thumbnail_width',SWAL_USER_THUMBNAIL_WIDTH));
	//convert to rgba
	$rgba_main 						= hex2rgba($swal_main_overlay_color, ($swal_main_overlay_opacity/100));

	$swal_overlay_color         	= get_option('swal_overlay_color') ? sanitize_hex_color(get_option('swal_overlay_color')) : SWAL_OVERLAY_COLOR;
	$swal_overlay_opacity       	= intval(get_option('swal_overlay_opacity',SWAL_OVERLAY_OPACITY));
	//convert to rgba
	$rgba 							= hex2rgba($swal_overlay_color, ($swal_overlay_opacity/100));

	$swal_link_color                = get_option('swal_link_color') ? sanitize_hex_color(get_option('swal_link_color')) : SWAL_LINK_COLOR;
	$swal_buttons_color         	= sanitize_hex_color(get_option('swal_buttons_color',SWAL_LINK_COLOR));
	$swal_button_style              = intval(get_option('swal_button_style'));
	$swal_input_fields_style        = intval(get_option('swal_input_fields_style'));
	$swal_button_border_radius      = get_option('swal_button_border_radius') ? intval(get_option('swal_button_border_radius')) : 3;
	$swal_input_border_radius       = get_option('swal_input_border_radius') ? intval(get_option('swal_input_border_radius')) : 3;
	$swal_button_height             = get_option('swal_button_height') ? intval(get_option('swal_button_height')) : SWAL_BUTTON_HEIGHT;
    $swal_input_height              = get_option('swal_input_height') ? intval(get_option('swal_input_height')) : SWAL_INPUT_HEIGHT;
	$swal_form_text_color 			= $swal_popup_image_text_color;
	$swal_add_overlay_logout       	= intval(get_option('swal_add_overlay_logout'));
	$swal_logout_overlay_color     	= sanitize_hex_color(get_option('swal_logout_overlay_color',SWAL_LOGOUT_OVERLAY_COLOR));
	$swal_logout_overlay_opacity   	= intval(get_option('swal_logout_overlay_opacity',SWAL_OVERLAY_OPACITY));
	//convert to rgba
	$rgba_logout 				 	= hex2rgba($swal_logout_overlay_color, ($swal_logout_overlay_opacity/100));
	$swal_logout_text_color 		= calculateTextColor($swal_logout_overlay_color,'#333333','#ffffff');
	$swal_additional_css       		= swal_notify_import_rules_stripped(get_option('swal_additional_css'));
	$swal_input_text_color          = get_option('swal_input_text_color') ? sanitize_hex_color(get_option('swal_input_text_color')) : SWAL_INPUT_TEXT_COLOR;
    $swal_input_background_color    = get_option('swal_input_background_color') ? sanitize_hex_color(get_option('swal_input_background_color')) : SWAL_INPUT_BACKGROUND_COLOR;
    $swal_input_border_color        = get_option('swal_input_border_color') ? sanitize_hex_color(get_option('swal_input_border_color')) : SWAL_INPUT_BORDER_COLOR;
    $swal_input_focus_color         = get_option('swal_input_focus_color') ? sanitize_hex_color(get_option('swal_input_focus_color')) : SWAL_INPUT_FOCUS_COLOR;

	//loader
	$swal_loader_background_color        = get_option('swal_loader_background_color') ? sanitize_hex_color(get_option('swal_loader_background_color')) : SWAL_LOADER_BACKGROUND_COLOR;
	$swal_loader_background_color 	= hex2rgba($swal_loader_background_color, 0.7);
    $swal_loader_text_color         = sanitize_hex_color(get_option('swal_loader_text_color',SWAL_LOADER_TEXT_COLOR));

    // tabs
    $swal_tabs_link_style                = intval(get_option('swal_tabs_link_style'));
    $swal_tab_text_color                 = get_option('swal_tab_text_color') ? sanitize_hex_color(get_option('swal_tab_text_color')) : SWAL_LINK_COLOR;
    $swal_tab_active_text_color          = get_option('swal_tab_active_text_color') ? sanitize_hex_color(get_option('swal_tab_active_text_color')) : SWAL_POPUP_TEXT_COLOR;
    $swal_tab_block_bkg_color            = get_option('swal_tab_block_bkg_color') ? sanitize_hex_color(get_option('swal_tab_block_bkg_color')) : '#222222';
    $swal_tab_block_text_color           = get_option('swal_tab_block_text_color') ? sanitize_hex_color(get_option('swal_tab_block_text_color')) : '#555555';


	$css = '<style type="text/css" media="screen">';
	$css .= '.swal-thumbnail {
			    width: '.$swal_user_thumbnail_width.'px;
			    height: '.$swal_user_thumbnail_width.'px;
			}';
	$css .= '.ajax-auth {
    		max-width: '.$swal_form_width.'px;
    		}';
	if ($swal_customize_popup) { //if popup has a color setting then set css
		if ($swal_popup_color || $swal_popup_border_radius) { //popup color
	$css .= '#popup-wrapper-ajax-auth {
			background-color: '.$swal_popup_color.';
			color: '.$swal_popup_text_color.';
			border-radius: '.$swal_popup_border_radius.'px;
			}
			#wrapper-forgot_password, #wrapper-login, #wrapper-register {
			  border-radius: '.$swal_popup_border_radius.'px;
			}';

		}
		if ($swal_popup_secondary_text_color) { //popup secondary text color
	$css .= '.ajax-auth a.text-link,
			.ajax-auth h4,
			.swal-instruction-text {
			color: '.$swal_popup_secondary_text_color.';
			}
			.ajax-auth h4:after {
			    background-color: '.$swal_popup_secondary_text_color.';
			}
			.ajax-auth h4:before {
			    background-color: '.$swal_popup_secondary_text_color.';
			}';
		}
		if ($swal_main_overlay_color) { //overlay color
	$css .= '.login_overlay {
			background-color: '.$rgba_main.';
			}';
		}
		if ($swal_link_color) { //link color
	$css .= '.ajax-auth h3 a {
			color: '.$swal_link_color.';
			}';
		}
		if ($swal_buttons_color) { //buttons color
			$css .= '.ajax-auth input.submit_button,
				  .ajax-auth .submit_button {';
				  if (!$swal_button_style) {
				  	$css .= 'background: -moz-linear-gradient(top, '.$swal_buttons_color.', '.sw_adjustBrightness($swal_buttons_color, -10).');
					  	background: linear-gradient(to bottom, '.$swal_buttons_color.', '.sw_adjustBrightness($swal_buttons_color, -10).');
					  	border-color: '.sw_adjustBrightness($swal_buttons_color, -30).';
						box-shadow: 0 1px 0 '.sw_adjustBrightness($swal_buttons_color, 30).' inset;';
				  } else {
				  	$css .= 'background: -moz-linear-gradient(top, '.$swal_buttons_color.', '.$swal_buttons_color.');
					  	background: linear-gradient(to bottom, '.$swal_buttons_color.', '.$swal_buttons_color.');
					  	border-color: '.sw_adjustBrightness($swal_buttons_color, -30).';
						box-shadow: 0 0 0 '.sw_adjustBrightness($swal_buttons_color, 30).' inset;
						text-shadow: 0 0 0 rgba(0,0,0,0);
						border: 0;';
				  }
					$css .= 'background-color: '.$swal_buttons_color.';
							border-radius: '.$swal_button_border_radius.'px;
							height: '.$swal_button_height.'px;
							line-height: '.($swal_button_height-2).'px;
					}
					.wizard > .actions a,
					.wizard > .actions a:hover {
						border-radius: '.$swal_button_border_radius.'px;
					}';
		}
	}

	// input fields
	
		$css .= '.ajax-auth input[type="date"], 
				.ajax-auth input[type="email"], 
				.ajax-auth input[type="number"], 
				.ajax-auth input[type="password"], 
				.ajax-auth input[type="tel"], 
				.ajax-auth input[type="text"], 
				.ajax-auth select {
					border-radius: '.$swal_input_border_radius.'px;
					height: '.$swal_input_height .'px !important;
					line-height: '.$swal_input_height .'px !important;';
				if ($swal_input_fields_style) {
					$css .= 'box-shadow: none;';
				}
			$css .= 'border-color: '.$swal_input_border_color.';
					 color: '.$swal_input_text_color.' !important;
					 background-color: '.$swal_input_background_color.' !important;
				}';
		$css .= '.ajax-auth input:focus, 
				.ajax-auth select:focus {
					border-color: '.$swal_input_focus_color.' !important;
				}';

	// input fields icons

		$css .= '.swal-input-icons i {
					height: '.$swal_input_height.'px;
					line-height: '.($swal_input_height-2).'px;
		}
		.field-icon {
			height: '.$swal_input_height.'px;
			line-height: '.($swal_input_height-2).'px;
		}';

	$css .= '.inner-text-ajax-forms {
			color: '.$swal_form_text_color.';
			}';
	if (($swal_customize_popup && $swal_popup_color) || $swal_popup_layout_style == 4 || $swal_popup_layout_style == 5 || $swal_popup_layout_style == 7) { //if popup has a color setting then set css
		if ($swal_popup_layout_style == 4 || $swal_popup_layout_style == 5 || $swal_popup_layout_style == 7) {
			$swal_popup_text_color = $swal_form_text_color;
		}
	$css .= '.ajax-auth h1,
		.ajax-auth h3 {
			color: '.$swal_popup_text_color.';
			}';
	}

	// Login Overlay

	$css .= '#popup-wrapper-ajax-auth .sw-ajax-login-overlay-wrapper,
			#popup-wrapper-ajax-auth .sw-ajax-login-overlay,
			.sw-ajax-login-overlay,
			.sw-ajax-login-overlay-wrapper {
			    background: '.$rgba.';
			}';

	// Logout Overlay

	if ($swal_add_overlay_logout) { 
	$css .= '#popup-wrapper-ajax-auth .sw-ajax-logout-overlay-wrapper,
			#popup-wrapper-ajax-auth .sw-ajax-logout-overlay,
			.sw-ajax-logout-overlay {
			    background: '.$rgba_logout.';
			}';
	$css .= '#popup-wrapper-ajax-auth .sw-ajax-logout-text-contrast,
			#swal-no-ajax-content .sw-ajax-logout-text-contrast {
			    color: '.$swal_logout_text_color.' !important;
			}';
		}
	if (!$swal_popup_layout_style || $swal_popup_layout_style == 1 || $swal_popup_layout_style == 6 || $swal_popup_layout_style == 7) {
		$css .= '#popup-wrapper-ajax-auth {';
			$css .= 'max-width: '.$swal_simple_layout_width.'px;
			    margin-left: -'.($swal_simple_layout_width/2).'px;
			}
			.inner-form-ajax-forms {
			    width: 100%;
			    float: none;
			}
			#swal-no-ajax-content .inner-form-ajax-forms {
			    width: 100%;
			    float: none;
			}
			@media only screen and (max-width:480px) {
			    #popup-wrapper-ajax-auth {
			        left: 0;
			        margin-left: 0;
			        }
			}
			@media only screen and (min-width:481px) and (max-width:960px) {
			    #popup-wrapper-ajax-auth {
			        left: 50%;
			        }
			}';
	} else if ($swal_popup_layout_style > 1 && $swal_popup_layout_style < 6) {
		$css .= '#popup-wrapper-ajax-auth {';
			$css .= 'max-width: '.$swal_double_layout_width.'px;
			    margin-left: -'.($swal_double_layout_width/2).'px;
			}
			@media only screen and (max-width:'.$swal_double_layout_width.'px) {
			    #popup-wrapper-ajax-auth {
					height: auto;
			        min-height: 80px;
			        margin: 0;
			        margin-top: 60px;
			        margin-bottom: 40px;
			        left: 0;
			        }
			}';
		}
	// Tabs
	if ($swal_popup_layout_style == 6 || $swal_popup_layout_style == 7) {
		$css .= '.swal_form_tabs ul li a {
					color: '. $swal_tab_text_color.';
			}';
		$css .= '.swal_form_tabs.swal_form_tabs_blocks ul li a {
					background-color: '. $swal_tab_block_bkg_color.';
					color: '. $swal_tab_block_text_color.';
			}';
	}
    if ($swal_additional_css) {
		$css .= wp_filter_nohtml_kses( $swal_additional_css );
	}
	if ($swal_loader_background_color || $swal_loader_text_color) {
		$css .= '.swal-toast {
			  color: '.$swal_loader_text_color.';
			  background: '.$swal_loader_background_color.';
			  }';
			}

	$css = apply_filters('swal_inline_css', $css);

	$css .= '</style>';

	// Minify the CSS
	$css = swal_minimizeCSSsimple($css);

	echo $css;
}

?>