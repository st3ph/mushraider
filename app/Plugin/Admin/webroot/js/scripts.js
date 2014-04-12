var hideFlashMessage = function() {
    $('.flashMessage').slideUp(function() {
        $(this).remove();
    });
};

var closeModal = function($modal) {
    $modal.dialog('close');
    //$modal.dialog('destroy');
};

var addInList = function(id, title, element, field) {
    var html = '<li>';
        html += '<input type="hidden" name="data[Game]['+field+'][list][]" value="'+id+'">';
        html += '<span>'+title+'</span>';
        html += ' <i class="icon-remove-sign"></i>';
    html += '</li>';
    $(element).append(html);
};

var loadSpectrum = function(element) {
    if($(element).length) {
        var color = $(element).val().replace('#', '');
        $(element).spectrum({
            color: color,
            showPalette: true,
            showSelectionPalette: true,
            localStorageKey: 'mushraider',
            preferredFormat: 'hex6'
        });
    }
}

var UpdatePreviewCanvas = function() {
    var img = this;
    var canvas = document.getElementById('previewcanvas');

    if(typeof canvas === "undefined" || typeof canvas.getContext === "undefined") {
        return;
    }
    canvas.style.display = 'block';

    var context = canvas.getContext('2d');

    var world = new Object();
    world.width = canvas.offsetWidth;
    world.height = canvas.offsetHeight;

    canvas.width = world.width;
    canvas.height = world.height;

    if(typeof img === "undefined") {
        return;
    }

    var WidthDif = img.width - world.width;
    var HeightDif = img.height - world.height;

    var Scale = 0.0;
    if(WidthDif > HeightDif) {
        Scale = world.width / img.width;
    }else {
        Scale = world.height / img.height;
    }
    if(Scale > 1) {
        Scale = 1;
    }

    var UseWidth = Math.floor(img.width * Scale);
    var UseHeight = Math.floor(img.height * Scale);

    var x = Math.floor((world.width - UseWidth) / 2);
    var y = Math.floor((world.height - UseHeight) / 2);

    $('#previewcanvascontainer').find('.currentLogo').hide();
    context.drawImage(img, x, y, UseWidth, UseHeight);
}

jQuery(function($) {
    if($('.flashMessage').length) {
        setTimeout('hideFlashMessage()', 8000);
    }
    
    $('.flashMessage .close').bind('click', function(e) {
        e.preventDefault();
        hideFlashMessage();
    });

    $('table td.actions').on('click', '.delete', function(e) {
        return confirm($(this).data('confirm'));
    });

    $('.tt').tooltip();

    loadSpectrum('.colorpicker');

    /*
    * Games
    */
    $('form select.selectFiller').on('change', function(e) {
        var id = $(this).val();
        if(id.length) {
            var $selectedOption = $(this).find('option:selected');
            var title = $selectedOption.text();
            var listId = $(this).data('list');
            var fieldName = $(this).data('field');
            $selectedOption.remove();           
            addInList(id, title, '#'+listId, fieldName);
        }
    });

    $('form ul.gameFilledList').on('click', 'li i', function(e) {
        var $parent = $(this).parent('li');
        var $select = $parent.parents('.form-group').find('select');
        var option = '<option value="'+$parent.find('input').val()+'">'+$parent.find('span').text()+'</option>';
        $parent.fadeOut(function() {
            $(this).remove();
            $select.append(option);
        });
    });

    var modalView;
    $('form').on('click', '.addObjectToGame', function(e) {
        e.preventDefault();

        var controllerName = $(this).data('controller');
        var listName = $(this).data('list');
        var fieldName = $(this).data('field');

        if(typeof modalView != 'undefined' && modalView.length) {
            closeModal(modalView);            
        }

        $.ajax({
            type: 'get',
            url: site_url+'admin/'+controllerName+'/add',
            success: function(resHtml) {
                // Inject HTML
                modalView = $('<div>');
                modalView.html(resHtml);

                // Add event to catch form submit
                modalView.find('form').on('submit', function(e) {
                    e.preventDefault();

                    // Add loading
                    $(this).find('.submit').prepend(imgLoading);
                    $(this).find('.submit input').attr('disabled', true);                    
                    
                    var datastring = $(this).serialize();
                    $.ajax({
                        type: 'post',
                        url: site_url+'admin/'+controllerName+'/add',
                        data: datastring,
                        dataType: "json",
                        success: function(objectInfos) {
                            closeModal(modalView);                        
                            addInList(objectInfos.id, objectInfos.title, '#'+listName, fieldName);
                        }
                    });
                });

                modalView.dialog({
                    closeOnEscape: true,
                    width: 500,
                    modal: true,
                    draggable: false,
                    open: function(event, ui) {
                        loadSpectrum('.colorpicker');
                    }
                });
            }
        });
    });

    $('.imageupload').on('change', function() {
        if(!( window.File && window.FileReader && window.FileList && window.Blob)) {
            console.log('The File APIs are not fully supported in this browser.');
            return false;
        }

        if(typeof FileReader === "undefined") {
            console.log("Filereader undefined!");
            return false;
        }

        var file = this.files[0];

        if(!( /image/i).test(file.type)) {
            console.log("File is not an image.");
            return false;
        }

        reader = new FileReader();
        reader.onload = function(event) { 
            var img = new Image; 
            img.onload = UpdatePreviewCanvas; 
            img.src = event.target.result;
        }
        reader.readAsDataURL(file);
    });

    /*
    * Stats
    */
    if($('#datatable').length) {
        $('#datatable').dataTable();
    }
});