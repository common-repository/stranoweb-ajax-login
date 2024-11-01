=== StranoWeb Ajax Login ===
Contributors: beeky2
Donate link: https://www.ajaxlogin.com/
Tags: ajax, login, register, logout, popup, modal, woocommerce, stranoweb, menu, dashboard, admin, e-commerce
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 2.0.4
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Stranoweb Ajax Login replaces default Wordpress login, register and lost password forms with a beautiful ajax modal popup and comes with a lot of amazing features.

== Description ==

Stranoweb Ajax Login replaces default Wordpress login, register and lost password forms with a beautiful ajax modal popup and comes with a lot of amazing features.
It’s fully customizable and responsive, includes several social logins and allows you to disable new user registration and restrict wordpress admin dashboard to certain user roles.


== Features ==
- Ajax login, register and lost password modal popup (same functions are working even on non-popup mode);
- Fully customizable login, register, lost password and logout popups and pages;
- Drag and Drop Registration Form builder (Premium version);
- User verification by administrator approval and email verification (Premium version);
- Different popup layouts with image and text over image option (2 on free version, 8 on Premium);
- Custom Logo on the forms (Premium version);
- Custom css setting;
- Social logins (Facebook, Twitter, Google, Linkedin, Amazon) with several icon styles and position displacement (Only twitter on free version);
- Logged in Menu item: Once logged in the plugin adds a menu item to the selected menu with optional user thumbnail and additional submenu Thumbnail style, menu item text and submenu are fully customizable;
- Customizable redirects and permalinks;
- Option to redirect not logged-in users to login page (Premium version);
- Wordpress admin dashboard access restriction to users with specific roles (Premium version);
- Password length, you can choose the minimum length required;
- Optional reCAPTCHA v2 and reCAPTCHA v3 for new user registration form;
- Shortcode Support;
- Emails Customizer;
- Shortcodes to add StranoWeb Ajax Login forms to any page or post;
- Hooks to help developers to integrate additional functions;


== Installation ==

= Minimum Requirements =

* PHP version 5.2.4 or greater (PHP 5.6 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)
* WordPress 4.4+

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of StranoWeb Ajax Login, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “StranoWeb Ajax Login” and click Search Plugins. Once you’ve found our Ajax Login plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Examples of Ajax modal popups.
2. Example of responsivity.
3. New user registration popup.
4. Admin area.
5. Admin area.

== Frequently Asked Questions ==

= Will StranoWeb Ajax Login work with my theme? =

Yes, StranoWeb Ajax Login works with any theme, however it may require some styling to make it match nicely. Note: the plugin is responsive but it may not work as expected on some themes when they are in mobile view, we are working on it to make it as much compatible with any theme as possible.

= Where can I report bugs or contribute to the project? =

Bugs can be reported on the [StranoWeb GitHub repository](https://github.com/stranoweb/stranoweb-ajax-login/issues).

== Changelog ==

= 2.0.4 - 2023-12-19 =
* Updated: Further updates to former Twitter texts;

= 2.0.3 - 2023-12-15 =
* Updated: Twitter logo updated to the new X and black color;
* Fixed: Github issue #39;

= 2.0.2 - 2023-09-22 =
* Fixed: Github issue #37;

= 2.0.1 - 2023-04-08 =
* Fixed: Some PHP 8 warnings;
* Fixed: Replaced a file in twitter login folder because of an error;

= 2.0.0 - 2023-03-02 =
* Fixed: a bug on registration form when username was disabled and autologin enabled;

= 1.9.9 - 2023-02-27 =
* Fixed: broken layout on registration form when username was disabled;

= 1.9.8 - 2023-02-15 =
* Fixed: PHP Warning header already sent issue with wp-cron.php;
* Added: new querystrings to open the popup: swal=login, swal=register, swal=lostpassword;

= 1.9.7 - 2023-01-25 =
* Fixed: excessive memory usage in admin users in the case of a large number of users on the site and user activation enabled;

= 1.9.6 - 2023-01-08 =
* Fixed: flush rewrite rules only for some options;

= 1.9.5 - 2023-01-05 =
* Fixed: function checking server variables causing warning when bots visiting the website;

= 1.9.4 - 2022-12-22 =
* Added: filter 'swal_placeholders' to add new items to emails placeholders array;

= 1.9.3 - 2022-11-17 =
* Added: new options to customize input fields and buttons;
* Added: new options to open popup by querystring parameter (Premium version);
* Fixed: an error that in some installations were caused due to bad connection to our server (Premium version);
* Updated: Nonces are now disabled by default, changed the option from "disable SWAL nonces" to "enable SWAL nonces";
* Updated: Facebook API version;
* Updated: added reset option to slider inputs;

= 1.9.2 - 2022-09-21 =
* Fixed: page titles attribute on admin pages were always referred to the default pages for login, register and so on;

= 1.9.1 - 2022-07-8 =
* Fixed: changed getCookie JS function name to swalgetCookie because of conflict with Ninja Forms plugin;
* Fixed: issue on Multistep admin, some checkboxes didn't work properly on mouse click;

= 1.9.0 - 2022-06-7 =
* Updated: tested up to WP version 6.0;
* Updated: added the default 'signup_extra_fields' hook to register form;

= 1.8.9 - 2022-04-22 =
* Updated: added social login icons positioning also on register form;

= 1.8.8 - 2022-01-31 =
* Updated: tested up to WP version 5.9;
* Fixed: a css issue, on some themes the popup wasn't center aligned in mobile devices;
* Fixed: a js issue, when user registration was disabled the anchor to open forgot password form didn't work;

= 1.8.7 - 2021-12-21 =
* Fixed: an issue with WooCommerce login where a json was returned when user inserted wrong credentials;

= 1.8.6 - 2021-12-1 =
* Added: option to enable registration without username. User just need to insert email and password;
* Added: option to hide StranoWeb Ajax Login admin bar link;
* Fixed: facebook login when user verification is enabled now shows the message to check emails to follow the instructions for email verification;
* Fixed: js error "ajax_auto_popup_object is not defined" when closing the popup;
* Updated: swal_check_if_menu_has_login_item() function, in some installations caused error;

= 1.8.5 - 2021-8-2 =
* Updated: tested up to WP version 5.8;
* Added: option to add social logins also to register form;

= 1.8.4 - 2021-5-26 =
* Fixed: in some circumstances the plugin created multiple login, register, forgot password, change password and logout pages when custom pages option was enabled;
* Added: auto opening delay after popup closing;

= 1.8.3 - 2021-4-22 =
* Fixed: some bug fixes;

= 1.8.2 - 2021-3-28 =
* Fixed: autologin was always disabled even if user verification was not enabled;

= 1.8.1 - 2021-2-21 =
* Added: a workaround to fix the issue caused by plugins using the 'wp_login_failed' hook;

= 1.8.0 - 2021-2-19 =
* Added: new option to add 2 verifications type for new users: by email verification link and by admistrator approval;
* Added: two new columns for registration date and social login used for registration on admin users table;
* Added: new Messages settings tab on admin to customize all loader and alert texts;

= 1.7.9 - 2020-11-18 =
* Fixed: A css bug fix, some popup elements were clickable even if not visible;

= 1.7.8 - 2020-11-9 =
* Added: 9 opening popup animations (3 on free version);
* Added: Option to force FontAwesome set loading;
* Added: Option to do direct logout without confirmation popup;
* Added: WordPress 'user_registration_email' and 'registration_errors' filters to register function (thanks ykosbie, GitHub #19);
* Improved: Some JS and PHP code optimization;

= 1.7.7 - 2020-10-8 =
* Fixed: A css issue on first name and last name fields that weren't clickable in some circumstances;
* Fixed: Changed the class name from .clear to .swal-clear to avoid conflict with some themes;

= 1.7.6 - 2020-9-21 =
* Added: Option to set the loader fadeout timeout;
* Added: License key deactivation on plugin deactivation, this prevents that the key stays activated if user forgets to deactivate it when uninstalling the plugin;
* Fixed: An issue on register telephone field not being saved;
* Fixed: Google login was always redirecting to the homepage;

= 1.7.5 - 2020-8-24 =
* Updated tested up to WP version 5.5;
* Added: New multistep feature to register form (Premium);
* Added: Custom logout redirect also to WP admin bar logout;
* Added: Minified versions of JS and CSS files;
* Fixed: An issue that didn't allow to update some sections of Learnpress posts;

= 1.7.4 - 2020-7-28 =
* Added: Amazon login;
* Added: New action hooks on forms;
* Added: WooCommerce State / Province field on drag & drop builder, it's autopopulated by Country selection;
* Fixed: Twitter login was not working;
* Fixed: On register form builder, when adding multiple classes the space was removed;

= 1.7.3 - 2020-7-07 =
* Added: Support to MC4WP plugin (Mailchimp);
* Added: More WooCommerce fields on drag & drop register form editor;
* Added: The * symbol to the mandatory field's labels;

= 1.7.2 - 2020-7-02 =
* Added: Auto popup option;
* Fixed: The default WP reset password URL wasn't properly redirected to custom reset password URL;

= 1.7.1 - 2020-6-18 =
* Added: {RESET_PASSWORD_URL} tag added to emails editor, now you can also send out a reset password URL when users register;
* Fixed: Reset password page not visible if "Redirect to login page not logged-in users" option was enabled;
* Fixed: Telephone field wasn't added to backend users admin;
* Fixed: added (required) info to custom field labels on backend users admin if they are required in frontend register form;

= 1.7.0 - 2020-6-07 =
* Added: WPML compatibility;
* Added: Premium Plugin Update notice, now you'll be notified on updates on Plugins page and update it from that page;


= 1.6.1 - 2020-5-29 =
* Added: Support to MailPoet;
* Added: Shortcode to display only login/logout item;
* Added: New option "After registration redirect to:";
* Added: Extended the new way to add Login / Register / Logout menu items;
* Improved: French translation (Thanks to Walter Synold);
* Improved: Replaced h1 with h2 on popup titles, better for SEO;
* Fixed: LinkedIn social login not working;

= 1.6.0 - 2020-5-02 =
* Added: Now you can also add Login / Register / Logout menu items from WordPress Menu Admin!
* Added: Support to LiteSpeed Cache plugin.
* Added: Option to disable nonce verification on Login, Register, Forgot Password forms to fix issues with some Caching Plugins.
* Added: Option "Only Name" to "Once logged show on main menu" settings.
* Improved: Added some extra documentation admin for Login, Register, Forgot Password text fields.
* Improved: Extended the support to Mailster plugin, now the checkbox addeds by that plugin on register form is styled like SWAL checkboxes.
* Fixed: when a user logged in from Login Page wasn't redirected to the homepage if this option was enabled.
* Fixed: SWAL shortcodes dropdown list now apperas only on backend editors.

= 1.5.6 - 2020-4-07 =
* Added: 2 new fields 'Title' and 'Paragrah' on register form drag & drop builder.
* Added: a new <span> wrapper to login menu item with editable class, this helps customization with certain themes.
* Improved: Now you can remove popup's top texts and links just leaving the fields empty on admin settings.

= 1.5.5 - 2020-4-01 =
* Updated tested up to WP version 5.4
* Added: New option for popup close button position.
* Improved: some PHP and JS code optimization.

= 1.5.4 - 2020-3-21 =
* Added: an hidden input field to register form to make it compatible with third party plugins that use "wp-submit" class or id to detect form submit.

= 1.5.3 - 2020-3-17 =
* Added: Checkbox field on drag & drop register form builder.
* Fixed: Some frontend css fixing.

= 1.5.2 - 2020-3-11 =
* Added: Option to disable google fonts loading for Google social login.
* Added: Option to disable HTML popup output if don't needed.
* Added: 2 new popup layouts.
* Improved: Now you can assign the login menu item to multiple menu.
* Fixed: An error on WP site health check.
* Fixed: a JS error related to reCaptcha V2 that didn't allow popup opening.

= 1.5.1 - 2020-2-26 =
* Improved: Changed form IDs to avoid conficts with other plugins using same old IDs.
* Fixed: Google Sign-in now working.

= 1.5.0 - 2020-2-01 =
* Added: License key Plugin Activation.
* Added: New drag & drop builder for registration form.
* Added: 'Hide header' option in email editor.
* Added: Subject editor in email editor.
* Added: New sticky header with update settings button always visible.
* Improved: Ajax save plugin settings.
* Improved: User roles list admin restriction, now the list is extended to any additional role.
* Fixed: 'Remember me' checkbox is now styled.
* Fixed: Redirection to same page issue in some cases.
* Fixed: missing website URL on social login admin guides.
* Fixed: Redirect after password change was always to /login.

= 1.4.3 - 2019-12-18 =
* Added: 2 registration form type, with password fields and without password fields (password is randomly-generated).
* Added: Form fieldset width option (Premium).
* Added: All the texts are now customizable (Premium).
* improved: js code optimization.

= 1.4.2 - 2019-12-07 =
* Added: New Emails admin section, now you can customize emails! (Premium)
* Fixed: New user registration email sent twice.
* Fixed: New user successful registration message line break issue.
* Added: Custom pages options on permalinks settings (now also on free version)
* improved: Some code optimization.

= 1.3.4.1 - 2019-11-23 =
* Fixed: Linkedin login updated to V2.
* Added: Option to redirect to Admin dashboard after login.
* improved: rewritten the admin settings page structure to allow future addons.

= 1.3.4 - 2019-10-21 =
* Added: Option to enable/disable email to new user when registered.
* improved: Some code optimization.
* Fixed: facebook login always in italian https://github.com/stranoweb/stranoweb-ajax-login/issues/7.

= 1.3.3 - 2019-9-14 =
* Fixed: forgot password email was sent twice.

= 1.3.2 - 2019-8-23 =
* Added: German translation (Thanks to Bastbra).

= 1.3.1 - 2019-8-17 =
* Fixed: A Js issue when new member registration was disabled.

= 1.3.0 - 2019-8-01 =
* Added: Custom pages options on permalinks settings.
* Added: New Shortcodes to show the forms as full form (like they appear in popups) or flat version (the naked version).
* Added: SWAL Shortcodes dropdown list on text editor.
* Fixed: Removed a PHP error on admin settings (on free version).
* Fixed: An issue with Elementor when in maintenance mode not showing StranoWeb Ajax Login forms.
* improved: More detailed error messages.
* improved: CSS.

= 1.2.2 - 2019-6-19 =
* Fixed: not redirecting after logout as expected.

= 1.2.1 - 2019-6-14 =
* Fixed: not all the new files were uploaded on WordPress repository.

= 1.2.0 - 2019-6-13 =
* Added: You can select between reCAPTCHA v2 and v3.
* Added: Enabled the use of shortcodes.
* Added: 2 new classes to directly open register and forgot password popups instead of always opening login popup.
* Added: A new option to disable automatic login after registration, useful when using plugins for email validation (Premium version).
* Added: Option to redirect not logged-in users to login page (Premium version).
* Fixed: Page title when the forms are showed on page template.

= 1.1.1 - 2019-5-19 =
* Fixed: <p> and <br/> tags removed on description custom texts.

= 1.1.0 - 2019-5-17 =
* Added: Show/Hide Password option on login and register form (Premium version).
* Added: reCAPTCHA v3.
* Fixed: Overlay not properly working on layout 5 and 6 (Premium version).

= 1.0.12 - 2019-05-15 =
* Tested up to WordPress 5.2.
* Fixed: Font Awesome CSS icons issue in some cases.

= 1.0.11 - 2019-04-17 =
* Tested up to WordPress 5.1.

= 1.0.10 - 2019-01-27 =
* Deferred javascripts to improve loading performance.

= 1.0.9 - 2019-01-20 =
* Added the async loading to the javascript to improve loading performance.
* Replaced Google sign in icon according to google guideline (Premium version).

= 1.0.8 - 2019-01-02 =
* Changed the way to assign the login item menu to a menu. Now you can add it to a specific menu instead of a navigation menu.

= 1.0.7 - 2018-12-26 =
* Completed some missing translation in italian on the free version.

= 1.0.6 - 2018-12-17 =
* Fixed a bug in the free version where the user's thumbnail size was 0 instead of 24 (Thanks Alexander Selivanuk for the bug reporting).

= 1.0.5 - 2018-12-12 =
* Fixed an issue where Colorpicker js wasn't loaded on some WordPress installations.

= 1.0.4 - 2018-12-02 =
* Added 2 new hooks and the possibility to upload a Logo on the forms (Premium only).
* Some CSS adjustments.

= 1.0.3 - 2018-11-29 =
* Minor fixes

= 1.0.2 - 2018-11-29 =
* Fixed an issue where the users logging-in via socials didn't trigger the GDPR consent automatically.
* Now you can not assign the menu item to any menu, this can be usefull when you want to use the plugin just to replace the default login, register, forgot password forms.

= 1.0.1 - 2018-11-26 =
* Some fixings to readme.txt file and added screenshots

= 1.0.0 - 2018-11-22 =
* Release date


== Upgrade Notice ==

= 0.0 =
No upgrades are required so far.

