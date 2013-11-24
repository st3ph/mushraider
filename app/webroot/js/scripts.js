var hideFlashMessage = function() {
    $('.flashMessage').slideUp(function() {
        $(this).remove();
    });
};

var trim = function(string) {
    return string.replace(/^\s+/g,'').replace(/\s+$/g,'');
}

jQuery(function($) {
    if($('.flashMessage').length) {
        setTimeout('hideFlashMessage()', 8000);
    }
    
    $('.flashMessage .close').bind('click', function(e) {
        e.preventDefault();
        hideFlashMessage();
    });

    $('h3.blockToggle').on('click', 'a', function(e) {
        e.preventDefault();

        $(this).parent('h3').next('.hide').slideToggle();
    });

    $('.confirm').on('click', function(e) {
        return confirm($(this).data('confirm'));
    });

    $('.tt').tooltip({

    });

    /*
    * Account
    */
    $('#CharacterGameId').on('change', function(e) {
        var gameId = $(this).val();
        if(gameId.length) {
            $.ajax({
                type: 'get',
                url: site_url+'ajax/getListByGame',
                data: 'game='+gameId,
                success: function(resHtml) {
                    $('#objectsPlaceholder').html(resHtml);
                }
            });
        }else {
            $('#objectsPlaceholder').html('');
        }
    });

    /*
    * Events
    */
    var $EventGame = $('#EventGameId');
    var loadDungeons = function($selectObject) {
        var gameId = $selectObject.val();
        if(gameId.length) {
            $.ajax({
                type: 'get',
                url: site_url+'ajax/getDungeonsByGame',
                data: 'game='+gameId,
                dataType: 'json',
                success: function(dungeons) {
                    var optionsHtml = '';
                    $(dungeons).each(function(id, dungeon) {                        
                        optionsHtml += '<option value="'+dungeon.Dungeon.id+'">'+dungeon.Dungeon.title+'</option>';
                    });
                    $('#EventDungeonId').html(optionsHtml);
                }
            });
        }else {
            $('#EventDungeonId').html('');
        }
    }
    if($EventGame.length) {
        loadDungeons($EventGame);
    }
    $('#EventGameId').on('change', function(e) {
        loadDungeons($EventGame);
    });

    $('#eventSignin').on('change', '#Character', function(e) {
        var characterId = $(this).val();
        if(!characterId.length) {
            $('#EventsRole').find('option[value=""]').attr('selected', true);
        }else {
            $.ajax({
                type: 'get',
                url: site_url+'ajax/getDefaultRole',
                data: 'character='+characterId,
                dataType: 'json',
                success: function(msg) {
                    $('#EventsRole').find('option[value="'+msg.role+'"]').attr('selected', true);     
                }
            });
        }
    });

    $('#eventSignin').on('click', '.btn', function(e) {
        var $characterField = $('#Character');
        var $roleField = $('#EventsRole');
        var $commentField = $('#Comment');
        var $roster = $('#eventRoles');
        var $characterMessage = $characterField.prev('.message');
        var userId = $characterField.data('user');
        var eventId = $characterField.data('event');
        var characterId = $characterField.val();
        var roleId = $roleField.val();
        var signInValue = $(this).data('status');        
        $characterField.removeClass('form-error');
        $characterMessage.removeClass('text-error').removeClass('text-success');
        if(!characterId || !roleId) { // Don't choose character / role but try to signin / signout
            $characterField.addClass('form-error');
            $characterMessage.html($characterField.data('error')).addClass('text-error');
            return;
        }

        $.ajax({
            type: 'get',
            url: site_url+'ajax/eventSignin',
            data: 'character='+characterId+'&signin='+signInValue+'&e='+eventId+'&u='+userId+'&role='+roleId+'&c='+$commentField.val(),
            dataType: 'json',
            success: function(msg) {
                var messageClass = 'label label-'+msg.type;
                var messageText = signInValue == 1?$characterField.data('signin'):$characterField.data('signout');
                if(msg.type == 'important') {
                    messageText = msg.msg;
                    $characterMessage.removeClass('label-info');
                }

                // Remove character from the roster
                $roster.find('tbody li[data-user="'+userId+'"]').remove();

                // Add character to the roster
                if(msg.msg == 'ok' && typeof msg.html != 'undefined' && msg.html.length) {
                    var charStatus = signInValue?'waiting':'rejected';
                    $roster.find('tbody td[data-id="role_'+roleId+'"] .'+charStatus).append(msg.html);                    
                }

                $characterMessage.html(messageText).addClass(messageClass);
            }
        });        
    });

    var editor = $('.wysiwyg').cleditor({
        width: 'auto',
        height: 150,
        controls: "bold italic underline strikethrough | font size strikethrough style | color highlight removeformat | bullets numbering | " +
                  "outdent indent | alignleft center alignright justify | undo redo | link unlink"
    });

    // Validate roster
    $('#eventRoles th').on('click', 'i', function() {
        var $editButton = $(this);
        var $table = $editButton.parents('table');
        var $roleTd = $table.find("td[data-id='"+$editButton.parents('th').data('id')+"']");
        var $waiting = $roleTd.find('.waiting');
        var $validated = $roleTd.find('.validated');
        if($editButton.hasClass('icon-edit')) { // Go Edit mode
            $editButton.removeClass('icon-edit').addClass('icon-save');
            $editButton.removeClass('text-warning').addClass('text-success');
            // Add 'add button' to waiting list
            $waiting.find('li').each(function() {
                $(this).find('.character').prepend('<i class="icon-plus text-success"></i>');
                $(this).addClass('nosort');
            });

            // Add 'remove button' to validated list
            $validated.find('li').each(function() {
                $(this).find('.character').prepend('<i class="icon-minus text-error"></i>');
            });
        }else { // Save
            $imgLoading = $(imgLoading);
            $imgLoading.addClass('pull-right');
            $editButton.after($imgLoading);

            var validatedList = '';
            $validated.find('li').each(function() {
                validatedList += $(this).data('id')+',';
            });

            $.ajax({
                type: 'get',
                url: site_url+'ajax/roster',
                data: 'v='+validatedList+'&r='+$roleTd.data('id')+'&e='+$table.data('id'),
                success: function(msg) {                
                    $editButton.next('img').remove();

                    $editButton.removeClass('icon-save').addClass('icon-edit');
                    $editButton.removeClass('text-success').addClass('text-warning');
                }
            }); 

            $waiting.find('li').each(function() {
                $(this).find('.character  i').remove();
                $(this).removeClass('nosort');
            });

            // Remove 'add button' to validated list
            $validated.find('li').each(function() {
                $(this).find('.character  i').remove();
            });
        }
    });

    $('#eventRoles td').on('click', 'i', function() {
        var $button = $(this);
        var $table = $button.parents('table');
        var $roleTh = $table.find("th[data-id='"+$button.parents('td').data('id')+"']");
        var $roleTd = $button.parents('td');
        var $waiting = $roleTd.find('.waiting');
        var $validated = $roleTd.find('.validated');

        if($button.hasClass('icon-plus')) { // Add player to roster
            // Check if there is a room left for this role
            if(parseInt($roleTh.find('.max').text()) > $validated.find('li').length) {
                var $player = $(this).parents('li');
                var $newPlayer = $player.clone();
                $newPlayer.find('i').remove();
                $newPlayer.find('.character').prepend('<i class="icon-minus text-error"></i>');
                $validated.append($newPlayer);
                $player.remove();

                var currentPlayers = parseInt($roleTh.find('.current').text());
                $roleTh.find('.current').text((currentPlayers + 1));
            }else {
                alert($roleTd.data('full'));
            }
        }else { // Remove player from roster
            $player = $(this).parents('li');
            var $newPlayer = $player.clone();
            $newPlayer.find('i').remove();
            $newPlayer.find('.character').prepend('<i class="icon-plus text-success"></i>');
            $waiting.append($newPlayer);
            $player.remove();

            var currentPlayers = parseInt($roleTh.find('.current').text());
            currentPlayers = currentPlayers > 0?currentPlayers - 1:0;
            $roleTh.find('.current').text(currentPlayers);
        }
    });

    $('#eventRoles .sortWaiting').sortable({
        connectWith: "#eventRoles .sortWaiting",
        placeholder: "sortable-placeholder",
        cursor: "move",
        containment: "#eventRoles",
        handle: '.icon-move',
        receive: function(event, ui) {
            console.log(ui.item);
            var characterId = ui.item.data('id');
            var roleId = ui.item.parents('td').data('id');
            var eventId = ui.item.parents('table').data('id');
            
            $.ajax({
                type: 'get',
                url: site_url+'ajax/updateRosterChar',
                data: 'c='+characterId+'&r='+roleId+'&e='+eventId,
                success: function(msg) {
                }
            });
        }
    }).disableSelection();
});