var hideFlashMessage = function() {
    $('.flashMessage').slideUp(function() {
        $(this).remove();
    });
};

jQuery(function($) {
    if($('.flashMessage').length) {
        setTimeout('hideFlashMessage()', 8000);
    }
    
    $('.flashMessage .close').bind('click', function(e) {
        e.preventDefault();
        hideFlashMessage();
    });
});