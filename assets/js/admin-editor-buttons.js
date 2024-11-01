(function() {
    tinymce.create("tinymce.plugins.swal_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            //add new button    
            ed.addButton("swal", {
                text: 'SW Ajax Login',
                 icon: false,
                 type: 'menubutton',
                 menu: [
                       {
                        text: 'Full Login form',
                        onclick: function() {
                           ed.insertContent('[swal_show_login_form]');
                                  }
                        },
                        {
                        text: 'Login form only',
                        onclick: function() {
                           ed.insertContent('[swal_show_login_form_only hidelink="false"]');
                                  }
                       },
                       {
                        text: 'Full Register form',
                        onclick: function() {
                           ed.insertContent('[swal_show_register_form]');
                                 }
                       },
                       {
                        text: 'Register form only',
                        onclick: function() {
                           ed.insertContent('[swal_show_register_form_only hidelink="false"]');
                                 }
                       },
                       {
                        text: 'Full Forgot password form',
                        onclick: function() {
                           ed.insertContent('[swal_show_forgot_password_form]');
                                 }
                       },
                       {
                        text: 'Forgot password form only',
                        onclick: function() {
                           ed.insertContent('[swal_show_forgot_password_form_only hidelink="false"]');
                                 }
                       },
                       {
                        text: 'Full Reset password form',
                        onclick: function() {
                           ed.insertContent('[swal_show_reset_password_form]');
                                 }
                       },
                       {
                        text: 'Logout button',
                        onclick: function() {
                           ed.insertContent('[swal_show_logout_form]');
                                 }
                       },
                       {
                        text: 'Login item',
                        onclick: function() {
                           ed.insertContent('[swal_display_login_item hideavatar="false" openlogout="true" class="" loggedintext=""]');
                                 }
                       },
                       {
                        text: 'Social Login buttons',
                        onclick: function() {
                           ed.insertContent('[swal_socials_login_buttons]');
                                 }
                       }
                       ]
            });


        },

        createControl : function(n, cm) {
            return null;
        },

    });

    tinymce.PluginManager.add("swal_button_plugin", tinymce.plugins.swal_button_plugin);
})();