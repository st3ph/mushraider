jQuery(function($) {
    var $preview;
    var preview = '<div class="widget">';
        preview += '<header class="widget-header">My awesome widget</header>';
        preview += '<div class="widget-content">';
            preview += '<ul class="unstyled widgetEventsIndex">';
                var previewRow = '<li>';
                    previewRow += '<div class="row-fluid">';
                        previewRow += '<div class="span1">';
                            previewRow += '<img src="/img/game-logo.png" />';
                        previewRow += '</div>';
                        previewRow += '<div class="span11">';
                            previewRow += '<strong>Super fun event</strong><br />';
                            previewRow += 'Awesome dungeon';
                            previewRow += '<div class="date">in 2 days 19h</div>';
                        previewRow += '</div>';
                    previewRow += '</div>';
                previewRow += '</li>';
                for(i = 0;i < 5;i++) {
                    preview += previewRow;
                }
            preview += '</ul>';
        preview += '</div>';
        preview += '<footer class="widget-footer"><p><a href="http://mushraider.com" target="_blank">MushRaider</a></p></footer>';
    preview += '</div>';
    $preview = $(preview);

    // Apply styles
    applyWidgetStyles($preview);

    $('#addWidget').find('.preview').html($preview);
});