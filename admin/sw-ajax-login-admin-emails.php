<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 *
 * Adds the tab to manage the emails
 *
 */
add_filter('swal_admin_tabs_items', 'swal_add_emails_tab');

function swal_add_emails_tab( $menu_item) {

    $menu_item[] = array(
          'title'  => esc_html__('Emails','sw-ajax-login'),
          'priority'   => 41,
          'callback'   => 'swal_admin_emails_settings',
          ); 

  return $menu_item;
}




/**
 * 
 * Email design PREMIUM description
 * 
 */
function swal_admin_emails_settings() {

    ?>
    <!-- Emails Window -->
    <h2 class="swal-title center"><?php esc_html_e('Emails','sw-ajax-login') ?></h2>
    <div class="gc-np background-pair">
        <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
            <h3><?php esc_html_e('Design your emails', 'sw-ajax-login') ?></h3>
            <p><?php esc_html_e('With', 'sw-ajax-login') ?> <strong><?php esc_html_e('Stranoweb Ajax Login Premium', 'sw-ajax-login') ?></strong> <?php esc_html_e('You can easily customize the email notifications received by users on registration and password recovery.', 'sw-ajax-login') ?>
                <br/><?php esc_html_e('They will perfectly match your website design!', 'sw-ajax-login') ?></p>
            <ul class="swal-features-list">
                <li><?php esc_html_e('Customize email colors and layout', 'sw-ajax-login') ?></li>
                <li><?php esc_html_e('Emails content editor', 'sw-ajax-login') ?></li>
                <li><?php esc_html_e('Add your logo', 'sw-ajax-login') ?></li>
                <li><?php esc_html_e('Add images', 'sw-ajax-login') ?></li>
                <li><?php esc_html_e('Footer content editor', 'sw-ajax-login') ?></li>
            </ul>
        </div>
        <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
            <div class="swal-image-container">
                <img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-templates-email.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>
        <div>
            <div class="swal-premium-button">
                <a href="<?php echo esc_html(SWAL_PREMIUM_BUY_LINK) ?>"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> <?php esc_html_e('Upgrade to the Premium version', 'sw-ajax-login') ?></a>
            </div>
        </div>
    </div>

    <?php
}


?>