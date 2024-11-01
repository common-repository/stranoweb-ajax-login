<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

function swal_display_premium_features() {

	$tab_premium_title = esc_html__('Premium Features','sw-ajax-login');
    if (SWAL_P)
        $tab_premium_title = esc_html__('Features','sw-ajax-login');

	?>
	<div class="clear"></div>
	<h2 class="swal-title center"><?php echo $tab_premium_title; ?></h2>
        <div class="gc-np background-pair">
            <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
                <h3><?php esc_html_e('Fully customizable responsive popups', 'sw-ajax-login') ?></h3>
                <p><strong><?php esc_html_e('Stranoweb Ajax Login Pro', 'sw-ajax-login') ?></strong> <?php esc_html_e('replaces the default WordPress login, register and lost password forms with beautiful ajax modal popups and comes with a lot of amazing features.
It’s fully customizable, responsive, includes several social logins and allows to disable new user registration or restrict WordPress admin dashboard to certain user rules.', 'sw-ajax-login') ?></p>
            </div>
            <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
            	<img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-responsive-layouts.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>

        <div class="gc-np--rev">
            <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
                <h3><?php esc_html_e('Several different layout models', 'sw-ajax-login') ?></h3>
                <p><?php esc_html_e('You can customize your forms as you like adding images, changing background, text and buttons color, and if you\'re one who likes to get your hands dirty you can add your custom css.', 'sw-ajax-login') ?></p>
            </div>
            <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
            	<img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-banner-2.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>

        <div class="gc-np background-pair clear">
            <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
            	<h3><?php esc_html_e('Improve customer acquisition with social logins', 'sw-ajax-login') ?></h3>
                <p><?php esc_html_e('Social Login makes it easier for visitors to your site to become customers by using their existing social media credentials to register on your site. ', 'sw-ajax-login') ?>
                	<?php esc_html_e('With Stranoweb Ajax Login, you can easily add some of the most popular social logins to login form, even on WooCommerce login form.', 'sw-ajax-login') ?></p>
            </div>
            <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
            	<img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-social-logins.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>

        <div class="gc-np--rev clear">
            <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
                <h3><?php esc_html_e('User verification', 'sw-ajax-login') ?></h3>
                <p><?php esc_html_e('To reduce the number of spam accounts you can add the user verification to your website. It can be by user approval by administrators or email verification.', 'sw-ajax-login') ?><br/>
                <?php esc_html_e('No more spam accounts!', 'sw-ajax-login') ?></p>
            </div>
            <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
            	<img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-user-verification.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>

        <div class="gc-np background-pair clear">
            <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
            	<h3><?php esc_html_e('Create a custom submenu for logged in users', 'sw-ajax-login') ?></h3>
                <p><?php esc_html_e('With StranoWeb Ajax Login you can add your custom submenu to the user menu item, very usefull when you want to group all the user related links in one place.', 'sw-ajax-login') ?>
                </p>
            </div>
            <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
            	<img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-submenu.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>

        <div class="gc-np--rev clear">
            <div class="sw-grid span-1-2 tablet-1 mobile-1 inner-text vertical-middle">
                <h3><?php esc_html_e('Restrict access to WordPress admin dashboard', 'sw-ajax-login') ?></h3>
                <p><?php esc_html_e('On some websites you may want that only users with specific roles can access to the WordPress Admin Dashboard; with Stranoweb Ajax Login WordPress Plugin you can easily manage who can access to the dashboard.', 'sw-ajax-login') ?></p>
            </div>
            <div class="sw-grid span-1-2 tablet-1 mobile-1 vertical-middle">
                <img src="<?php echo SWAL_PLUGIN_DOCS_IMAGES . '/swal-no-dashboard.jpg'; ?>" alt="" class="center span-1"/>
            </div>
        </div>

        <?php

        // Display plugin features table (only on free version)
        swal_display_premium_table();
        
}


function swal_display_premium_table() {

    if (!SWAL_P) {
                    ?>
        <div class="gc-np background-pair clear">
            <div class="sw-grid span-1 tablet-1 mobile-1 inner-text vertical-middle">
                <h3 class="center"><?php esc_html_e('Features', 'sw-ajax-login') ?></h3>

                <table class="swal-comparison-table">
                    <thead>
                        <tr>
                            <td></td>
                            <th class="free-cell center"><?php esc_html_e('Free', 'sw-ajax-login') ?></th>
                            <th class="premium-cell center"><?php esc_html_e('Premium', 'sw-ajax-login') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p><?php esc_html_e('Ajax modal popup for login, registration, forgot password and logout','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Form fields validation','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Popup layouts','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-big">2</span></td>
                            <td><span class="swal-feature-big">8</span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('GDPR compliant','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Customizable CSS','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Social logins','sw-ajax-login'); ?></p></th>
                            <td><?php esc_html_e('Twitter', 'sw-ajax-login') ?></td>
                            <td><?php esc_html_e('Facebook, Twitter, Google, Linkedin, Amazon', 'sw-ajax-login') ?></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Social icons themes','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-big">2</span></td>
                            <td><span class="swal-feature-big">5</span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Customizable redirects and permalink','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('reCAPTCHA v2 and reCAPTCHA v3 for new user registration','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Shortcode support on text editors','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Styled emails','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Disallow new user registration','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Drag & Drop Register form builder','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Emails editor','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Custom overlay','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Logo upload','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Custom login menu item text','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Customizable texts','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Option to redirect not logged in users to login form','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('WordPress admin dashboard restriction','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Show/Hide password icon','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Disable automatic login for new user after registration','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Automatic opening of the popup','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('Automatic opening of the popup only on certain pages','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('User activation by administrators','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                        <tr>
                            <th><p><?php esc_html_e('User activation by email verification','sw-ajax-login'); ?></p></th>
                            <td><span class="swal-feature-no"><?php esc_html_e('No', 'sw-ajax-login') ?></span></td>
                            <td><span class="swal-feature-yes"><?php esc_html_e('Yes', 'sw-ajax-login') ?></span></td>
                        </tr>
                    </tbody>
                </table>

                <div>
                    <div class="swal-premium-button">
                        <a href="<?php echo esc_html(SWAL_PREMIUM_BUY_LINK) ?>"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> <?php esc_html_e('Upgrade to the Premium version', 'sw-ajax-login') ?></a>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

}

?>