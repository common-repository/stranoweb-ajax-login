(function($) {

/*
 *
 * Create 'keyup_event' tinymce plugin
 *
 */
tinymce.PluginManager.add('keyup_event', function(editor, url) {

    // Create keyup event
    editor.on('keyup', function(e) {

        swalEnableButton();
    });
});

/*
 *
 * Enable the buttons
 *
 */
function swalEnableButton() {
    $('button.swal-add-loader,input[type="submit"]').removeAttr("disabled").removeClass('disabled');
  }


})(jQuery);