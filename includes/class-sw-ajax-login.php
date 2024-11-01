<?php
/**
 * Stranoweb Ajax Login setup
 *
 * @author   Stranoweb
 * @category API
 * @package  sw-ajax-login-free
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Main Stranoweb Ajax Login Class.
 *
 * @class Layers_SwAjaxLogin_free
 * 
 */

class Layers_SwAjaxLogin_free {
 
    /**
     * Initializes the plugin.
     *
     * To keep the initialization fast, only add filter and action
     * hooks in the constructor.
     */
    private static $instance;
     
    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
            self::$instance->__construct();
        }
        return self::$instance;
    }


    public function __construct() {

        // Before init action.
        do_action( 'before_swal_init' );

        // includes core files and constants
        $this->define_constants();
        $this->define_default_settings();
        $this->includes();
        $this->i18n();
        $this->init_actions();

        // enqueue Frontend scripts & styles
        add_action( 'wp_enqueue_scripts', array( $this, 'add_swal_ajax_login_scripts'), 99999 );
        add_action( 'wp_enqueue_scripts', array( $this, 'add_swal_ajax_login_styles'), 99999 );

        // Adds async attribute to scripts
        add_filter('script_loader_tag', array( $this, 'add_defer_attribute'), 10, 2);

        // enqueue Backend scripts & styles
        add_action( 'admin_head', array( $this, 'swal_admin_style'));
        add_action( 'admin_enqueue_scripts', array( $this, 'swal_admin_scripts'));

        // get current template name
        add_filter( 'template_include', array( $this, 'var_template_include'), 1000 );

        //redirect template page
        add_action( 'template_redirect', array( $this, 'swal_prefix_url_rewrite_annuncio_templates') );

        // Check for old submenu type
        add_action( 'admin_notices', array( $this, 'swal_admin_old_submenu_alert' ) );

        // Add query vars
        add_filter( 'query_vars', array( $this, 'swal_prefix_register_annuncio_query_var') );

        // Add SWAL menu item to admin bar
        add_action( 'admin_bar_menu', array( $this, 'swal_toolbar_link_to_mypage'), 999 );

        // adds plugin settings menu to admin 
        add_action( 'admin_menu', array( $this, 'sw_ajax_login_sub_menu_fields'));

        // Generate SWAL Admin Tabs menu
        add_action( 'swal_admin_tabs_menu', array( $this, 'swal_admin_add_tabs_menu') );

        // Includes Admin TinyMCE custom buttons
        add_filter( 'mce_external_plugins', array( $this, 'swal_tinymce_buttons_scripts' ));
        add_filter( 'mce_external_plugins', array( $this, 'swal_tinymce_extra_scripts' ));

        // Add custom buttons to TinyMCE
        add_filter( 'mce_buttons', array( $this, 'register_buttons_editor' ));

        // Add social login to registration form
        add_action( 'swal_register_end_of_form', array( $this, 'swal_add_social_logins_to_register_form') );

        // Add link to plugin settings in plugin list page
        add_filter( 'plugin_action_links_'.plugin_basename( SWAL_FILE ).'/'.plugin_basename( SWAL_FILE ).'.php', array( $this, 'swal_add_settings_link') );

        // Workaround for WooCommerce that mess with nonce when not logged user adds item to the cart
        add_filter('nonce_user_logged_out', function($uid, $action) {
          if ($uid && $action && ($action == 'ajax-login-nonce' || $action == 'ajax-register-nonce' || $action == 'ajax-forgot-nonce')) {
             $uid = 0;
          }
           return $uid;
        }, 100, 2);

        // Append all the forms to the footer and keep them hidden
        add_action('wp_footer', array( $this, 'append_swal_forms'));

        // Adds developer details as HTML comment on the footer
        add_action('wp_footer', array( $this, 'add_developer_detail'));

        // Workaround for those plugins that add an action to 'wp_login_failed', they mess up with SWAL ajax
        add_action( 'wp_login_failed', array( $this, 'swal_login_failed'), 1, 2 );
        
        // At the end of init action
        do_action( 'swal_loaded' );
    }


    /**
     * Define SWAL init actions.
     */
    private function init_actions() {

        // Register custom URL
        add_action( 'init', array( $this, 'prefix_swal_rewrite_rule') );

        // In Appearance > Menu you will see a new menu called SW Ajax Login User Menu, this is the submenu where you can add menu items
        //add_action( 'init', array( $this, 'register_swal_user_menu') ); --- DEPRECATED ---

        // Set a cookie with an unique value for the visitor.
        add_action( 'init', array( $this, 'swal_visitor_unique_value') );
    }



    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        require_once SWAL_PLUGIN_FUNCTIONS . '/wpml-integration.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-ajax.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-colors.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/parser.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-email-templates.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-forms.php';


    /**
     * Include required core files used in admin and on the frontend.
     */
    if (!is_admin() || (defined( 'DOING_AJAX' ) && DOING_AJAX)) {

        // Get social login adds 
        $this->swal_add_native_twitter_login      = intval(get_option('swal_add_native_twitter_login'));

        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-css.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-custom-menu-items.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-social-login-buttons.php';

        if ($this->swal_add_native_twitter_login)
            require_once SWAL_PLUGIN_FUNCTIONS . '/functions-twitter-login.php';
        }

    /**
     * Include required core files used in admin.
     */
    if (is_admin() && !(defined( 'DOING_AJAX' ) && DOING_AJAX)) {
        require_once SWAL_PLUGIN_DOCS . '/sw-ajax-login-docs.php';
        require_once SWAL_PLUGIN_DOCS . '/sw-ajax-login-premium.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-settings.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-apparence.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-menu.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-socials.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-redirects.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-login-window.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-register-window.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-user.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-recaptcha.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-messages.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-permalinks.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-forgot-password.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-logout-window.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-emails.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-advanced.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-header.php';
        require_once SWAL_PLUGIN_PATH . '/admin/admin-create-pages.php';
        require_once SWAL_PLUGIN_PATH . '/admin/sw-ajax-login-admin-register-fields-free.php';
        require_once SWAL_PLUGIN_FUNCTIONS . '/functions-checkboxes.php';
        }
    }

    /**
     * Define SWAL Constants.
     */
    private function define_constants() {

        $this->define("SWAL_FILE", plugin_dir_path( dirname(__FILE__)));
        $this->define("SWAL_PLUGIN_FRAMEWORK", plugin_dir_url( dirname(__FILE__) ));
        $this->define("SWAL_PLUGIN_PATH", plugin_dir_path( dirname(__FILE__)));
        $this->define("SWAL_WEBSITE_PATH", site_url());

        $this->define("SWAL_PLUGIN_CSS", SWAL_PLUGIN_FRAMEWORK . "assets/css");
        $this->define("SWAL_PLUGIN_JS", SWAL_PLUGIN_FRAMEWORK . "assets/js");
        $this->define("SWAL_PLUGIN_IMAGES", SWAL_PLUGIN_FRAMEWORK . "assets/img");
        $this->define("SWAL_PLUGIN_ADMIN_CSS", SWAL_PLUGIN_FRAMEWORK . "admin/css");
        $this->define("SWAL_PLUGIN_ADMIN_JS", SWAL_PLUGIN_FRAMEWORK . "admin/js");
        $this->define("SWAL_PLUGIN_ADMIN_IMAGES", SWAL_PLUGIN_FRAMEWORK . "admin/img");
        $this->define("SWAL_PLUGIN_FUNCTIONS", SWAL_PLUGIN_PATH . "includes/functions");
        $this->define("SWAL_PLUGIN_DOCS", SWAL_PLUGIN_PATH . "docs");
        $this->define("SWAL_PLUGIN_DOCS_IMAGES", SWAL_PLUGIN_FRAMEWORK . "docs/img");
    }

    /**
     *
     * Define SWAL Default settings
     *
     */
    private function define_default_settings() {

        $this->define("SWAL_PLUGIN_SETTINGS_PAGE", 'sw-ajax-login-free-settings'); //plugin settings page slug
        $this->define("SWAL_MENU_TO_APPEND", 'primary');
        $this->define("SWAL_PAGINA_ACCOUNT", 'account');
        $this->define("SWAL_PAGINA_ACCOUNT_DEFAULT", 0);
        $this->define("SWAL_USER_THUMBNAIL_STYLE_DEFAULT", 2);  //0 = no thumbnail, 1 = squared, 2 = rounded
        $this->define("SWAL_USER_THUMBNAIL_WIDTH", 24);
        $this->define("SWAL_REDIRECT_AFTER_LOGIN", 1);  //0 = homepage, 1 = same page, 2 = custom page
        $this->define("SWAL_REDIRECT_AFTER_LOGOUT", 0);  //0 = homepage, 1 = same page, 2 = custom page
        $this->define("SWAL_LOGGED_IN_REDIRECT", 1); //0 = alert message, 1 = redirect to homepage, 2 = redirect to custom page
        $this->define("SWAL_MENU_ITEM_TEXT", 0);  //0 = login, 1 = login/register, 2 = custom text
        $this->define("SWAL_POPUP_LAYOUT_STYLE", 0);  //0 = simple, 1 = image on left, 2 = full popup image
        $this->define("SWAL_SIMPLE_LAYOUT_WIDTH", 340);  //default popup single column width
        $this->define("SWAL_DOUBLE_LAYOUT_WIDTH", 960);  //default popup double column width
        $this->define("SWAL_FORM_WIDTH", 320);  //default form width
        $this->define("SWAL_MAIN_OVERLAY_OPACITY", 50);  //default main overlay opacity
        $this->define("SWAL_POPUP_COLOR", '#ffffff');  //default popup color
        $this->define("SWAL_MAIN_OVERLAY_COLOR", '#000000');  //default main overlay color
        $this->define("SWAL_POPUP_TEXT_COLOR", '#333333');  //default text color
        $this->define("SWAL_POPUP_SECONDARY_TEXT_COLOR", '#B2B0B0');  //default secondary text color
        $this->define("SWAL_POPUP_IMAGE_TEXT_COLOR", '#ffffff');  //default text over images color
        $this->define("SWAL_LINK_COLOR", '#e25c4c');  //default link color
        $this->define("SWAL_LOADER_BACKGROUND_COLOR", '#000000');  //default link color
        $this->define("SWAL_LOADER_TEXT_COLOR", '#ffffff');  //default text over images color
        $this->define("SWAL_ADD_OVERLAY_LOGIN", 0);  //0 = false, 1 = true
        $this->define("SWAL_ADD_OVERLAY_REGISTER", 0);  //0 = false, 1 = true
        $this->define("SWAL_ADD_OVERLAY_PASSWORD", 0);  //0 = false, 1 = true
        $this->define("SWAL_OVERLAY_COLOR", '#000000'); //default overlay color
        $this->define("SWAL_LOGOUT_OVERLAY_COLOR", '#000000'); //default overlay color
        $this->define("SWAL_POPUP_BORDER_RADIUS", 4);  //default popup border radius
        $this->define("SWAL_BUTTON_BORDER_RADIUS", 3);  //default submit button border radius
        $this->define("SWAL_INPUT_BORDER_RADIUS", 3);  //default input fields border radius
        $this->define("SWAL_BUTTON_HEIGHT", 40);  //default button height
        $this->define("SWAL_INPUT_HEIGHT", 40);  //default input height
        $this->define("SWAL_INPUT_TEXT_COLOR", '#333333'); //default overlay color
        $this->define("SWAL_INPUT_BACKGROUND_COLOR", '#F9F9F9'); //default overlay color
        $this->define("SWAL_INPUT_BORDER_COLOR", '#9a9a9a'); //default overlay color
        $this->define("SWAL_INPUT_FOCUS_COLOR", '#3379f9'); //default overlay color
        $this->define("SWAL_ADD_SOCIALS_LOGIN", 0);  //0 = false, 1 = true
        $this->define("SWAL_ADD_NATIVE_SOCIALS_LOGIN", 0);  //0 = Enable native social plugin
        $this->define("SWAL_DISABLE_NEW_USER_REGISTRATION", 0);  //0 = false, 1 = true
        $this->define("SWAL_OVERLAY_OPACITY", 30);
        $this->define("SWAL_MENU_ITEM_STYLE", 1); //0 = logout link, 1 = username + submenu
        $this->define("SWAL_LOGIN_BG_IMAGE_ALIGNMENT", 4); //4 = middle center
        $this->define("SWAL_REGISTER_BG_IMAGE_ALIGNMENT", 4); //4 = middle center
        $this->define("SWAL_PASSWORD_BG_IMAGE_ALIGNMENT", 4); //4 = middle center
        $this->define("SWAL_LOGOUT_BG_IMAGE_ALIGNMENT", 4); //4 = middle center
        $this->define("SWAL_SOCIAL_ICONS_THEME", 4);
        $this->define("SWAL_MIN_PASSWORD_LENGTH", 6);
        $this->define("SWAL_LOGIN_REMEMBER_CREDENTIALS", 0); //0 = always, 1 = never, 2 = show checkbox
        $this->define("SWAL_SOCIAL_ICONS_POSITION", 0); //0 = below login button
        $this->define("SWAL_MENU_ITEM_LINK_TO", 0); //0 = no link
        $this->define("SWAL_HIDE_ADMIN_BAR", 1); //1 = hide
        $this->define("SWAL_PREMIUM_BUY_LINK", 'https://www.ajaxlogin.com/');
        $this->define("SWAL_GDPR_CONSENT_TEXT", esc_html__('I consent collecting and storing my data from this form.'));
        $this->define("SWAL_GDPR_CONSENTINTRO_TEXT", esc_html__('Privacy Policy'));
        $this->define("SWAL_REGISTER_SUCCESS_TEXT", esc_html__('Registration completed successfully! Please check your email for email verification.'));
        $this->define("SWAL_NO_PASSWORD_TEXT", esc_html__('Registration confirmation will be emailed to you.'));
        $this->define("SWAL_LOGIN_FORM_TITLE", esc_html__('Login'));
        $this->define("SWAL_LOGIN_BUTTON", esc_html__('LOGIN'));
        $this->define("SWAL_LOGIN_INTRO_TEXT", esc_html__('New to site?'));
        $this->define("SWAL_LOGIN_INTRO_TEXT_LINK", esc_html__('Create an Account'));
        $this->define("SWAL_LOGIN_FORGOT_PASSWORD_TEXT", esc_html__('Lost password?'));
        $this->define("SWAL_REGISTER_FORM_TITLE", esc_html__('Signup'));
        $this->define("SWAL_REGISTER_BUTTON", esc_html__('SIGNUP'));
        $this->define("SWAL_REGISTER_INTRO_TEXT", esc_html__('Already have an account?'));
        $this->define("SWAL_REGISTER_INTRO_TEXT_LINK", esc_html__('Login'));
        $this->define("SWAL_FORGOT_PWD_FORM_TITLE", esc_html__('Forgot Password?'));
        $this->define("SWAL_FORGOT_PWD_BUTTON", esc_html__('SUBMIT'));
        $this->define("SWAL_FORGOT_PWD_INTRO_TEXT", esc_html__('Don\'t need to reset?'));
        $this->define("SWAL_FORGOT_PWD_INTRO_TEXT_LINK", esc_html__('Login'));
        $this->define("SWAL_AUTOPOPUP_DELAY", 2000); //auto popup opening delay

        // Messages
        $this->define("SWAL_LOGIN_SUCCESSFUL_TEXT", esc_html__('Login successful'));
        $this->define("SWAL_REGISTER_SUCCESSFUL_TEXT", esc_html__('Registration successful'));
        $this->define("SWAL_REDIRECTING_TEXT", esc_html__('redirecting...'));
        $this->define("SWAL_SENDING_USER_INFO_TEXT", esc_html__('Sending user info, please wait...'));
        $this->define("SWAL_LOGGING_OUT_TEXT", esc_html__('Logging out, please wait...'));
        $this->define("SWAL_AUTHORIZATION_FAILED_TEXT", esc_html__('Authorization failed.'));
        $this->define("SWAL_SOMETHING_WENT_WRONG_TEXT", esc_html__('Something Went Wrong!'));
        $this->define("SWAL_2FA_CODE_TEXT", esc_html__('2FA Code'));
        $this->define("SWAL_2FA_BACK_TEXT", esc_html__('Back'));
        
        // Email templates
        $this->define("SWAL_EMAIL_BODY_COLOR", '#f4f4f4'); //email body background color
        $this->define("SWAL_EMAIL_CONTENT_BACKGROUND_COLOR", '#ffffff'); //email content background color
        $this->define("SWAL_EMAIL_CONTENT_COLOR", '#565656'); //email content text color
        $this->define("SWAL_EMAIL_HEADER_COLOR", '#446f9b'); //email header background color
        $this->define("SWAL_EMAIL_HEADER_TEXT_COLOR", '#ffffff'); //email header title color
        $this->define("SWAL_EMAIL_HEADER_TITLE_ALIGN", 0); //email header title alignment
        $this->define("SWAL_EMAIL_BODY_SHADOW", 1); //email header title alignment
        $this->define("SWAL_EMAIL_HEADER_IMAGE_ALIGN", 0); //email header title alignment
        // End Email templates
        $this->define("SWAL_P", 0);

        // Get social login adds 
        $this->swal_add_native_twitter_login      = intval(get_option('swal_add_native_twitter_login'));
    }

    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }


    /**
     * 
     * Return the social logins list
     * This function is filterable so you can change the list order
     * 
     */
    public static function swal_get_social_logins() {
        $socials = array(
            'twitter',
            );
        $socials      = apply_filters('swal_social_logins_list', $socials);
        return $socials;
    }


    /**
     *
     * Call translation files
     *
     */
    public function i18n() {
        load_plugin_textdomain( 'sw-ajax-login', false, basename( SWAL_PLUGIN_PATH) . '/languages');
    }

    /**
     *
     * Includes scripts
     *
     */
    public function add_swal_ajax_login_scripts() {

        global $wp_scripts;

        $scripttype = SWAL_DEVELOP ? '' : '.min';

        wp_enqueue_script('sw-ajax-auth-script', SWAL_PLUGIN_JS . '/ajax-auth-script'.$scripttype.'.js', array('jquery'), SWAL_PLUGIN_VERSION, true );
        if ( !is_user_logged_in() ) {
            wp_enqueue_script('swal_validate', SWAL_PLUGIN_JS . '/jquery.validate.min.js', array('jquery'), '1.17.0', true);
            wp_enqueue_script( 'password-strength-meter' );
        }
        
        //impostazione per redirect pagina
        $page_to_redirect = swal_page_to_redirect();
        $page_to_redirect_logout = swal_page_to_redirect_logout();

        $swal_add_recaptcha  = intval(get_option('swal_add_recaptcha'));
        $swal_recaptcha_key  = $swal_add_recaptcha ? esc_attr(get_option('swal_recaptcha_v3_key')) : '';
        $swal_register_success_message      = esc_html(get_option('swal_register_success_message', SWAL_REGISTER_SUCCESS_TEXT));
        $swal_recaptcha_theme               = esc_attr(get_option('swal_recaptcha_theme', 'light'));
        $swal_disable_fade_fx               = intval(get_option('swal_disable_fade_fx'));

        // auto popup settings
        $swal_enable_autopopup                = intval(get_option('swal_enable_autopopup'));
        $swal_autopopup_custom_pages          = 0;
        $swal_autopopup_delay                 = get_option('swal_autopopup_delay') ? intval(get_option('swal_autopopup_delay')) : SWAL_AUTOPOPUP_DELAY;
        $swal_pages_autopopup                 = array();
        $swal_loader_persistence             = get_option('swal_loader_persistence') ? intval(get_option('swal_loader_persistence')) : 2000;
        $swal_autopopup_delay_autoopen_after_closing          = intval(get_option('swal_autopopup_delay_autoopen_after_closing'));

        // messages
        $swal_sending_user_info_text          = get_option('swal_sending_user_info_text') ? esc_attr(get_option('swal_sending_user_info_text')) : __(SWAL_SENDING_USER_INFO_TEXT,'sw-ajax-login');
        $swal_logging_out_text                = get_option('swal_logging_out_text') ? esc_attr(get_option('swal_logging_out_text')) : __(SWAL_LOGGING_OUT_TEXT,'sw-ajax-login');
        $swal_authorization_failed_text       = get_option('swal_authorization_failed_text') ? esc_attr(get_option('swal_authorization_failed_text')) : __(SWAL_AUTHORIZATION_FAILED_TEXT,'sw-ajax-login');
        $swal_something_went_wrong_text       = get_option('swal_something_went_wrong_text') ? esc_attr(get_option('swal_something_went_wrong_text')) : __(SWAL_SOMETHING_WENT_WRONG_TEXT,'sw-ajax-login');
        $swal_2fa_code_text                   = get_option('swal_2fa_code_text') ? esc_attr(get_option('swal_2fa_code_text')) : __(SWAL_2FA_CODE_TEXT,'sw-ajax-login');
        $swal_2fa_back_text                   = get_option('swal_2fa_back_text') ? esc_attr(get_option('swal_2fa_back_text')) : __(SWAL_2FA_BACK_TEXT,'sw-ajax-login');


        if(class_exists( 'WooCommerce' ) && is_shop()){
            $page_id = wc_get_page_id('shop');
          } elseif (class_exists( 'WooCommerce' ) && Is_product()) {
            $page_id = 'wc_single_product';
          } else {
            $page_id = get_the_ID();
          }

         wp_localize_script( 'sw-ajax-auth-script', 'ajax_auth_object', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'redirecturl' => $page_to_redirect,
            'redirecturllogout' => wp_logout_url( $page_to_redirect_logout ),
            'redirecturllogin' => swal_login_page( '', '', '' ).'/?password=changed',
            'loadingmessage' => $swal_sending_user_info_text,
            'logoutmessage' => $swal_logging_out_text,
            'facebook_id' => esc_attr(get_option('swal_facebook_id')),
            'facebook_error_1' => $swal_authorization_failed_text,
            'facebook_error_2' => $swal_something_went_wrong_text,
            'enablerecaptcha'  => intval(get_option('swal_add_recaptcha')),
            'recaptchakey'  => $swal_recaptcha_key,
            'recaptchatheme'  => $swal_recaptcha_theme,
            'registermessage' => $swal_register_success_message,
            'locale'    => get_locale(),
            'swal_disable_fade' => $swal_disable_fade_fx,
            'swal_loader_persistence' => $swal_loader_persistence,
        ));

        // Check if auto popup is enabled, user is not logged in and the current page is in array
        if ($swal_enable_autopopup && !is_user_logged_in() && !swal_is_swal_page() && (!$swal_autopopup_custom_pages || in_array($page_id, $swal_pages_autopopup))) {
            wp_localize_script( 'sw-ajax-auth-script', 'ajax_auto_popup_object', array( 
                    'swal_enable_autopopup' => $swal_enable_autopopup,
                    'swal_autopopup_delay' => $swal_autopopup_delay,
                    'swal_autopopup_autoopen_delay' => $swal_autopopup_delay_autoopen_after_closing,
                ));
        }
    }

    /**
     *
     * Includes styles
     *
     */
    public function add_swal_ajax_login_styles() {

        global $wp_styles;

        $swal_force_fontawesome_load        = intval(get_option('swal_force_fontawesome_load'));

        $scripttype = SWAL_DEVELOP ? '' : '.min';

        //check if font-awesome is already loaded if not loads
        $srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src') );
        if ( (in_array('font-awesome.css', $srcs) || in_array('font-awesome.min.css', $srcs)) && !$swal_force_fontawesome_load  ) {
            /* echo 'font-awesome.css registered'; */
          } else {
            wp_enqueue_style('swal-font-awesome', SWAL_PLUGIN_CSS . '/font-awesome/css/font-awesome.min.css' );
          }

        wp_enqueue_style( 'sw-ajax-auth-style', SWAL_PLUGIN_CSS . '/ajax-auth-style'.$scripttype.'.css');
    }

    
    /**
     *
     * Includes Admin scripts
     *
     */
    public function swal_admin_scripts() {

        global $wp_scripts;

        $current_screen = get_current_screen();

        if (isset($_GET["page"])) {
            if($_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE || $_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE."-docs" || $_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE."-admin-register-fields") {
                wp_enqueue_script('swal_tabbedcontent', SWAL_PLUGIN_JS . '/tabbedcontent.js', array('jquery'), SWAL_PLUGIN_VERSION, true);
                // Colorpicker Scripts
                wp_enqueue_script( 'wp-color-picker' );
                // Media
                wp_enqueue_media();
                wp_enqueue_script('swal_admin_scripts_admin', SWAL_PLUGIN_JS . '/admin.js', array('jquery'), SWAL_PLUGIN_VERSION, true);

                wp_localize_script( 'swal_admin_scripts_admin', 'ajax_auth_object', array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'mediabutton' => __('Set as background','sw-ajax-login'),
                    'mediatitle' => __('Select image','sw-ajax-login'),
                    'confirm1' => __('Do you want to remove the image?','sw-ajax-login'),
                    'confirm_title' => __('Remove input field','sw-ajax-login'),
                    'confirm_message' => __('Are you sure you want to delete this input field?','sw-ajax-login'),
                    'confirm_button' => __('OK','sw-ajax-login'),
                    'confirm_cancel' => __('Cancel','sw-ajax-login'),
                    'saving_settings' => __('Saving settings, please wait...','sw-ajax-login'),
                    'settings_saved' => __('Settings have been saved!','sw-ajax-login'),
                    'saving_error' => __('Problem occurred while saving your settings','sw-ajax-login'),
                ));
            }
        }

        // Scripts for admin users page
        if ($current_screen->base == 'users' || $current_screen->base == 'user-edit' ) {
            wp_enqueue_script('swal_admin_scripts_admin', SWAL_PLUGIN_JS . '/admin.js');
            wp_localize_script( 'swal_admin_scripts_admin', 'ajax_auth_object', array(
                    'activate' => __('Activate','sw-ajax-login'),
                    'deactivate' => __('Deactivate','sw-ajax-login'),
                ));
        }
    }

    /**
     *
     * Includes Admin styles
     *
     */
    public function swal_admin_style() {

        global $wp_styles;

        $current_screen = get_current_screen();

        if (isset($_GET["page"])) {
            if($_GET["page"] == "sw-ajax-login-free-settings" || $_GET["page"] == "sw-ajax-login-free-settings-docs" || $_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE."-admin-register-fields") {
                wp_enqueue_style('swal_admin_style', SWAL_PLUGIN_ADMIN_CSS . '/admin_style.css');
                // Colorpicker Styles
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style('font-awesome', SWAL_PLUGIN_CSS . '/font-awesome/css/font-awesome.min.css' );
                }
            }

        if ($current_screen->base == 'users' || $current_screen->base == 'user-edit' ) {
                wp_enqueue_style('swal_admin_style', SWAL_PLUGIN_ADMIN_CSS . '/admin_user_style.css');
            }

        }

    /**
     *
     * Includes Admin TinyMCE js file
     *
     */
    public function swal_tinymce_buttons_scripts($plugin_array) {
        //enqueue TinyMCE plugin script with its ID.
        if (is_admin() && current_user_can('administrator')) {
            $plugin_array["swal_button_plugin"] =  SWAL_PLUGIN_JS . "/admin-editor-buttons.js";
        }
        return $plugin_array;
    }

    /**
     *
     * Add custom buttons to TinyMCE
     *
     */
    public function register_buttons_editor($buttons) {
        //register buttons with their id.
        if (is_admin()) {
            array_push($buttons, "swal");
        }
        return $buttons;
    }

    /**
     *
     * Includes the function to check if tinyMCE content has changed
     * The only way to do it is adding it as plugin
     *
     */
    function swal_tinymce_extra_scripts($plugin_array) {
        if (isset($_GET["page"])) {
            if($_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE || $_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE."-docs" || $_GET["page"] == SWAL_PLUGIN_SETTINGS_PAGE."-admin-register-fields") {
                $plugin_array['keyup_event'] = SWAL_PLUGIN_JS . '/admin-for-tinymce.js';
                }
        }
        return $plugin_array;
    }

    /**
     *
     * Get current template
     *
     */
    public function var_template_include( $t ){
          $GLOBALS['current_theme_template'] = basename($t);
          return $t;
        }

    /**
     *
     * Redirect template page
     *
     */
    public function swal_prefix_url_rewrite_annuncio_templates() {
     
        if ( get_query_var( 'slug')=='sw-account') {

            // this filter is required for Elementor when in coming soon mode 
            add_filter( 'elementor/maintenance_mode/is_login_page', function( $value ) {

                if ( ! is_user_logged_in() ) {
                    $value = true;
                }
                return $value;
            } );
            add_filter( 'template_include', function() {
                return SWAL_PLUGIN_PATH . 'templates/sw-ajax-login-forms.php';
            });
        }
    }
     
    /**
     *
     * Register custom URL
     *
     */
    public static function prefix_swal_rewrite_rule() {

        $swal_pagina_account_default    = intval(get_option('swal_pagina_account_default',SWAL_PAGINA_ACCOUNT_DEFAULT));

        // apply only for native swal url
        if ($swal_pagina_account_default != 2) {
            add_rewrite_rule( 'login/?$', 'index.php?slug=sw-account', 'top' );
            add_rewrite_rule( '^'.swal_get_permalink().'/?([^/]*)/?', 'index.php?slug=sw-account&act=$matches[1]', 'top' );
        }
    }

    /**
     *
     * Add query vars
     *
     */
    public function swal_prefix_register_annuncio_query_var( $vars ) {
        $vars[] = 'slug';
        $vars[] = 'act';

        return $vars;
    }

    /**
     *
     * Add SWAL menu item to admin bar only to administrators
     *
     */
    public function swal_toolbar_link_to_mypage( $wp_admin_bar ) {
        $args = array(
            'id'    => 'swal_ajax_login',
            'title' => 'SW Ajax Login',
            'href'  => admin_url('/admin.php?page='.SWAL_PLUGIN_SETTINGS_PAGE),
            'meta'  => array( 'class' => 'swal-toolbar-page' )
        );
        
        $swal_disable_admin_bar_link        = intval(get_option('swal_disable_admin_bar_link'));

        if (current_user_can('administrator') && !$swal_disable_admin_bar_link) {
            $wp_admin_bar->add_node( $args );
        }
    }

    /**
     *
     * --- DEPRECATED ---
     * Register logged user menu
     *
     * In Appearance > Menu you will see a new menu called SW Ajax Login User Menu, this is the submenu where you can add menu items
     *
     */
    public function register_swal_user_menu() {
      register_nav_menu('swal-user-menu-item',__( 'SW Ajax Login User Menu' ));
    }


    /**
     *
     * Adds link to plugin settings in plugin list page
     *
     */
    public function swal_add_settings_link( $links ) {
        $settings_link = '<a href="options-general.php?page='.SWAL_PLUGIN_SETTINGS_PAGE.'">' . esc_html__( 'Settings','sw-ajax-login' ) . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     *
     * Adds async attribute to scripts
     *
     */
    public function add_defer_attribute($tag, $handle) {
       // add script handles to the array below
       $scripts_to_defer = array(
                            'sw-ajax-auth-script',
                            'swal_validate',
                            );
       
       foreach($scripts_to_defer as $defer_script) {
          if ($defer_script === $handle) {
             return str_replace(' src', ' defer src', $tag);
          }
       }
       return $tag;
    }

    /**
     *
     * Adds developer details as HTML comment on the footer
     *
     */
    public function add_developer_detail(){
      
        echo '<!--    Login form by Stranoweb Ajax Login. Learn more: https://www.ajaxlogin.com/   -->

                ';
    }

    /**
     *
     * Append SWAL forms to the footer
     *
     */
    public function append_swal_forms(){

        $swal_popup_animation       = sanitize_html_class(get_option('swal_popup_animation'));

        $class_animation            = $swal_popup_animation ? ' '.$swal_popup_animation : '';
  
        echo '<div class="login_overlay">
                <div id="popup-wrapper-ajax-auth" class="swal-popup-animation'.$class_animation.'">
                    <div id="sw-wrapper-ajax-login">';
                        swal_account_forms('ajax');
                 echo '</div>
                 </div>
            </div>';
    }

    /**
     *
     * Generate Admin Tabs menu
     *
     */
    public function swal_admin_add_tabs_menu () {

        // get the last tab name
            $tab_premium = esc_html__('Premium Version','sw-ajax-login');
            if (SWAL_P)
                $tab_premium = esc_html__('Features','sw-ajax-login');

            // compose the array with the tabs
            $menu_item[] = array(
                                'title'  => esc_html__('Appearence','sw-ajax-login'),
                                'priority'   => 5,
                                'callback'   => 'swal_admin_apparence_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html__('Menu','sw-ajax-login'),
                                'priority'   => 10,
                                'callback'   => 'swal_admin_menu_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html__('Social Login','sw-ajax-login'),
                                'priority'   => 15,
                                'callback'   => 'swal_admin_socials_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html__('Redirects & Permalink','sw-ajax-login'),
                                'priority'   => 20,
                                'callback'   => 'swal_admin_redirects_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html__('Login window','sw-ajax-login'),
                                'priority'   => 25,
                                'callback'   => 'swal_admin_login_window_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html__('Register window','sw-ajax-login'),
                                'priority'   => 30,
                                'callback'   => 'swal_admin_register_window_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html__('Forgot Password','sw-ajax-login'),
                                'priority'   => 35,
                                'callback'   => 'swal_admin_forgot_password_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html_x('Logout','sw-ajax-login'),
                                'priority'   => 40,
                                'callback'   => 'swal_admin_logout_window_settings',
                                );
            $menu_item[] = array(
                                'title'  => esc_html_x('Advanced','sw-ajax-login'),
                                'priority'   => 45,
                                'callback'   => 'swal_admin_advanced_settings',
                                );
            $menu_item[] = array(
                                'class'  => 'premium-tab',
                                'title'  => $tab_premium,
                                'priority'   => 50,
                                'callback'   => 'swal_display_premium_features',
                                );

            /**
             * Filters the arguments used to display a navigation menu.
             *
             * @param array $menu_item Array of tabs.
             */
            $menu_item = apply_filters( 'swal_admin_tabs_items', $menu_item );
            

            sw_create_tabs_menu_new('newTabs', $menu_item, true);

        return;

    }

    /**
     * adds plugin settings menu to admin
     */
    public function sw_ajax_login_sub_menu_fields() {
        add_submenu_page( SWAL_PLUGIN_SETTINGS_PAGE, esc_html__('Registration fields','sw-ajax-login'), esc_html__('Registration fields','sw-ajax-login'),'manage_options', SWAL_PLUGIN_SETTINGS_PAGE.'-admin-register-fields','swal_admin_register_fields_settings',1);
    }


    /**
     *
     * Alert for the menu associated to the old 'swal-user-menu-item' navigation menu
     * If selected will delete the old submenu and move
     * 
     * @since 1.6.1
     */

    public function swal_admin_old_submenu_alert() {

        if (isset($_GET["page"])) {
            $page = $_GET["page"];
            if($page != SWAL_PLUGIN_SETTINGS_PAGE 
                && $page != SWAL_PLUGIN_SETTINGS_PAGE."-docs" 
                && $page != SWAL_PLUGIN_SETTINGS_PAGE."-license" 
                && $page != SWAL_PLUGIN_SETTINGS_PAGE."-admin-register-fields") {
                return;
            }
        } else {
            return;
        }

        // Check if nav menu has a menu associated to it
        $has_menu = has_nav_menu('swal-user-menu-item');

        // if not returns
        if (!$has_menu) {
            return false;
        }
        ?>

        <div class="notice notice-warning is-dismissible">
            <p><img src="<?php echo SWAL_PLUGIN_IMAGES.'/swal-logo.png'; ?>" alt="" /></p>
            <h4><?php
                esc_html_e('Menus update required','sw-ajax-login');
             ?></h4>
             <p><?php
                esc_html_e('From version 1.6.0 we have extended the way to add Login / Register / Logout menu items to menus by adding them from WP Admin menus page.','sw-ajax-login')
             ?></p>
             <p>
                <strong><?php
                esc_html_e('What happens updating to the new menus?','sw-ajax-login');
             ?></strong><br/>
             <?php
                esc_html_e('After updating you will see on Admin Navigation menus the Login items you have assigned on our Menu section and all the submenu items you had previously added in the "old way" will be moved to the new Login items as their submenu.','sw-ajax-login');
             ?>
         <br/>
             <?php
                esc_html_e('Finally you can place the login item in any position!','sw-ajax-login');
             ?>
         </p>
         <p>
            <a href="<?php print wp_nonce_url(admin_url('admin.php?page='.$page.'&swal_update_menus=true'), 'swal_updating_menus', 'swal_nonce');?>"
        class="button button-primary"><?php esc_html_e('Update menus','sw-ajax-login'); ?></a>
         </p>
        </div>
    <?php
    }


    /**
     * 
     * Set a cookie with an unique value for the visitor.
     * Useful to use with transients instead of $_SESSION
     * Expires in 1 Week
     * 
     */
    public function swal_visitor_unique_value() {
        if(!isset($_COOKIE['swal_visitor_unique_value']) && !headers_sent()) {
            setcookie('swal_visitor_unique_value', swal_generateRandomString(32), time()+60*60*24*7, '/', COOKIE_DOMAIN, false  );
        }
    }


    /**
     * Workaround for those plugins that add an action to 'wp_login_failed', they mess up with SWAL ajax
     *
     * @since 1.8.0
     *
     */
    public function swal_login_failed( $username, $error ) {

        $error = $error->get_error_message();

        $output = json_encode(array(
                'loggedin'=>false,
                'message'=> __($error,'sw-ajax-login')
                ));

        if (isset($_POST['woocommerce-login-nonce'])) {
            return $output;
        } else {
            die($output);
        }
 
    }


    /**
     * Add social logins to registration form
     *
     * @since 1.8.5
     *
     */
    public function swal_add_social_logins_to_register_form() {

        $swal_add_socials_login_to_register  = intval(get_option('swal_add_socials_login_to_register'));
        $swal_social_icons_position          = intval(get_option('swal_social_icons_position',SWAL_SOCIAL_ICONS_POSITION));

        if ($swal_add_socials_login_to_register) {

            echo '<h4>' . esc_html__('or','sw-ajax-login') . '</h4>';

            swal_add_socials_login_buttons();

        }
    }
     
}


?>