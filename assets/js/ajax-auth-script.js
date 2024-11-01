jQuery(document).ready(function ($) {


InitializeAjaxLoginForms();

/**
 * 
 * WooCommerce country and state fields, only if WooCommerce is installed
 *
 */
if ($('#swal_billing_country').length && $('#swal_billing_state_field').length) {

  $('#swal_billing_country').on('change', function(e){

       $(this).fadeTo( 200, 0.4, function() {
          $(this).attr("disabled", true);
      });

        var country = $(this).val();
        $.ajax({
              type: 'POST',
              url: ajax_auth_object.ajaxurl + '?action=swal-getstates',
              data: { 
                'country': country, 
              },
              success: function(data){          
                $('#swal-wrapper_billing_state').replaceWith(data);
                $( '#swal_billing_country' ).fadeTo( 200, 1, function() {
                    $(this).attr("disabled", false);
                });
              }
        });
        
        return false;

    });
}


/**
 *
 * Initialize register sections Tabs when multistep form is enabled
 *
 */

function InitializeMultiStepRegisterForm() {


  // if there aren't steps (sections) in the form then return 
  if (!$('#swal-register section').length) return false;

    var form = $("#swal-register");
    var form_inner_div = '#swal_wrapper_register_fields';

    $(form_inner_div).steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: parseInt(swal_steps_object.swal_step_transition),
      enableFinishButton: false,
      transitionEffectSpeed: 300,
      labels: {
          next: swal_steps_object.swal_next,
          previous: swal_steps_object.swal_previous,
      },
      titleTemplate: '<span class="number">#index#.</span> <span class="title">#title#</span>',
      onInit: function (event, currentIndex) { 
          // resize the section height
          swal_resize_content(form_inner_div, currentIndex);
      },
      onStepChanging: function (event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        if (!$(form).valid()) {
            return false;
          }

          return form.valid();
      },
      onStepChanged: function (event, currentIndex, priorIndex) {
          // resize the section height
          swal_resize_content(form_inner_div, currentIndex);
      },
    });

    return false;

  
}


function swal_resize_content(selector, currentIndex) {
  var nextStep = selector+'-p-' + currentIndex;
  var totalHeight = 0;
  $(nextStep).children().each(function () {
      totalHeight += $(this).actual( 'outerHeight', { includeMargin : true });
  });
  //$(nextStep).parent().height(totalHeight + 60);
  $(nextStep).parent().animate({height:totalHeight + 60},300,'easeInOutQuad');
}

/**
 *
 * Auto popup
 *
 */

if (typeof ajax_auto_popup_object != "undefined" && ajax_auto_popup_object.swal_enable_autopopup) {
  if (!jQuery('.wrapper-ajax-forms').length) return true;

  var delay = ajax_auto_popup_object.swal_autopopup_delay;
  
  // If the cookie for no auto opening after closing is null then open the popup
  if (swalgetCookie('swal_no_autoopen_delay') == null) { 

    setTimeout(swal_open_login_popup, delay);
  }
}

/**
 *
 * Auto popup by querystring
 *
 */
if (typeof ajax_auto_popup_qs != "undefined" && ajax_auto_popup_qs.swal_autopopup_by_querystring == 'login') {
  if (!jQuery('.wrapper-ajax-forms').length) return true;
  setTimeout(swal_open_login_popup, 500);
}

if (typeof ajax_auto_popup_qs != "undefined" && ajax_auto_popup_qs.swal_autopopup_by_querystring == 'register') {
  if (!jQuery('.wrapper-ajax-forms').length) return true;
  setTimeout(swal_open_register_popup, 500);
}

if (typeof ajax_auto_popup_qs != "undefined" && ajax_auto_popup_qs.swal_autopopup_by_querystring == 'lostpassword') {
  if (!jQuery('.wrapper-ajax-forms').length) return true;
  setTimeout(swal_open_forgot_password_popup, 500);
}


/**
 * 
 * Facebook Login
 *
 */

 //trigger Facebook login

$('body').on('click', '.fb-login-button', function() {
		FBLogin();
		return	false;
	});

function inizializzaLoginFacebook() {

    if ($('.fb-login-button').length) {
        $.ajaxSetup({ cache: true });
        $.getScript('https://connect.facebook.net/'+ajax_auth_object.locale+'/sdk.js', function(){
          FB.init({
            appId      : ajax_auth_object.facebook_id,
            cookie     : true,
            xfbml      : true,
            version    : 'v15.0'
          });     
        });
    }
}

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {

  if (response.status === 'connected') {
      // Logged into your app and Facebook.
      // we need to hide FB login button
      $('#fblogin').hide();
      //fetch data from facebook
      getUserInfo();
  } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      $('#status').html('Please log into this app.');
  } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      $('#status').html('Please log into facebook');
  }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample code below.
function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
}

function FBLogin() {

	 showToast(ajax_auth_object.loadingmessage,'<div class="swal-loading-css-big-relative"></div>',false);
      FB.login(function(response) {
         if (response.authResponse) 
         {
             getUserInfo(); //Get User Information.
          } else
          {
           showToast(ajax_auth_object.facebook_error_1,'<i class="fa fa-exclamation"></i>',true);
          }
       },{scope: 'public_profile,email'});
}

function FBLogout() {

  	FB.logout(function(response) {
         //$('#fblogin').show(); //showing login button again
         //$('#fbstatus').hide(); //hiding the status
    });
}

function getUserInfo() {

    FB.api('/me', {fields: 'first_name,last_name,email,hometown,link,picture'}, function(response) {
     

      $.ajax({
            type: "POST",
            dataType: 'json',
            data: response,
            url: ajax_auth_object.ajaxurl + '?action=swal-fblogin',
            success: function(data) {
              if (data.loggedin == true) {

                // if auto login is disabled open login form and show the message
                  if (data.execlogin == false) {

                      showToast(data.message,'<i class="fa fa-check"></i>',true);

                      // show message on login form
                      showMessage(ajax_auth_object.registermessage,'.swal_login_form_message');
                      // enable button
                      submit.removeAttr("disabled").removeClass('disabled');
                      OpenLoginForm();

                  } else {
                      showToast(data.message,'<i class="fa fa-check"></i>',true);
                      document.location.href = ajax_auth_object.redirecturl;
                      ClosePopup();
                  }

					        
                } else {
                	showToast(data.message,'<i class="fa fa-exclamation"></i>',true);
              }
            }
      });

    });
	
}


/**
 * 
 * Twitter Login
 *
 */

 $('body').on('click', '.twitter-login-button', function(e) {

 	e.preventDefault;

  showToast(ajax_auth_object.loadingmessage,'<div class="swal-loading-css-big-relative"></div>',false);
 	
		$.ajax({
            type: "POST",
            dataType: 'json',
            data: response,
            url: ajax_auth_object.ajaxurl + '?action=swal-twlogin',
            success: function(data) {
             if (data.loggedin == true) {
					showToast(data.message,'<i class="fa fa-check"></i>',true);
                    document.location.href = ajax_auth_object.redirecturl;
                    ClosePopup();
                } else {
                	showToast(data.message,'<i class="fa fa-exclamation"></i>',true);
                }
            }
      });
		return	false;
	});


/**
 * 
 * Other non Ajax Social Logins
 * this is just to show a loader as the login process is fully in PHP
 *
 */
 $('body').on('click', '.swal-login-button', function(e) {
    showToast(ajax_auth_object.loadingmessage,'<div class="swal-loading-css-big-relative"></div>',false);
  });


/**
 * 
 * Initialize all the forms
 *
 */
function InitializeAjaxLoginForms() {

      InitializeAjaxLoginSubmit();
      inizializzaLoginFacebook();
      InitializeMultiStepRegisterForm();
}


 /**
  * 
  * Function to open Login Popup on click
  *
  */
	$('.sw-open-login').click(function(e) {

    var item = $(this);

    // If popup is disabled or the click is coming inside the popup then return and execute the default action
    if (!jQuery('.wrapper-ajax-forms').length || $(this).closest("#popup-wrapper-ajax-auth").length>0) {
      return true;
    }
    e.preventDefault();
    swal_open_login_popup();

  });

 /**
  * 
  * Function to open Register Popup on click
  *
  */
  $('.sw-open-register').click(function(e) {

    var item = $(this);

    // If popup is disabled or the click is coming inside the popup then return and execute the default action
    if (!$('.wrapper-ajax-forms').length || $(this).closest("#popup-wrapper-ajax-auth").length>0) {
      return true;
    }

    e.preventDefault();
    swal_open_register_popup(); 
    });

 /**
  * 
  * Function to open Forgot Password Popup on click
  *
  */
  $('.sw-open-forgot-password').click(function(e) {

    var item = $(this);
    
    if (!$('.wrapper-ajax-forms').length || $(this).closest("#popup-wrapper-ajax-auth").length>0) {
      return true;
    }
    e.preventDefault();
    swal_open_forgot_password_popup();       
    });



/**
 * 
 * Open Login Popup
 *
 */
function swal_open_login_popup() {
    
    if (jQuery('.swal_form_tabs').length) {
          jQuery('.swal_form_tabs a').removeClass('active');
          jQuery('.pop_login').addClass('active');
        }

      var collegamento = ajax_auth_object.ajaxurl + '?action=getLoginForms';
      if (jQuery('#popup-wrapper-ajax-auth #swal-login').length || jQuery('#popup-wrapper-ajax-auth .wrapper-logout').length) {
          
          openpopupauth(collegamento,false);
        }        
    }

/**
 * 
 * Open Register Popup
 *
 */
function swal_open_register_popup() {
    
    if (jQuery('.swal_form_tabs').length) {
          jQuery('.swal_form_tabs a').removeClass('active');
          jQuery('#pop_signup').addClass('active');
        }
      var collegamento = ajax_auth_object.ajaxurl + '?action=getLoginForms';
      if (jQuery('#popup-wrapper-ajax-auth #swal-register').length || jQuery('#popup-wrapper-ajax-auth .wrapper-logout').length) {
          
          OpenRegisterForm();
          openpopupauth(collegamento,false);
        }     
    }


/**
 * 
 * Open Forgot Password Popup
 *
 */
function swal_open_forgot_password_popup() {
    
    if (jQuery('.swal_form_tabs').length) {
          jQuery('.swal_form_tabs a').removeClass('active');
          jQuery('.pop_login').addClass('active');
        }
      var collegamento = ajax_auth_object.ajaxurl + '?action=getLoginForms';
      if (jQuery('#popup-wrapper-ajax-auth #forgot_password').length || jQuery('#popup-wrapper-ajax-auth .wrapper-logout').length) {
          
          OpenPasswordForm();
          openpopupauth(collegamento,false);
        }
    }




 /**
  * 
  * Function to open Logout Popup
  *
  */  
  $('.swal-logout-menu-item,.open_logout,.woocommerce-MyAccount-navigation-link--customer-logout').click(function(e) {
      e.preventDefault();
      var collegamento = ajax_auth_object.ajaxurl + '?action=getLoginForms';
      
          openpopupauth(collegamento,false); 
         
    });


 /**
  * 
  * Avoid that popup closes if clicked inside it.
  *
  */
		$('body').on('click', '#popup-wrapper-ajax-auth', function(e) {
		   e.stopPropagation();
		})

/**
* 
* On click to the links shows the selected form and hides all the others
*
*/
	$('body').on('click', '#sw-wrapper-ajax-login .pop_login, #sw-wrapper-ajax-login #pop_signup, #sw-wrapper-ajax-login #pop_forgot', function(e) {
      e.preventDefault();
      formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
      formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-register');
        formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-login');
        if ($('.swal_form_tabs').length) {
            $('.swal_form_tabs a').removeClass('active');
          }
        if ($(this).hasClass('pop_login')) {
            if ($('.swal_form_tabs').length) {
              $(this).addClass('active');
            }  
            formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
            if ($('#sw-wrapper-ajax-login #wrapper-register').length) {
              formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-register');
            } else {
              formToFadeOut2 = '';
            }
            formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-login');
        }
        if ($(this).attr('id') == 'pop_signup') {
            if ($('.swal_form_tabs').length) {
              $(this).addClass('active');
            } 
            formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-login');
            formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
            formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-register');
        }
        if ($(this).attr('id') == 'pop_forgot') {
            if ($('.swal_form_tabs').length) {
              $('.pop_login').addClass('active');
            } 
            formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-login');

            if ($('#sw-wrapper-ajax-login #wrapper-register').length) {
              formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-register');
            } else {
              formToFadeOut2 = '';
            }
            formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
        }
        if (formToFadeOut2) {
          // Check if has to disable fade animation
           if (ajax_auth_object.swal_disable_fade == 0) {
              formToFadeOut2.fadeOut(300, function () {
                formToFadeOut.fadeOut(300, function () {
                    formtoFadeIn.fadeIn(300);
                }); 
              }); 
           } else {
                formToFadeOut2.hide();
                formToFadeOut.hide();
                formtoFadeIn.show();
           }
          
        } else {
          formToFadeOut.fadeOut(300, function () {
                formtoFadeIn.fadeIn(300);
            }); 
        }
        
    });

/**
* 
* Set up login form as default form when the popup has been closed.
* It avoids to show the other forms instead of login when it's opened again.
*/
function OpenLoginForm() {
  var formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-register');
  var formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
  var formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-login');
  formToFadeOut.hide();
  formToFadeOut2.hide();
  formtoFadeIn.show();
}

function OpenRegisterForm() {
  var formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-login');
  var formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
  var formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-register');
  formToFadeOut.hide();
  formToFadeOut2.hide();
  formtoFadeIn.show();
}
function OpenPasswordForm() {
  var formToFadeOut = $('#sw-wrapper-ajax-login #wrapper-login');
  var formToFadeOut2 = $('#sw-wrapper-ajax-login #wrapper-register');
  var formtoFadeIn = $('#sw-wrapper-ajax-login #wrapper-forgot_password');
  formToFadeOut.hide();
  formToFadeOut2.hide();
  formtoFadeIn.show();
}


// Append the loader to the button or element with 'add-loader-onclick' on click.

	$('body').on('click', '.add-loader-onclick', function() {
			//$(this).attr("disabled", true).html("<span>"+txt_button_sending+"</span>");
			$(this).append("<div class='loading-css'></div>");
		});



/**
 * 
 * Opens ajax modal popup
 *
 * @preload: true = preloads the page on the DOM but keeps it hidden
 *
 */
function openpopupauth(pagina,preload) {
		
		var hide_class = '';

    recaptcha_version = $('#swal_recaptcha_register_version').val();
							
				showOverlayAuth(preload);

        //InitializeAjaxLoginForms();
          if (recaptcha_version == '0') {
                      grecaptcha.reset();
                  }
				
				return false;
}



$('body').on('click', '.close-popup, div.login_overlay', function(e) {
			e.preventDefault();
      hideOverlayAuth();

      if (typeof ajax_auto_popup_object === 'undefined') {
          return false;
      }

      // Set cookie containing the time for no auto opening after closing
      if (swalgetCookie('swal_no_autoopen_delay') == null) { 
          swalwriteCookie('swal_no_autoopen_delay', true, ajax_auto_popup_object.swal_autopopup_autoopen_delay);
      }
});

//funzione chiudi popup
function ClosePopup() {
    $('.swal-popup-animation').addClass('swal-closing');
	  $('.swal-popup-animation').removeClass('swal-ready');
}


/**
 * 
 * Show & Hide overlay & popup
 *
 */

function showOverlayAuth(preload) {

    $('body').css('overflow','hidden')
    $('.login_overlay').addClass('swal-overlay-open');
    $('.swal-popup-animation').addClass('swal-ready');
}

function hideOverlayAuth() {

    $('.login_overlay').addClass('swal-closing');
    $('.login_overlay').removeClass('swal-overlay-open');
    $('.swal-popup-animation').addClass('swal-closing');
    $('.swal-popup-animation').removeClass('swal-ready');
    // Bring body style to original overflow setting
    setTimeout(swalResetBodyStyle, 350);
}

function swalResetBodyStyle() {
  $('.login_overlay').removeClass('swal-closing');
  $('.swal-popup-animation').removeClass('swal-closing');
   $('body').css('overflow','auto');
   OpenLoginForm();
}


/**
 * 
 * Show Toast message
 *
 * @autofadeout: true = automatically fadeout the toast message after 2 seconds;
 *
 */
function showToast(testo,icona,autofadeout) {

	fadeout = '';

	if (autofadeout) {
		fadeout = ' fadeout';
	}
	if ($(".swal-toast").length) {
		$(".swal-toast").html(icona+testo);
		if (autofadeout) {
			$(".swal-toast").delay(ajax_auth_object.swal_loader_persistence).fadeOut("slow", function() {
			$(this).remove();
			});
		}
	} else {
		$('body').append($('<div class="swal-toast'+fadeout+'">'+icona+testo+'</div>').hide().fadeIn('normal'));
		$('.fadeout').delay(ajax_auth_object.swal_loader_persistence).fadeOut("slow", function() {
			$(this).remove();
			});
		}
	}


/**
 * 
 * Fadeout and remove Toast message
 *
 */
function hideToast() {

    $('.swal-toast').fadeOut("slow", function() {
      $(this).remove();
      });
  }

/**
 * 
 * Show message into a div
 *
 */
function showMessage(text,selector) {

  if ($(selector).length) {
    $(selector).html(nl2br(text));
    $(selector).hide().fadeIn('normal');
    }
  }

/**
 * 
 * Escape line break
 *
 */
function nl2br (str, is_xhtml) {     
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br/>' : '<br>';      
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');  
}  

/**
 * 
 * Remove a parameter from form data
 *
 */
function RemoveParameterFromFormData( formData, name ) {
      
      var newdata = formData.replace( new RegExp( `&${name}=[^&]*|${name}=[^&]*&` ), '' );

      return newdata;
    }

/**
 *
 * Show/Hide password
 *
 */
$('body').on('click','.swal-toggle-password', function (e) {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
});


/**
 * 
 * Perform Logout
 *
 */
$('body').on('click','#logout-button', function (e) {

    e.preventDefault();

    showToast(ajax_auth_object.logoutmessage,'<div class="swal-loading-css-big-relative"></div>',false);
    document.location.href = ajax_auth_object.redirecturllogout;
    ClosePopup();
  
});



/**
 * 
 * Handle Ajax form submit
 *
 */
function HandleAjaxFormSubmit(action,redirect,dataForm,token) {

    var submit = $("form#swal-login button, form#swal-register button");

        // disable button onsubmit to avoid double submision
        submit.attr("disabled", "disabled").addClass('disabled');

        var recaptcha_version = $('#swal_recaptcha_register_version').val();

        // Compose dataForm with the extra fields
        dataForm = dataForm + '&action='+action;

        if (token != '') {
          dataForm = dataForm +'&g-recaptcha-response='+token;
        }

        $.ajax({
              type: 'POST',
              dataType: 'json',
              url: ajax_auth_object.ajaxurl,
              data: dataForm,
              success: function (data) {
              
                  if (data.loggedin == true) {
                      showToast(data.message,'<i class="fa fa-check"></i>',true);
                          if (redirect) {
                              if (redirect == 'show_message' || ($('#swal-register-form-wrapper').length && redirect == 'go_to_login')) {
                                  // show message
                                  showMessage(ajax_auth_object.registermessage,'#swal_register_form_message');
                                  $('#swal_wrapper_register_fields').fadeOut('normal');

                                  // enable button
                                  submit.removeAttr("disabled").removeClass('disabled');

                              } else if (redirect == 'go_to_login' && !$('#swal-register-form-wrapper').length) {
                                  // show message
                                  showMessage(ajax_auth_object.registermessage,'.swal_login_form_message');
                                  // enable button
                                  submit.removeAttr("disabled").removeClass('disabled');
                                  OpenLoginForm();

                              } else {
                                  document.location = redirect;
                                  ClosePopup();
                              }
                             
                          } else {
                              document.location = ajax_auth_object.redirecturl;
                              ClosePopup();
                          }
                          
                  } else {
                      if (recaptcha_version == '0') {
                          grecaptcha.reset();
                      }
                      if (data.message == '2fa_required') {
                        
                        // show 2fa field and hide username and password fields
                        manage2faInputFields();
                      } else {
                          showToast(data.message,'<i class="fa fa-exclamation"></i>',true);
                          
                      }
                      submit.removeAttr("disabled").removeClass('disabled');
                      
                  }
              }
        }); // close $.ajax
        
        return false;
}





/**
 * 
 * Perform AJAX login/register on form submit
 *
 */
function InitializeAjaxLoginSubmit() {

  	$('form#swal-login, form#swal-register').on('submit', function (e) {

        e.preventDefault();

          if (!$(this).valid()) return false;
           

        showToast(ajax_auth_object.loadingmessage,'<div class="swal-loading-css-big-relative"></div>',false);

        action = '';
        recaptcha_version = '';
        token = '';
        redirect = '';

        // Remove the action parameter from data form, it will be added later
        var dataForm = RemoveParameterFromFormData( $(this).serialize(), 'action' );

        if ($(this).attr('id') == 'swal-login') {

        		action = 'ajaxlogin';
            redirect = $('form#swal-login #redirect_to').val();

            // Submit for via Ajax
            HandleAjaxFormSubmit(action,redirect,dataForm,token);
            

        } else if ($(this).attr('id') == 'swal-register') {

      			action = 'ajaxregister';
            recaptcha_version = $('#swal_recaptcha_register_version').val();
            redirect = $('form#swal-register #swal_register_page_to_redirect').val();

            // reCAPTCHA enabled
            if (ajax_auth_object.enablerecaptcha == '1') {

                if (recaptcha_version == '1') {

                    // reCAPTCHA v3
                    grecaptcha.ready(function() {
                            grecaptcha.execute(ajax_auth_object.recaptchakey, {action: 'register'}).then(function(token) {

                              // add token to the hidden field
                              //$('#swal_recaptcha_register').val(token);

                              // Submit form via Ajax
                              HandleAjaxFormSubmit(action,redirect,dataForm,token);
                              
                            });
                        });
                } else if (recaptcha_version == '0') {

                    // reCAPTCHA v2
                    HandleAjaxFormSubmit(action,redirect,dataForm,token);
                }
            } else {
            // reCAPTCHA not enabled

                // Submit form via Ajax without reCAPTCHA control
                HandleAjaxFormSubmit(action,redirect,dataForm,token);
            }
    		}  
          
    });

	
    /**
     * 
     * Perform AJAX forgot password on form submit
     *
     */
  	$('form#forgot_password').on('submit', function(e){

        e.preventDefault();

    		if (!$(this).valid()) return false;

        showToast(ajax_auth_object.loadingmessage,'<div class="swal-loading-css-big-relative"></div>',false);

        $('p.status').fadeOut('normal');

        var submit = $("#forgot_password button");

        // disable button onsubmit to avoid double submision
        submit.attr("disabled", "disabled").addClass('disabled');

    		form = $(this);
    		$.ajax({
        			type: 'POST',
                    dataType: 'json',
                    url: ajax_auth_object.ajaxurl,
        			data: { 
        				'action': 'ajaxforgotpassword', 
        				'user_login': $('#user_login').val(), 
        				'security': $('#forgotsecurity').val(), 
        			},
        			success: function(data){					

                  if (data.loggedin == true) {
                      showToast(data.message,'<i class="fa fa-check"></i>',true);
                    } else {
                      showToast(data.message,'<i class="fa fa-exclamation"></i>',true);
                    }
                    submit.removeAttr("disabled").removeClass('disabled');
        			}
    		});
    		
    		return false;
  	});


    /**
     * 
     * Perform AJAX reset password
     *
     */
    $("form#resetPasswordForm").on('submit', function(e){

        e.preventDefault();

        if (!$(this).valid()) return false;

        showToast(ajax_auth_object.loadingmessage,'<div class="swal-loading-css-big-relative"></div>',false);

        var submit = $("div#resetPassword #wp-submit"),
          message = $("div#resetPassword #message");


        // disable button onsubmit to avoid double submision
        submit.attr("disabled", "disabled").addClass('disabled');
        message.fadeOut(200);

        $.ajax({
          type: 'POST',
                dataType: 'json',
                url: ajax_auth_object.ajaxurl,
          data: { 
              'action': 'reset_pass', 
              'resetsecurity': $('#resetsecurity').val(), 
              'pass1': $('#pass1').val(), 
              'pass2': $('#pass2').val(),
              'user_key': $('#user_key').val(),
              'user_login_reset': $('#user_login_reset').val(),
          },
          success: function(data){          
     
              submit.removeAttr("disabled").removeClass('disabled');

              if (data.loggedin == true) {
                  showToast(data.message,'<i class="fa fa-check"></i>',false);
                  document.location.href = ajax_auth_object.redirecturllogin+'/?password=changed';
              } else {
                  //showToast(data.message,'<i class="fa fa-exclamation"></i>',true);
                  $('html, body').stop().animate({
                      scrollTop: $('[id="message"]').offset().top -100
                    }, 500);
                    // display return data only for errors
                  message.html( data.message ).fadeIn('normal');
                  hideToast();
              }

          },
          error: function () {
              showToast(ajax_auth_object.facebook_error_2,'<i class="fa fa-exclamation"></i>',true);
              submit.removeAttr("disabled").removeClass('disabled');
          }
        });

        return false;
    });

    

    /**
     * Password strength function
     */
    function checkPasswordStrength( $pass1,
                                    $pass2,
                                    $strengthResult,
                                    $submitButton,
                                    blacklistArray ) {
        var pass1 = $pass1.val();
        var pass2 = $pass2.val();
     
        // Reset the form & meter
        $submitButton.attr( 'disabled', 'disabled' );
            $strengthResult.removeClass( 'short bad good strong' );
     
        // Extend our blacklist array with those from the inputs & site data
        blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )
     
        // Get the password strength
        var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );
     
        // Add the strength meter results
        switch ( strength ) {
     
            case 2:
                $strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
                break;
     
            case 3:
                $strengthResult.addClass( 'good' ).html( pwsL10n.good );
                break;
     
            case 4:
                $strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
                break;
     
            case 5:
                $strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
                break;
     
            default:
                $strengthResult.addClass( 'short' ).html( pwsL10n.short );
     
        }
     
        // The meter function returns a result even if pass2 is empty,
        // enable only the submit button if the password is strong and
        // both passwords are filled up
        if ( 4 === strength && '' !== pass2.trim() ) {
            $submitButton.removeAttr( 'disabled' );
        }
     
        return strength;
    }
    
    /**
     *
     * Perform password check
     *
     */
    $( 'body' ).on( 'keyup', 'input[name=pass1], input[name=pass2]',
        function( event ) {
            checkPasswordStrength(
                $('input[name=pass1]'),         // First password field
                $('input[name=pass2]'), // Second password field
                $('#password-strength'),           // Strength meter
                $('input[type=submit]'),           // Submit button
                ['black', 'listed', 'word']        // Blacklisted words
            );
        });


    /**
     *
     * Client side form validation
     *
     */
      if (jQuery("#swal-register").length) {
      		jQuery("#swal-register").validate({
            ignore: ":hidden",
            rules:{
        			   password2:{ equalTo:'#signonpassword'}	
        		      }
          });
        }

      if (jQuery("#resetPasswordForm").length) {
          jQuery("#resetPasswordForm").validate({
            rules:{
                pass2:{ equalTo:'#pass1'} 
                  }
          });
      }
      else if (jQuery("#swal-login").length) 
  		    jQuery("#swal-login").validate();

    	if(jQuery('#forgot_password').length)
    		  jQuery('#forgot_password').validate();

} // close InitializeAjaxLoginSubmit function

    

  /**
   * 
   * reset login form. remove 2fa field and show username and password fielfds
   *
   */
  function manage2faInputFields() {
      
      var field = '<label for="username">'+ajax_auth_object.swal_2fa_label+'</label><div><input type="text" id="swal_2fa_code" name="swal_2fa_code" class="required" autocomplete="off"/><a href="#" class="swal_reset_2fa"><i class="fa fa-arrow-left" aria-hidden="true"></i> '+ajax_auth_object.swal_2fa_back+'</a></div>';

          if ($('.swal_2fa_wrapper').length ) {
            $('.swal-login-fields').hide();
            $('.swal_2fa_wrapper').append(field).hide().fadeIn(500);
            hideToast();
          }
  }

  /**
   * 
   * Hide username and password fields and show 2fa field
   *
   */

    $('body').on('click','.swal_reset_2fa', function(e) {

        e.preventDefault();

        $('.swal_2fa_wrapper').empty();
        $('#swal_2fa_code').attr('value', '');
        $('#password').attr('value', ''); 
        $('.swal-login-fields').fadeIn(500);
    });



});


/**
 *
 * Common functions
 *
 */
function InitializeRecaptcha() {

  if (jQuery('.g-recaptcha').length) {
            var sitekey = jQuery('.g-recaptcha').data('sitekey');
            var captchaWidgetId = grecaptcha.render( 'myCaptcha', {
              'sitekey' : sitekey,
              'theme' : ajax_auth_object.recaptchatheme,
            });
          }
}

/**
 *
 * Write, Read, Erase cookie
 *
 */
function swalwriteCookie(name, value, secs) {
    var expires;

    if (secs) {
        var date = new Date();
        date.setTime(date.getTime() + (secs * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function swalgetCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    swalwriteCookie(name, "", -1);
}



