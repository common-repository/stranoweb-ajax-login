<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}




/**
 *
 * Add Social login settings to admin options
 *
 */
add_action( 'admin_init', 'swal_register_socials_settings' );

 

/**
 *
 * Adds register Social login settings
 *
 */
function swal_register_socials_settings() {

  // Socials
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_socials_login', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_socials_login_to_register', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_social_icons_position', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_use_social_profile_picture', 'intval' );

    // Get the social logins list
    $swal_socials = Layers_SwAjaxLogin_free::swal_get_social_logins();

    foreach ($swal_socials as $social) {
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_native_'.$social.'_login', 'intval' );
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_'.$social.'_id', 'sanitize_text_field' );
        register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_'.$social.'_secret_key', 'sanitize_text_field' );
    }
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_add_social_login_to_woocommerce', 'intval' );
    register_setting( SWAL_PLUGIN_SETTINGS_PAGE . '-group', 'swal_social_icons_theme', 'intval' );
  // End Socials

}


/**
 * 
 * Social login setting fields
 * 
 */
function swal_admin_socials_settings() {


  // Socials
    $swal_add_socials_login              = intval(get_option('swal_add_socials_login',SWAL_ADD_SOCIALS_LOGIN));
    $swal_add_socials_login_to_register  = intval(get_option('swal_add_socials_login_to_register'));
    $swal_social_icons_position          = intval(get_option('swal_social_icons_position',SWAL_SOCIAL_ICONS_POSITION));
    $swal_use_social_profile_picture     = intval(get_option('swal_use_social_profile_picture'));
    $swal_add_native_twitter_login       = intval(get_option('swal_add_native_twitter_login'));
    $swal_twitter_id                     = esc_attr(get_option('swal_twitter_id'));
    $swal_twitter_secret_key             = esc_attr(get_option('swal_twitter_secret_key'));
    $swal_add_social_login_to_woocommerce       = intval(get_option('swal_add_social_login_to_woocommerce'));
    $swal_social_icons_theme             = intval(get_option('swal_social_icons_theme',SWAL_SOCIAL_ICONS_THEME));
  // End Socials

    $url_to_paste = '<input value="'.home_url().'" readonly="readonly" type="text" class="settings-select">';

    $swal_how_to = array(
                        'facebook' => '<ol class="description">
                                        <li>'.esc_html__('Login into your Fb account and go to ','sw-ajax-login').'<a href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a></li>
                                        <li>'.esc_html__("Click on 'Add a New App' button. A popup will open. Enter the name of the app as you wish and your email, then click on 'Create New App ID' button.",'sw-ajax-login').'</li>
                                        <li>'.sprintf(esc_html__("You'll be redirected to the next page 'Select a product', choose %s.", 'sw-ajax-login'), '<strong>facebook login</strong>' ).'</li>
                                        <li>'.sprintf(esc_html__("You have now to select the App platform, please click on %s.", 'sw-ajax-login'), '<strong>Web</strong>' ).'</li>
                                        <li>'.sprintf(esc_html__("Enter this website URL (see point 9 below) and click %s, ignore the following steps.", 'sw-ajax-login'), '<strong>Save</strong>' ).'</li>
                                        <li>'.sprintf(esc_html__("Click on %s on the left column menu.", 'sw-ajax-login'), '<strong>Settings</strong>' ).'</li>
                                        <li>'.esc_html__("In the landing page you will find the API version, App ID, App Secret. Copy the App ID and App Secret Key (click on 'Show' button) and enter them in our plugin settings above.", 'sw-ajax-login').'</li>
                                        <li><strong>'.esc_html__("Important: ",'sw-ajax-login').'</strong>'.esc_html__("After that please go to 'App Review' link just below the alert link on the left column, there you will find an option to make the app public and select YES. This is very important otherwise your app will not work for all users.", 'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Site URL",'sw-ajax-login').': '.$url_to_paste.'</li>
                                    </ol>',
                        'twitter' => '<ol class="description">
                                        <li>'.esc_html__('Login into your X account and go to ','sw-ajax-login').'<a href="https://developer.twitter.com/en/portal/projects-and-apps" target="_blank">https://developer.twitter.com/en/portal/projects-and-apps</a></li>
                                        <li>'.esc_html__("Click on 'Add a New App' button. A popup will open. Enter the name of the app as you wish and your email, then click on 'Create New App ID' button.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Enter your desired Application Name, Description and your website address (points 4 & 5) making sure to enter the full address including the http:// or https://.", 'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Site URL",'sw-ajax-login').': '.$url_to_paste.'</li>
                                        <li>'.esc_html__("Callback URL",'sw-ajax-login').': '.$url_to_paste.'</li> 
                                        <li>'.esc_html__("After creating your X Application click on the tab that says 'Keys and Access Tokens' and get Consumer key(API Key) and Consumer secret(API secret).", 'sw-ajax-login').'</li>
                                        <li><strong>'.esc_html__("Important: ",'sw-ajax-login').'</strong>'.esc_html__("To get the user's email address please go to app's 'permission' tab and in additional Permissions there you will find a checkbox to request for user email address. Please enable it. To enable it you need to enter privacy policy url and terms of service url.", 'sw-ajax-login').'</li>  
                                        <li><strong>'.esc_html__("Important 2: ",'sw-ajax-login').'</strong>'.esc_html__("Go to app's 'Settings' tab and make sure that the checkbox 'Enable Callback Locking' is unchecked.", 'sw-ajax-login').'</li>
                                    </ol>',
                        'google' => '<ol class="description">
                                        <li>'.sprintf(esc_html__('Login to your %s','sw-ajax-login'),'<a href="https://console.developers.google.com/" target="_blank">Google API console</a>').'</li>
                                        <li>'.esc_html__("From the project drop-down, select an existing project , or create a new one by selecting 'Create a new project'. ",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("In the sidebar under 'APIs & Services', select 'Credentials', then select the OAuth consent screen tab.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Choose an Email Address, specify a Product Name. In the Authorized domains, specify the domains which will be allowed to authenticate using OAuth and press Save.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("In the 'Credentials' tab, select the 'New credentials' drop-down list, and choose 'OAuth client ID'.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Under Application type, select 'Web application' and In the 'Authorized JavaScript origins' and 'Authorized redirect URI' fields, enter the origin for your app as follows:",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Authorized JavaScript origins",'sw-ajax-login').': '.$url_to_paste.'</li>
                                        <li>'.esc_html__("Authorized redirect URI",'sw-ajax-login').': '.$url_to_paste.'</li>
                                        <li>'.esc_html__("Press the 'Create' button.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("From the resulting 'OAuth client' dialog box, copy the 'Client ID' and 'Client secret'.",'sw-ajax-login').'</li>
                                    </ol>',
                        'linkedin' => '<ol class="description">
                                        <li>'.esc_html__('Login into your Linkedin account and go to ','sw-ajax-login').'<a href="https://www.linkedin.com/secure/developer?newapp=" target="_blank">https://www.linkedin.com/secure/developer?newapp=</a></li>
                                        <li>'.esc_html__("If you have an existing application, select it to modify its settings, or create a new one by clicking 'Create Application'. ",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Fill all the compulsory fields, then click on 'Submit' button.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("On the new page 'Authentication' copy the Authentication Keys 'Client ID' and 'Client Secret' and add them to our plugin settings.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("In 'Default Application Permissions' section check 'r_basicprofile' and 'r_emailaddress'.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("in 'OAuth 2.0' section add to 'Authorized Redirect URLs:' the URL you see at point 7.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Authorized redirect URLs",'sw-ajax-login').': '.$url_to_paste.'</li>
                                        <li>'.esc_html__("Press the 'Update' button.",'sw-ajax-login').'</li>
                                    </ol>',
                        'amazon' => '<ol class="description">
                                        <li>'.esc_html__('Login into your security profile in the Amazon Developer Console ','sw-ajax-login').'<a href="https://developer.amazon.com/loginwithamazon/console/site/lwa/overview.html" target="_blank">https://developer.amazon.com/loginwithamazon/console/site/lwa/overview.html</a><br/>
                                            '.esc_html__('You will be asked to login to the Developer Console, which handles application registration for Login with Amazon. If this is your first time using the Developer Console, you will be asked to set up an account.','sw-ajax-login').'</li>
                                        <li>'.esc_html__("Click Create a New Security Profile. This will take you to the Security Profile Management page.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Enter a Name and a Description for your security profile and fill all the other fields, then click on 'Save' button.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Go to the Web Settings of the security profile that you want to use for your app.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Locate the security profile you want to modify from the table and click on it.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Hover over the Manage menu and Select the Web Settings menu item.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Click Edit button you'll find at the bottom.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("To use Login with Amazon with a website, you must specify either Allowed Origins and Allowed Return URLs.",'sw-ajax-login').'</li>
                                        <li>'.esc_html__("Copy and paste this URL on both fields",'sw-ajax-login').': '.$url_to_paste.'</li>
                                        <li>'.esc_html__("Press the 'Save' button.",'sw-ajax-login').'</li>
                                    </ol>',
                        'apple' => '<ol class="description">
                                        <li></li>
                                    </ol>',
                        );


    ?>
     <!-- Login via Socials -->
    <h3><?php esc_html_e('Login via Socials','sw-ajax-login') ?></h3>
            <table class="form-table">
                <tr valign="top">
                <th scope="row"></th>
                <td>
                    <input type="checkbox" class="sw-showoncheck" data-target="internal-socials-login" id="swal_add_socials_login" name="swal_add_socials_login" value="1" <?php echo checked( '1', $swal_add_socials_login, true ); ?>/>
                    <label for="swal_add_socials_login" class="sw-right-label"><?php esc_html_e('Add socials login','sw-ajax-login'); ?></label>
                    <p class="description wrapper-tab_content">
                        <?php esc_html_e('This option enables login via socials.','sw-ajax-login');
                         ?>
                    </p>
                </td>
                </tr>

                <tr valign="top" class="internal-socials-login">
                <th scope="row"><label for="swal_social_icons_position"><?php esc_html_e('Social Icons position','sw-ajax-login'); ?></label></th>
                <td>
                    <select name="swal_social_icons_position" id="swal_social_icons_position" class="floatL margin-right settings-select">
                        <?php
                            $item = array();
                            $item[] = __("Below login button",'sw-ajax-login');
                            $item[] = __("On top, before username and password fields",'sw-ajax-login');
                            $item[] = __("Between username and password fields and login button",'sw-ajax-login');
                            $item[] = __("Inside image container",'sw-ajax-login');
                            foreach($item as $key => $value) {
                                    echo '<option value="'.$key.'"'.selected( $swal_social_icons_position, $key,false ).'>'.$value.'</option>';
                                } 
                        ?>
                    </select>
                    <p class="clear description wrapper-tab_content">
                        <?php esc_html_e('This option set the position of social login icons, below login button, below input fields or inside the image container.','sw-ajax-login');
                         ?>
                    </p>
                </td>
                </tr>

                <tr valign="top" class="internal-socials-login">
                <th scope="row"></th>
                <td>
                    <?php
                    $args = array(
                            'id'            => 'swal_add_socials_login_to_register',
                            'name'          => 'swal_add_socials_login_to_register',
                            'value'         => $swal_add_socials_login_to_register,
                            'input_value'   => 1,
                            'label'         => esc_html__('Add socials login to registration form','sw-ajax-login'),
                            'label_class'   => 'sw-right-label',
                                );
                            swal_checkbox_ios_style( $args );
                            ?>
                    <p class="description wrapper-tab_content">
                        <?php esc_html_e('This option adds social logins to registration form.','sw-ajax-login');
                         ?>
                    </p>
                </td>
                </tr>

                <tr valign="top" class="internal-socials-login">
                <th scope="row"></th>
                <td>
                    <div class="inner-div-settings">
                        <div class="padding-td">
                            <input type="checkbox" id="swal_use_social_profile_picture" name="swal_use_social_profile_picture" value="1" <?php echo checked( '1', $swal_use_social_profile_picture, true ); ?>/>
                            <label for="swal_use_social_profile_picture" class="sw-right-label"><?php esc_html_e('Replace Avatar image with social profile picture','sw-ajax-login'); ?></label>
                            <p class="description">
                                <?php esc_html_e('By enabling this option Social profile picture will be shown instead of WordPress user avatar.','sw-ajax-login');
                                 ?>
                            </p>
                        </div>
                        <div class="native-socials-login">

                            <!-- Facebook API keys -->
                            <div class="padding-td">
                                <input disabled="disabled" type="checkbox" name="swal_add_native_facebook_login" value="0"/>
                                <label for="swal_add_native_facebook_login" class="sw-right-label"><i class="fa fa-facebook"></i><?php esc_html_e('Facebook','sw-ajax-login'); ?></label>
                                <div class="gc">
                                    <div class="sw-grid span-1 wrapper-tab_content">
                                    <p class="description sw-premium-text">
                                        <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                                    </p>
                                    </div>
                                </div>
                            </div>

                            <!-- X API keys -->
                            <div class="clear padding-td">
                                <input type="checkbox" class="sw-showoncheck" data-target="swal-twitter" id="swal_add_native_twitter_login" name="swal_add_native_twitter_login" value="1" <?php echo checked( '1', $swal_add_native_twitter_login, true ); ?>/>
                                <label for="swal_add_native_twitter_login" class="sw-right-label"><i class="fa fa-twitter"></i><?php esc_html_e('X','sw-ajax-login'); ?></label>
                                <div class="gc swal-twitter">
                                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                                        <label for="swal_twitter_id"><?php esc_html_e('Twitter App ID','sw-ajax-login'); ?></label>
                                        <input type="text" id="swal_twitter_id" name="swal_twitter_id" class="clear settings-select" value="<?php echo $swal_twitter_id; ?>">
                                    </div>
                                    <div class="sw-grid span-1-3 tablet-1-2 mobile-1 padding-td">
                                        <label for="swal_twitter_secret_key"><?php esc_html_e('Twitter App Secret','sw-ajax-login'); ?></label>
                                        <input type="text" id="swal_twitter_secret_key" name="swal_twitter_secret_key" class="clear settings-select" value="<?php echo $swal_twitter_secret_key; ?>">
                                    </div>
                                    <div class="sw-grid span-1 wrapper-tab_content">
                                    <a href="#" class="slidedown-div" data-item="twitter-info"><?php esc_html_e('How to get X API keys','sw-ajax-login'); ?> (?)</a>
                                    <div id="twitter-info" class="hide">
                                        <ol class="description">
                                            <li>
                                                <?php esc_html_e('Login into your X account and go to ','sw-ajax-login'); ?><a href="https://apps.twitter.com/app/new" target="_blank">https://apps.twitter.com/app/new</a>
                                            </li>
                                            <li><?php esc_html_e("Click on 'Add a New App' button. A popup will open. Enter the name of the app as you wish and your email, then click on 'Create New App ID' button.",'sw-ajax-login'); ?></li>
                                            <li><?php esc_html_e("Enter your desired Application Name, Description and your website address (points 4 & 5) making sure to enter the full address including the http:// or https://.", 'sw-ajax-login'); ?></li>
                                            <li><?php esc_html_e("Site URL",'sw-ajax-login'); ?>: <input value="<?php echo home_url(); ?>" readonly="readonly" type="text" class="settings-select"></li>
                                            <li><?php esc_html_e("Callback URL",'sw-ajax-login'); ?>: <input value="<?php echo home_url(); ?>" readonly="readonly" type="text" class="settings-select"></li> 
                                            <li><?php esc_html_e("After creating your X Application click on the tab that says 'Keys and Access Tokens' and get Consumer key(API Key) and Consumer secret(API secret).", 'sw-ajax-login'); ?></li>
                                            <li><strong><?php esc_html_e("Important: ",'sw-ajax-login'); ?></strong><?php esc_html_e("To get the user's email address please go to app's permission tab and in additional Permissions there you will find a checkbox to request for user email address. Please enable it. To enable it you need to enter privacy policy url and terms of service url.", 'sw-ajax-login'); ?></li>              
                                        </ol>
                                    </div>
                                    <hr/>
                                    </div>
                                </div>
                            </div>

                            <!-- Google API keys -->
                            <div class="clear padding-td">
                                <input disabled="disabled" type="checkbox" value="0"/>
                                <label for="swal_add_native_google_login" class="sw-right-label"><i class="signin-google"></i><?php esc_html_e('Google','sw-ajax-login'); ?></label>
                                <div class="gc">
                                    <div class="sw-grid span-1 wrapper-tab_content">
                                    <p class="description sw-premium-text">
                                        <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                                    </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Linkedin API keys -->
                            <div class="clear padding-td">
                                <input disabled="disabled" type="checkbox" value="0"/>
                                <label for="swal_add_native_linkedin_login" class="sw-right-label"><i class="fa fa-linkedin"></i><?php esc_html_e('Linkedin','sw-ajax-login'); ?></label>
                                <div class="gc">
                                    <div class="sw-grid span-1 wrapper-tab_content">
                                    <p class="description sw-premium-text">
                                        <?php esc_html_e('Available on Premium version','sw-ajax-login'); ?>
                                    </p>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <?php 
                            //check if woocommerce is installed and activated
                            if ( class_exists( 'WooCommerce' ) ) {
                                  
                                ?>
                            <div class="clear padding-td">
                                <input type="checkbox" id="swal_add_social_login_to_woocommerce" name="swal_add_social_login_to_woocommerce" value="1" <?php echo checked( '1', $swal_add_social_login_to_woocommerce, true ); ?>/>
                                <label for="swal_add_social_login_to_woocommerce" class="sw-right-label"><?php esc_html_e('Add to WooCommerce login form','sw-ajax-login'); ?></label>
                                <p class="description">
                                <?php esc_html_e('This option adds Stranoweb Ajax Login native social login buttons to WooCommerce login form','sw-ajax-login');
                                 ?>
                                </p>
                            </div>
                                <?php } ?>

                            <h3><?php esc_html_e('Icons theme','sw-ajax-login'); ?></h3>
                            <?php 
                            $item = array();
                            $item[3] = __('Theme 4','sw-ajax-login');
                            $item[4] = __('Theme 5','sw-ajax-login');
                            foreach($item as $key => $value) {
                                $key = $key+1;
                            echo '<div class="padding-td clear">';
                                    echo '<input type="radio" id="swal_social_icons_theme'.$key.'" name="swal_social_icons_theme" value="'.$key.'"'.checked( $swal_social_icons_theme, $key,false ).'>
                                    <label class="sw-right-label" for="swal_social_icons_theme'.$key.'">'.$value.'</label>';
                                    ?>
                                    <div class="swal-login-networks theme<?php echo $key ?> clear">
                                      <div class="social-networks">
                                        <div class="fb-login-button item-disabled">
                                            <div class="swal-icon-block icon-facebook">
                                                <i class="fa fa-facebook"></i>
                                                <span class="swal-long-login-text"><?php esc_html_e('Login with Facebook','sw-ajax-login'); ?></span>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="social-networks">
                                        <div class="twitter-login-button">
                                            <div class="swal-icon-block icon-twitter">
                                                <i class="fa fa-twitter"></i>
                                                <span class="swal-long-login-text"><?php esc_html_e('Login with X','sw-ajax-login'); ?></span>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="social-networks">
                                        <div class="google-login-button item-disabled">
                                            <div class="swal-icon-block icon-google">
                                                <i class="signin-google"></i>
                                                <span class="swal-long-login-text"><?php esc_html_e('Login with Google','sw-ajax-login'); ?></span>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="social-networks">
                                        <div class="linkedin-login-button item-disabled">
                                            <div class="swal-icon-block icon-linkedin">
                                                <i class="fa fa-linkedin"></i>
                                                <span class="swal-long-login-text"><?php esc_html_e('Login with Linkedin','sw-ajax-login'); ?></span>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php
                             } 
                        ?>

                        </div>
                    </div>
                </td>
                </tr>
            </table>
            <?php submit_button(esc_html__('Save Changes','sw-ajax-login'),'primary','submit_tab_7',true,array('id' => 'submit_tab_7' )); ?>

            <!-- Premium Advise -->
            <div class="clear description-section">
                <h3><?php esc_html_e('Want more social logins?','sw-ajax-login'); ?></h3>
                <p><?php esc_html_e('Social Login makes it easier for visitors to your site to become customers by using their existing social media credentials to register on your site.','sw-ajax-login');
                 ?></p>
                 <p><?php esc_html_e('With Premium version you\'ll get more social logins and all the StranoWeb Ajax Login features!','sw-ajax-login');
                 ?></p>
                 <div>
                    <div class="swal-premium-button left">
                        <a href="<?php echo esc_html(SWAL_PREMIUM_BUY_LINK) ?>"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> <?php esc_html_e('Upgrade to the Premium version', 'sw-ajax-login') ?></a>
                    </div>
                </div>
            </div>

    <?php
}


?>