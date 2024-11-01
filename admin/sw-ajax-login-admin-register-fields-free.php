<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}






/**
 * 
 * Registration fields setting fields
 * 
 */
function swal_admin_register_fields_settings() {

  // Get the options array
    $swal_register_fields                  = get_option('swal_register_field');

    //echo json_encode($swal_register_fields, JSON_PRETTY_PRINT); // for debug purpose
 // End Register extra fields window

    ?>
<div class="wrap swal-fields-editor-wrap">

    <?php
    /**
     *
     * This hook is for the header bar
     *
     */
    do_action('swal_admin_header_bar');
    ?>

     <!-- Register extra fields window -->
        <div class="swal-wrap-inner">

            <h2 class="swal-title center"><?php esc_html_e('Registration form fields','sw-ajax-login') ?></h2>
            <div class="gc-np background-pair">
                <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
                    <h3><?php esc_html_e('Build your registration form', 'sw-ajax-login') ?></h3>
                    <p><strong><?php esc_html_e('Stranoweb Ajax Login Premium', 'sw-ajax-login') ?></strong> <?php esc_html_e('provides a drag & drop system to easily build your register form.', 'sw-ajax-login') ?>
                        </p>
                    <ul class="swal-features-list">
                        <li><?php esc_html_e('Different type of fields', 'sw-ajax-login') ?></li>
                        <li><?php esc_html_e('Data validation', 'sw-ajax-login') ?></li>
                        <li><?php esc_html_e('Minimum user age control', 'sw-ajax-login') ?></li>
                        <li><?php esc_html_e('WooCommerce fields integration', 'sw-ajax-login') ?></li>
                    </ul>
                </div>
                <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
                    <div class="swal-image-container">
                        <img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-register-form-builder.jpg'; ?>" alt="" class="center span-1"/>
                    </div>
                </div>
                <div class="clear">
                    <div class="swal-premium-button">
                        <a href="<?php echo esc_html(SWAL_PREMIUM_BUY_LINK) ?>"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> <?php esc_html_e('Upgrade to the Premium version', 'sw-ajax-login') ?></a>
                    </div>
                </div>
            </div>
            
       
        </div>
</div>
  
<?php
}

?>