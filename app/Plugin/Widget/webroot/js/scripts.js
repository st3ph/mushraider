var applyWidgetStyles = function($widget) {
    $bgColor = $('#WidgetParamsBgColor');
    $textColor = $('#WidgetParamsTextColor');
    $linkColor = $('#WidgetParamsLinkColor');
    $headerTextColor = $('#WidgetParamsHeaderTextColor');
    $headerBgColor = $('#WidgetParamsHeaderBgColor');
    $widget.css({
        'background-color': $bgColor.val(),
        'color': $textColor.val()
    });
    $widget.find('a').css({
        'color': $linkColor.val()
    });
    $widget.find('.widget-header').css({
        'color': $headerTextColor.val(),
        'background-color': $headerBgColor.val(),
    });
};

jQuery(function($) {
    $('#addWidget').find('.customize').on('change', 'input', function() {
        applyWidgetStyles($('#addWidget').find('.preview').find('.widget'));
    });

    // Make sure we are in iframe
    if($('.widget .widget-content').length) {
        if(parent !== window) {
            var $restrictedDomain = $('#iframeDomainRestriction');
            var restrictedDomainValue = $restrictedDomain.data('domain');
            if(restrictedDomainValue.length) {
                if(document.referrer.indexOf(restrictedDomainValue) == -1) {
                    $('.widget-content').html('<p class="text-center text-error">'+$restrictedDomain.data('msg')+'</p>');
                }
            }
        }
    }
});