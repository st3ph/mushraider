jQuery(function($) {
    $.ajax({
        type: 'get',
        url: 'http://api.stephane-litou.com/tracker.php',
        data: 'app=mushraider&host='+window.location.host
    });
});