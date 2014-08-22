var hideFlashMessage = function() {
    $('.flashMessage').slideUp(function() {
        $(this).remove();
    });
};

var hideUpdateMessage = function() {
    $('.updateMessage').slideUp(function() {
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
        $(element).each(function() {
            var color = $(this).val().replace('#', '');
            $(this).spectrum({
                color: color,
                showPalette: true,
                showSelectionPalette: true,
                showInput: true,
                localStorageKey: 'mushraider',
                preferredFormat: 'hex6'
            });
        });
    }
};

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
};

var randomString = function(length) {
    var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var result = '';
    for(var i = length; i > 0; --i) {
        result += chars[Math.round(Math.random() * (chars.length - 1))];
    }
    return result;
};

var getAjaxProgress = function() {
    console.log('progress ?');

    $.ajax({
        type: 'get',
        url: site_url+'admin/ajax/ajaxProgress',
        data: '',
        success: function(progress) {
            console.log('progress : '+progress);
            var $progressBar = $('#progressBar');
            $progressBar.find('span').text(progress+'%');
            $progressBar.find('.bar').css('width', progress+'%');

            setTimeout('getAjaxProgress()', 1000);
        }
    });
};

jQuery(function($) {
    if($('.flashMessage').length) {
        var timer = $('.flashMessage').hasClass('alert-update')?30000:8000;        
        setTimeout('hideFlashMessage()', timer);
    }
    
    $('.flashMessage .close').bind('click', function(e) {
        e.preventDefault();
        hideFlashMessage();
    });

    if($('.updateMessage').length) {
        setTimeout('hideUpdateMessage()', 30000);
    }

    $('.updateMessage .close').bind('click', function(e) {
        e.preventDefault();
        hideUpdateMessage();
    });

    $('table td.actions').on('click', '.delete', function(e) {
        return confirm($(this).data('confirm'));
    });

    $('.tt').tooltip();

    loadSpectrum('.colorpicker');

    $('.sortableTbody').sortable({
        placeholder: "sortable-placeholder",
        cursor: "move",
        containment: ".sortableTbody",
        handle: '.icon-move',
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        stop: function(event, ui) {   
            var $thisTable = $(this).parents('table');
            var $imgLoading = $(imgLoading);
            var $jsonMsg = $thisTable.next('.jsonMsg');
            var model = $(this).data('model');
            var sorted = $(this).sortable('serialize');

            $jsonMsg.html($imgLoading);

            $.ajax({
                type: 'get',
                url: site_url+'admin/ajax/updateOrder',
                data: 'm='+model+'&'+sorted,
                dataType: 'json',
                success: function(json) {
                    $jsonMsg.html('<span class="text-'+json.type+'">'+json.msg+'</span>');
                    setTimeout(
                        function() {
                            $jsonMsg.html('');
                        }, 5000
                    );
                }
            });
        }
    }).disableSelection();

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

                var $dungeonSizeInputs = modalView.find('.dungeonSizeInputs');

                // Add event to catch form submit
                modalView.find('form').on('submit', function(e) {
                    e.preventDefault();

                    if($dungeonSizeInputs.length && $dungeonSizeInputs.find('#DungeonRaidssizeId').val() == '' && $dungeonSizeInputs.find('#DungeonCustomraidssize').val() == '') {
                        $dungeonSizeInputs.find('#DungeonRaidssizeId').addClass('form-error');
                        $dungeonSizeInputs.find('#DungeonCustomraidssize').addClass('form-error');
                        $dungeonSizeInputs.append('<div class="text-error">'+$dungeonSizeInputs.data('error')+'</div>');
                    }else {
                        // Remove dungeon size check errors
                        $dungeonSizeInputs.find('#DungeonRaidssizeId').removeClass('form-error');
                        $dungeonSizeInputs.find('#DungeonCustomraidssize').removeClass('form-error');
                        $dungeonSizeInputs.find('.text-error').remove();

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
                    }
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

    $('#importGame').on('click', function(e) {
        e.preventDefault();

        var $gameSlug = $('#GameSlug');
        var gameSlug = $('#GameSlug').val();
        var $progressBar = $('#progressBar');
        if(!gameSlug.length) {
            $gameSlug.addClass('form-error');
        }else {
            $gameSlug.parents('.importForm').fadeOut(function() {
                $progressBar.find('span').text('1%');
                $progressBar.find('.bar').css('width', '1%');
                $progressBar.fadeIn();
                $(this).remove();

                $.ajax({
                    type: 'get',
                    url: site_url+'admin/ajax/importGame',
                    data: 'slug='+gameSlug,
                    dataType: "json",
                    success: function(json) {
                        $progressBar.html('<span class="text-'+json.type+'">'+json.msg+'</span>');
                        if(json.type == 'success') {
                            window.location = site_url+'admin/games';
                        }
                    }
                });

                // Second ajax call every second to check the script progress
                setTimeout('getAjaxProgress()', 100);
            });
        }
    });

    $('.updateGame').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var gameSlug = $(this).data('slug');
        var $imgLoading = $(imgLoading);
        $button.before($imgLoading);
        $.ajax({
            type: 'get',
            url: site_url+'admin/ajax/importGame',
            data: 'slug='+gameSlug,
            dataType: "json",
            success: function(json) {
                $imgLoading.remove();
                var $msg = $('<div class="text-'+json.type+'">'+json.msg+'</div>');
                $button.parent('td').append($msg);
                setTimeout(
                    function() {
                        $msg.remove();
                        window.location = site_url+'admin/games';
                    }, 5000
                );
            }
        });
    });

    $('#checkUpdates').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $gamesList = $('.gamesList');
        $button.find('i').addClass('loadingAnimation');
        $.ajax({
            type: 'get',
            url: site_url+'admin/ajax/checkUpdates',
            data: '',
            dataType: "json",
            success: function(json) {
                $.each(json, function(i, item) {
                    $gamesList.find('.actions a[data-slug="'+item+'"]').addClass('btn-success');
                });
                $button.find('i').removeClass('loadingAnimation');
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

    /*
    * Users
    */
    if($('.wysiwyg').length) {
        var editorObject = $('.wysiwyg').cleditor({
            width: 'auto',
            height: 250,
            controls: "bold italic underline strikethrough | font size strikethrough style | color highlight removeformat | bullets numbering | " +
                      "outdent indent | alignleft center alignright justify | undo redo | link unlink"
        });
    }

    /*
    * Settings
    */
    $('#SettingEnabled').on('change', function(e) {
        if($(this).is(':checked')) {
            $('#apiModules').show();
        }else {
            $('#apiModules').hide();
        }
    });

    $('#apiPrivateKey').on('click', '.refresh', function(e) {
        e.preventDefault();

        var privateKey = randomString(32);
        $('#apiPrivateKey').find('.privateKey').text(privateKey);
        $('#SettingPrivateKey').val(privateKey);
    });

    /*
    * Dungeons
    */
    if($('.dungeonSizeInputs').length) {
        var $dungeonSizeInputs = $('.dungeonSizeInputs');
        var $dungeonSizeFrom = $dungeonSizeInputs.parents('form');

        $dungeonSizeFrom.on('submit', function(e) {
            if($dungeonSizeInputs.find('#DungeonRaidssizeId').val() == '' && $dungeonSizeInputs.find('#DungeonCustomraidssize').val() == '') {
                e.preventDefault();
                $dungeonSizeInputs.find('#DungeonRaidssizeId').addClass('form-error');
                $dungeonSizeInputs.find('#DungeonCustomraidssize').addClass('form-error');
                $dungeonSizeInputs.append('<div class="text-error">'+$dungeonSizeInputs.data('error')+'</div>');
            }else {
                $dungeonSizeInputs.find('#DungeonRaidssizeId').removeClass('form-error');
                $dungeonSizeInputs.find('#DungeonCustomraidssize').removeClass('form-error');
                $dungeonSizeInputs.find('.text-error').remove();
            }
        });  
    }

    /*
    * Widgets
    */
    $('#WidgetType').on('change', function() {
        $(this).parents('form').submit();
    });
});