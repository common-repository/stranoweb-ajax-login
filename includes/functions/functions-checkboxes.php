<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 *
 * Checkbox StranoWeb Style template
 *
 */
if ( ! function_exists( 'stranoweb_checkbox_template' ) ) {
function stranoweb_checkbox_template($args = '') {

		$defaults = array(
	        'taxonomy'   	=> '',
	        'term_id'  		=> '',
	        'name'   		=> '',
	        'label'   		=> '',
	        'value'         => '',
	        'show_image'	=> 0,
	        'echo'			=> 1,
	    );

		$r = wp_parse_args( $args, $defaults );

		if (is_array($r['value'])) {
			$selected_term = ($r['term_id']) ? checked( in_array( $r['term_id'], $r['value'] ), true, false ) : '';
		} else {
			$selected_term = ($r['term_id']) ? checked( $r['term_id'], $r['value'], false ) : '';
		}

		$term_id	= intval($r['term_id']);
		$taxonomy 	= esc_html($r['taxonomy']);
		$name 		= esc_html($r['name']);
		$label 		= esc_html($r['label']);
		$show_image = intval($r['show_image']);

		$label 		= apply_filters('sw_checkbox_label',$label,$term_id,$taxonomy);
		$output 	= '';

		if ($show_image) {

			$image_id 	= get_term_meta ( $term_id, 'category-image-id', true );
			// Get the image
			$image 		= esc_url(wp_get_attachment_thumb_url( $image_id ));

			$output .= '<div class="cc-selector">';
				$output .= '<input id="'.$taxonomy.'_'. $term_id .'" name="'.$name.'[]" type="checkbox" value="'. $term_id .'"' .$selected_term .'>';
                $output .= '<label class="drinkcard-cc" for="'.$taxonomy.'_'. $term_id.'" ';
                $output .= 'style="background: url('.$image.') center center; background-size: cover;">'.$label.'</label>';
            $output .= '</div>';
		} else {

			$wrapperclass = 'checkboxStyle';

			$wrapperclass 		= apply_filters('sw_checkbox_wrapper',$wrapperclass,$term_id,$taxonomy);

			$output .= '<div class="'.$wrapperclass.'">';
				$output .= '<input id="'.$taxonomy.'_'. $term_id .'" name="'.$name.'[]" type="checkbox" value="'. $term_id .'"' .$selected_term .'>';
				$output .= '<label for="'.$taxonomy.'_'. $term_id .'"></label>';
			$output .= '</div>';
			$output .= '<label for="'.$taxonomy.'_'. $term_id .'" class="checkboxStyle-label">' . $label . '</label>';
		}

		if ( $r['echo'] ) {
	        echo $output;
	    }
	    return $output;
	}
}

/**
 *
 * Checkbox StranoWeb Style template
 *
 */
if ( ! function_exists( 'stranoweb_checkbox_simple' ) ) {
function stranoweb_checkbox_simple($args = '') {

		$defaults = array(
	        'taxonomy'   	=> '',
	        'term_id'  		=> '',
	        'name'   		=> '',
	        'label'   		=> '',
	        'value'         => '',
	        'show_image'	=> 0,
	        'echo'			=> 1,
	    );

		$r = wp_parse_args( $args, $defaults );

		if (is_array($r['value'])) {
			$selected_term = ($r['term_id']) ? checked( in_array( $r['term_id'], $r['value'] ), true, false ) : '';
		} else {
			$selected_term = ($r['term_id']) ? checked( $r['term_id'], $r['value'], false ) : '';
		}

		$term_id	= intval($r['term_id']);
		$taxonomy 	= esc_html($r['taxonomy']);
		$name 		= esc_html($r['name']);
		$label 		= esc_html($r['label']);
		$show_image = intval($r['show_image']);

		$label 		= apply_filters('sw_checkbox_label',$label,$term_id,$taxonomy);
		
		$output = '<label><input id="'.$taxonomy.'_'. $term_id .'" name="'.$name.'[]" type="checkbox" value="'. $term_id .'"' .$selected_term .'> ' . $label;
		$output .= '</label>';


		if ( $r['echo'] ) {
	        echo $output;
	    }
	    return $output;
	}
}

/**
 *
 * Checkbox iOS Style
 *
 * @wrapper 	= (the css class for the wrapper div)
 * @label 		= (the label text)
 * @label_class = (css label class)
 * @id 			= (id attribute)
 * @name 		= (name attribute)
 * @post_id 	= (post id)
 * @input_value = (input tag value)
 * @value  		= (the value to match)
 * @echo 		= 1 echo, 0 return as a function
 *
 */
if ( ! function_exists( 'swal_checkbox_ios_style' ) ) { 
	function swal_checkbox_ios_style( $args ='' ) {

		$defaults = array(
	        'wrapper'   	=> 'checkbox_ios_style_wrapper',
	        'label'  		=> '',
	        'label_class'   => '',
	        'class'			=> '',
	        'id'            => '',
	        'name'        	=> '',
	        'post_id'       => '',
	        'title'			=> '',
	        'input_value'   => '',
	        'value'         => '',
	        'echo'			=> 1,
	        'data'			=> array(),
	    );

		$r = wp_parse_args( $args, $defaults );

		if (is_array($r['value'])) {
			$selected_term = ($r['value']) ? checked( in_array( $r['input_value'],$r['value'] ), true, false ) : '';
		} else {
			$selected_term = ($r['value']) ? checked( $r['value'], $r['input_value'], false ) : '';
		}

		$output = ($r['wrapper']) ? '<div class="'.esc_html($r['wrapper']).'">' : '';
		
		$title = $r['title'] ? ' title="'.esc_html($r['title']).'"' : '';

		$data = '';
		foreach ($r['data'] as $key => $value) {
			$data .= ' data-' . esc_html($key) .'="'.esc_html($value).'"';
		}

        	$output .= '<input'.$data.' id="'.esc_html($r['id']).'" name="'.esc_html($r['name']).'" type="checkbox" class="onoffswitch-checkbox '.esc_html($r['class']).'" value="'.esc_html($r['input_value']).'"'. $selected_term.'/>
                <label class="onoffswitch-label" for="'.esc_html($r['id']).'"'.$title.'>
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>';
            if ($r['label']) {

				$r['label_class'] = ($r['label_class']) ? ' class="'.esc_html($r['label_class']).'"' : '';

				$output .= '<label for="'.esc_html($r['id']).'"'.$r['label_class'].'>'. $r['label'] .'</label>';
			}
	    $output .= ($r['wrapper']) ? '</div>' : '';

        if ( $r['echo'] ) {
	        echo $output;
	    }
	    return $output;
	}
}


?>