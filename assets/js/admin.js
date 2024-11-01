(function($) {

  var activetab  = localStorage.getItem("swal-lastTab");
  var activetabdocs  = localStorage.getItem("swal-lastTab-docs");

  if (!activetab) {
         var activetab  = 0;
      }
  if (!activetabdocs) {
         var activetabdocs  = 0;
      }
  //Inizializza i tabs
  if ($('.swal-tabscontent').length) {
    $('.swal-tabscontent').tabbedContent({
            links: 'ul.newTabs li a'
        });
    var mytabs = $('.swal-tabscontent').tabbedContent().data('api');

    $('.swal-tabscontent').on('tabcontent.switch', function(api) {
        var switchtab = mytabs.getCurrent();
        localStorage.setItem("swal-lastTab",switchtab );
    });
    mytabs.switch(activetab);
  }

  if ($('.swal-tabscontent-docs').length) {
    $('.swal-tabscontent-docs').tabbedContent({
            links: 'ul.newTabs li a'
        });
    var mytabsdocs = $('.swal-tabscontent-docs').tabbedContent().data('api');

    $('.swal-tabscontent-docs').on('tabcontent.switch', function(api) {
        var switchtabdocs = mytabsdocs.getCurrent();
        localStorage.setItem("swal-lastTab-docs",switchtabdocs );
    });
    mytabsdocs.switch(activetabdocs);
  }


/*
 *
 * When document is ready show the page content and fade out the overlay loader
 *
 */
$( document ).ready(function() {
    $('.swal-wrap-inner').show();
    $('.swal-loader-overlay').delay(1000).fadeOut('slow');
});
  

/*
 *
 * Enable the buttons
 *
 */
function swalEnableButton() {
    $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');
  }



// Initialize ColorPicker
if ($('.swal-colorpicker').length) {
    $( '.swal-colorpicker' ).wpColorPicker({
      change: function (event, ui) {
          var target = $(this).data('target');
          var prop = $(this).data('prop');
          if (typeof prop !== "undefined") {
            var props = prop.split(',');
          }
          var color = ui.color.toString();
          
          if ($(target).length) {
              //loop the array with the properties
                $.each( props, function( key, value ) {
                  $(target).css(value, color);
                });
          }
            swalEnableButton();
        },
      clear: function (event) {
            swalEnableButton();
      }
    });
}


// The "Upload" button
$('.upload_image_button').click(function() {


    // Create the media frame.

    var button = $(this);
    var frame = new wp.media.view.MediaFrame.Select({
        // Modal title
        title: ajax_auth_object.mediatitle,
        multiple: false,

        // Library WordPress query arguments.
        library: {
          order: 'DESC',

          // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
          // 'id', 'post__in', 'menuOrder' ]
          orderby: 'date',
          type: 'image',

          // Searches the attachment title.
          search: null,
        },

        button: {
          text: ajax_auth_object.mediabutton,
        }
      });

 
      frame.on( 'select', function() {

        var attachment = frame.state().get('selection').first().toJSON();
        $(button).parent().prev().attr('src', attachment.url);
        $(button).prev().val(attachment.id);


      } );

      // Open the modal.
      frame.open();

      $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');

    return false;
});

// The "Remove" button (remove the value from input type='hidden')
$('.remove_image_button').click(function() {
    var answer = confirm(ajax_auth_object.confirm1);
    if (answer == true) {
        var src = $(this).parent().prev().attr('data-src');
        $(this).parent().prev().attr('src', src);
        $(this).prev().prev().val('');

        $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');
    }
    return false;
});


//input rage slider
var rangeSlider = function(){
  var slider = $('.range-slider'),
      range = $('.range-slider__range'),
      valuewrapper = $('.range-slider__value-wrapper');
      value = $('.range-slider__value');
    
  slider.each(function(){

    value.each(function(){
      var value = $(this).prev().attr('value');
      $(this).text(value);
    });

    range.on('input', function(){
      $(this).next(valuewrapper).find(value).text(this.value);
    });
  });
};

rangeSlider();


// Reset value anchors
$('.swal-reset-value').click(function() {
    var input = $(this).closest("div.sw-grid").find('input');
    var inputtab = $(this).closest("div.sw-grid").find('.range-slider__value');

    var defaultvalue = input.data('default');

    input.val(defaultvalue);
    inputtab.text(defaultvalue);

    // Enable all buttons
    $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');

    return false;
});


/*
 *
 * Enable the buttons anytime the form changes
 *
 */
swalEnableAdminButton();

function swalEnableAdminButton() {

    $('#swal-admin-form, .swal-admin-form').change(function() {
        $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');
        });
    $('#swal-admin-form, .swal-admin-form').keyup(function() {
        $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');
        });
    $('body').on('click','.wp-picker-container', function() {
        $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');
        });
  }


/**
 * 
 * Submit form by clicking on element with 'swal-submit-form' class
 *
 */
$('.swal-submit-form').click(function() {
        $('#swal-admin-form, .swal-admin-form').submit();
    });

swal_save_main_options_ajax();

function swal_save_main_options_ajax() {

      $('button.swal-add-loader,input[type="submit"]').attr("disabled", "disabled").addClass('disabled');

           $('#swal-admin-form, .swal-admin-form').submit( function () {

              showToast(ajax_auth_object.saving_settings,'<div class="swal-loading-css-big-relative"></div>',false);
              $('button.swal-add-loader,input[type="submit"]').attr("disabled", "disabled");
              $('.swal-add-loader').addClass('swal-loader-padding');
              $('.swal-add-loader i').remove();
              $('<i class="fa fa-refresh swal-processing swal-button-icon"></i>').hide().prependTo(".swal-add-loader").fadeIn("slow");

                var b =  $(this).serialize();
                $.post( 'options.php', b ).error( 
                    function() {
                        showToast(ajax_auth_object.saving_error,'<i class="fa fa-exclamation"></i>',true);
                    }).success( function() {
                        showToast(ajax_auth_object.settings_saved,'<i class="fa fa-check"></i>',true);

                        $('.swal-add-loader i').remove();
                        $('.swal-add-loader').prepend('<i class="fa fa-check swal-button-icon"></i>').find('i').delay(1900).fadeOut("slow", function() {
                            $(this).remove();
                    
                          });
                        window.setTimeout(function(){$(".swal-add-loader").removeClass("swal-loader-padding").addClass('disabled');}, 2000);
                    });
                    return false;    
                });
            }




//show divs depending on select->option value
ShowItemFromSelectOption('#swal_menu_item_text',[2],'#menu-custom-text');
ShowItemFromSelectOption('#swal_menu_item_logout_text',[1],'#menu-logout-custom-text');
ShowItemFromSelectOption('#swal_redirect_after_login',[2],'#custom-page-redirect');
ShowItemFromSelectOption('#swal_redirect_after_register',[1],'#custom-page-redirect-register');
ShowItemFromSelectOption('#swal_redirect_after_logout',[2],'#custom-page-redirect-logout');
ShowItemFromSelectOption('#swal_logged_in_redirect',[2],'#loggedin-custom-page-redirect');
ShowItemFromSelectOption('#swal_menu_item_link_to',[1],'#menu-item-custom-link');
ShowItemFromSelectOption('#swal_register_no_autologin_redirect',[0,1],'#after-register-custom-message');
ShowItemFromSelectOption('#swal_register_no_autologin_redirect',[3],'#after-register-custom-page-redirect');


ShowItemFromSelectOptionData('#swal_menu_to_append','no','#swal_menu_alert');

//hide divs depending on select->option value
HideItemFromSelectOption('#swal_user_thumbnail_style','0','#swal_thumbnail_width_slider');




// Shows contents when checkbox is checked
// Parent element must have "sw-showoncheck" class and data-target with the class of the target element

swal_show_on_check();
swal_opacity_on_check();


// Hides contents when checkbox is checked
// Parent element must have "sw-hideoncheck" class and data-target with the class of the target element

$('.sw-hideoncheck').each(
          function() {

            var parent = $(this);

            // First hide all the target selectors
            var item = '.'+parent.data('target');
            $(item).show();

            // then if the parent checkbox is checked shows the target selectors
            if(parent.is(":checked")){
                          $(item).hide();
                      }

            $(parent).click(function() {
                  if( parent.is(':checked')) {
                      $(item).fadeOut(300);
                  } else {
                      $(item).fadeIn(300);
                  }
                });
          }
        );


// Shows contents when radio button is checked
// Parent element must have "sw-radiobuttonshowoncheck" class and data-target with the class of the target element
// First hide all related groups
$('.sw-radiobuttonshowoncheck').each(
          function() {

            var parent = $(this);

            // First hide all the target selectors
            var group = '.'+parent.data('group');
            $(group).hide();
          });

$('.sw-radiobuttonshowoncheck').each(
          function() {

            var parent = $(this);
            var group = '.'+parent.data('group');

            // then if the parent checkbox is checked shows the target selectors
            if(parent.is(":checked")){
                          var item = parent.data('target');
                      }
            if (item) {
                            $('.'+item).show();
                          }

            $(parent).click(function() {
                var item = parent.data('target');
                
                // if the target is already visible return
                //if (!item) {
                    $(group).hide();
                //}
                if(item && !$('.'+item).is(":visible")){
                          $(group).hide();
                              if( parent.is(':checked')) {
                            
                                      $('.'+item).fadeIn(300);
                                    
                              }
                        }
                  
                });
          }
        );

// Shows contents from select option 
function ShowItemFromSelectOption(idselect,valueoption,itemtoshow) {
    var Privileges = $(idselect);
    var select = Privileges.val();
      $(itemtoshow).hide();

      $.each(valueoption, function (index, value) {
          if (select == value) {
              $(itemtoshow).show();
          }
        });

        
    Privileges.change(function () {
        $(itemtoshow).fadeOut(300)
        select = $(this).val();
        $.each(valueoption, function (index, value) {
          if (select == value) {
            $(itemtoshow).fadeIn(300);
          }
        });
        
        });
}

// Hides contents from select option 
function HideItemFromSelectOption(idselect,valueoption,itemtoshow) {
    var Privileges = $(idselect);
    var select = Privileges.val();
    if (select == valueoption) {
            $(itemtoshow).hide();
        }
        else $(itemtoshow).show();
        
    Privileges.change(function () {
        if ($(this).val() == valueoption) {
            $(itemtoshow).fadeOut(300);
        }
        else $(itemtoshow).fadeIn(300);
        });
}

// Shows contents from select option data
function ShowItemFromSelectOptionData(idselect,valueoption,itemtoshow) {
    var Privileges = $(idselect);
    var select = Privileges.find(':selected').data('select');
    if (select == valueoption) {
            $(itemtoshow).show();
        }
        else $(itemtoshow).hide();
        
    Privileges.change(function () {
      var select = Privileges.find(':selected').data('select');
        if (select == valueoption) {
            $(itemtoshow).fadeIn(300);
        }
        else $(itemtoshow).fadeOut(300);
        });
}


// slideDown & slideUp
$('body').on('click', '.slidedown-div', function() {
  var item = $(this).data('item');
  $('#'+item).slideToggle(300);
  return false;
  });

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
      $(".swal-toast").delay(2000).fadeOut("slow", function() {
      $(this).remove();
      });
    }
  } else {
    $('body').append($('<div class="swal-toast'+fadeout+'">'+icona+testo+'</div>').hide().fadeIn('normal'));
    $('.fadeout').delay(2000).fadeOut("slow", function() {
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




})(jQuery);

/*
 *
 * This function needs to be global to get reached by the other files
 *
 */
function swal_show_on_check() {
  jQuery('.sw-showoncheck').each(
            function() {

              var parent = jQuery(this);

              // First hide all the target selectors
              var item = '.'+parent.data('target');
              jQuery(item).hide();

              // then if the parent checkbox is checked shows the target selectors
              if(parent.is(":checked")){
                            jQuery(item).show();
                        }

              jQuery(parent).click(function() {
                    if( parent.is(':checked')) {
                        jQuery(item).fadeIn(300);
                    } else {
                        jQuery(item).fadeOut(300);
                    }
                  });
            }
        );
}


/*
 *
 * This function needs to be global to get reached by the other files
 *
 */
function swal_opacity_on_check() {
  jQuery('.sw-opacityoncheck').each(
            function() {

              var parent = jQuery(this);

              var overlay = '<div class="swal-disabled-overlay"></div>';

              // First hide all the target selectors
              var item = '.'+parent.data('target');
              jQuery(item).fadeTo(200, 0.4);
              jQuery(item+" input,"+item+" select,"+item+" textarea,"+item+" button").attr("readonly", true);
              jQuery(item).prepend(overlay);

              // then if the parent checkbox is checked shows the target selectors
              if(parent.is(":checked")){
                            jQuery(item).fadeTo(200, 1);
                            jQuery(item+" input,"+item+" select,"+item+" textarea,"+item+" button").attr("readonly", false);
                            jQuery(item).find(".swal-disabled-overlay").remove();
                        }

              jQuery(parent).click(function() {
                    if( parent.is(':checked')) {
                        jQuery(item).fadeTo(200, 1);
                        jQuery(item+" input,"+item+" select,"+item+" textarea,"+item+" button").attr("readonly", false);
                        jQuery(item).find(".swal-disabled-overlay").remove();
                    } else {
                        jQuery(item).fadeTo(200, 0.4);
                        jQuery(item+" input,"+item+" select,"+item+" textarea,"+item+" button").attr("readonly", true);
                        jQuery(item).prepend(overlay);
                    }
                  });
            }
        );
}

jQuery(function($){
  $('tr:has(.swal_activate)').addClass('not_active');
  $('.actions select[name^="action"]').append(
    '<option value="swal_bulk_active">' + ajax_auth_object.activate + '</option>' +
    '<option value="swal_bulk_deactive">' + ajax_auth_object.deactivate + '</option>'
  );
});