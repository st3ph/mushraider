jQuery(function($) {
    var $preview;
    var preview = '<div class="widget">';
        preview += '<header class="widget-header">My awesome widget</header>';
        preview += '<div class="widget-content">';
            preview += '<table class="table table-striped responsive widgetRosterIndex">';
                preview += '<thead>';
                    preview += '<tr>';
                        preview += '<th>Name</th>';
                        preview += '<th>Level</th>';
                        preview += '<th>Role</th>';
                        preview += '<th>Classe</th>';
                        preview += '<th>Race</th>';
                        preview += '<th>User</th>';
                    preview += '</tr>';
                preview += '</thead>';
                preview += '<tbody>';
                    var previewRow = '<tr>';
                        previewRow += '<td>Random Character</td>';
                        previewRow += '<td>60</td>';
                        previewRow += '<td>Tank</td>';
                        previewRow += '<td style="color:red">Warrior</td>';
                        previewRow += '<td>Undead</td>';
                        previewRow += '<td>Mush</td>';
                    previewRow += '</tr>';
                    for(i = 0;i < 5;i++) {
                        preview += previewRow;
                    }
                preview += '</tbody>';
            preview += '</table>';
        preview += '</div>';
        preview += '<footer class="widget-footer"><p><a href="http://mushraider.com" target="_blank">MushRaider</a></p></footer>';
    preview += '</div>';
    $preview = $(preview);

    // Apply styles
    applyWidgetStyles($preview);

    $('#addWidget').find('.preview').html($preview);
});