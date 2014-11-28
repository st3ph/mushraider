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
        'html':true
    });


    $('.startDate').datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        minDate: new Date(),
        onClose: function( selectedDate ) {
            $(this).parents('.dates').find('.endDate').datepicker( "option", "minDate", selectedDate );
        }
    });
    $('.endDate').datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        onClose: function( selectedDate ) {
            $(this).parents('.dates').find('.startDate').datepicker( "option", "maxDate", selectedDate );
        }
    });


    /*
    * Account
    */
    $('#CharacterGameId').on('change', function(e) {
        $imgLoading = $(imgLoading);        

        var $inputObj = $(this);
        var $submitObj = $inputObj.parents('form').find('input[type="submit"]');
        var gameId = $inputObj.val();
        if(gameId.length) {
            $inputObj.after($imgLoading);
            $submitObj.prop('disabled', true);

            $.ajax({
                type: 'get',
                url: site_url+'ajax/getListByGame',
                data: 'game='+gameId,
                dataType: 'html',
                success: function(resHtml) {
                    $('#objectsPlaceholder').html(resHtml);
                    $submitObj.prop('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var errorStr = $inputObj.data('error');
                    if(textStatus != null) {
                        errorStr += ' : '+textStatus;
                    }
                    if(errorThrown != null) {
                        errorStr += ' ('+textStatus+')';
                    }
                    $inputObj.after('<div class="text-error">'+errorStr+'</div>');
                }
            });

            $imgLoading.remove();
        }else {
            $('#objectsPlaceholder').html('');
        }
    });

    $('#absenceTab').on('click', '.edit', function(e) {
        e.preventDefault();

        $this = $(this);
        $row = $this.parents('tr');
        $formRow = $this.parents('tbody').find('.addForm');
        bgColorClass = 'bg-primary';

        $formRow.find('td').removeClass(bgColorClass);
        $formRow.find('input').not(':submit').val('');
        $row.parents('tbody').find('.overlay').remove();

        // clean up
        $formRow.find('.start').find('input').val($row.find('.start').text()).removeClass('form-error');
        $formRow.find('.end').find('input').val($row.find('.end').text()).removeClass('form-error');
        $formRow.find('.comment').find('input').val($row.find('.comment').text()).removeClass('form-error');
        $formRow.find('#AvailabilityId').val($this.data('id'));
        $formRow.find('.error-message').remove();
        $formRow.find('td').addClass(bgColorClass);

        $overlayButton = $('<button>').addClass('btn btn-danger').append('<span class="icon-remove"></span>');
        $overlay = $('<div>').addClass('overlay text-center');
        $row.find('td').css({position: 'relative'}).append($overlay);
        $row.find('td:last-child').find('.overlay').append($overlayButton);

        $overlayButton.on('click', function(e) {
            $formRow.find('td').removeClass(bgColorClass);
            $formRow.find('input').not(':submit').val('');
            $row.find('.overlay').remove();
        });
    });

    /*
    * Events
    */
    $("#createEvent input").datepicker({
        defaultDate: "+1d",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        minDate: new Date()
    });    

    $("#createEvent").on('click', 'button', function(e) {
        e.preventDefault();

        var datePicked = $(this).prev('input').val();
        if(!datePicked.length) {
            $(this).prev('input').addClass('form-error').focus();
        }else {
            var dates = datePicked.split('/');
            window.location = site_url+'events/add/'+dates[2]+'-'+dates[1]+'-'+dates[0];
        }
    });

    $('#filterEvents').on('change', 'select', function(e) {
        $imgLoading = $(imgLoading);
        $(this).after($imgLoading);
        var gameId = $(this).val();
        $.ajax({
            type: 'get',
            url: site_url+'ajax/filterEvents',
            data: 'game='+gameId,
            success: function() {
                window.location = site_url+'events';
            }
        });

        $imgLoading.remove();
    });


    var $EventGame = $('#EventGameId');
    var loadDungeons = function($selectObject, selectedObject) {
        var $imgLoading = $(imgLoading);        
        var gameId = $selectObject.val();
        var $dungeonObj = $('#EventDungeonId');
        var dungeonSelected = typeof(selectedObject) != 'undefined'?selectedObject:0;

        if(!dungeonSelected) {
            dungeonSelected = $dungeonObj.data('selected');
        }

        if(gameId.length) {
            $selectObject.after($imgLoading);

            $.ajax({
                type: 'get',
                url: site_url+'ajax/getDungeonsByGame',
                data: 'game='+gameId,
                dataType: 'json',
                success: function(dungeons) {
                    var optionsHtml = '';
                    $(dungeons).each(function(id, dungeon) {
                        optionsHtml += '<option value="'+dungeon.Dungeon.id+'" '+(dungeonSelected == dungeon.Dungeon.id?'selected="selected"':'')+'>'+dungeon.Dungeon.title+'</option>';
                    });
                    $dungeonObj.html(optionsHtml);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var errorStr = $selectObject.data('error');
                    if(textStatus != null) {
                        errorStr += ' : '+textStatus;
                    }
                    if(errorThrown != null) {
                        errorStr += ' ('+textStatus+')';
                    }
                    $selectObject.after('<div class="text-error">'+errorStr+'</div>');
                }
            });

            $imgLoading.remove();
        }else {
            $dungeonObj.html('');
        }
    }
    if($EventGame.length) {
        loadDungeons($EventGame);
    }
    $EventGame.on('change', function(e) {
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

                // Remove user from "bad kitties"
                $('#badKitties').find('span[data-user="'+userId+'"]').fadeOut(function() {
                    $(this).remove();
                });

                $characterMessage.html(messageText).addClass(messageClass);
            }
        });        
    });

    if($('.wysiwyg').length) {
        var editorObject = $('.wysiwyg').cleditor({
            width: 'auto',
            height: 150,
            controls: "bold italic underline strikethrough | font size strikethrough style | color highlight removeformat | bullets numbering | " +
                      "outdent indent | alignleft center alignright justify | undo redo | link unlink"
        });
    }

    if($('.wysiwyg-tall').length) {
        var editorObject = $('.wysiwyg-tall').cleditor({
            width: 'auto',
            height: 500,
            controls: "bold italic underline strikethrough | font size strikethrough style | color highlight removeformat | bullets numbering | " +
                      "outdent indent | alignleft center alignright justify | undo redo | link unlink"
        });
    }

    // Validate roster
    $('#eventRoles th').on('click', '.badge', function() {
        var $editButton = $(this);
        var $editButtonI = $(this).find('i');
        var $table = $editButton.parents('table');
        var $roleTd = $table.find("td[data-id='"+$editButton.parents('th').data('id')+"']");
        var $waiting = $roleTd.find('.waiting');
        var $validated = $roleTd.find('.validated');
        var $refused = $roleTd.find('.refused');
        if($editButtonI.hasClass('icon-edit')) { // Go Edit mode
            $editButton.removeClass('badge-warning').addClass('badge-success');
            $editButtonI.removeClass('icon-edit').addClass('icon-save');
            // Add 'add button' and 'refused button' to waiting list
            $waiting.find('li').each(function() {
                $(this).find('.character').prepend('<i class="icon-plus text-success"></i>');
                $(this).find('.character').prepend('<i class="icon-minus-sign text-error"></i>');
                $(this).addClass('nosort');
            });

            // Add 'add button' to refused list
            $refused.find('li').each(function() {
                $(this).find('.character').prepend('<i class="icon-plus text-success"></i>');
                $(this).addClass('nosort');
            });

            // Add 'remove button' to validated list
            $validated.find('li').each(function() {
                $(this).find('.character').prepend('<i class="icon-minus text-error"></i>');
                $(this).addClass('nosort');
            });
        }else { // Save
            $imgLoading = $(imgLoading);
            $imgLoading.addClass('pull-right');
            $editButton.after($imgLoading);

            var validatedList = '';
            $validated.find('li').each(function() {
                validatedList += $(this).data('id')+',';
            });

            var refusedList = '';
            $refused.find('li').each(function() {
                refusedList += $(this).data('id')+',';
            });

            $.ajax({
                type: 'get',
                url: site_url+'ajax/roster',
                data: 'v='+validatedList+'&refused='+refusedList+'&r='+$roleTd.data('id')+'&e='+$table.data('id'),
                success: function(msg) {                
                    $editButton.next('img').remove();

                    $editButton.removeClass('badge-success').addClass('badge-warning');
                    $editButtonI.removeClass('icon-save').addClass('icon-edit');
                }
            }); 

            $waiting.find('li').each(function() {
                $(this).find('.character  i').remove();
                $(this).removeClass('nosort');
            });

            // Remove 'add button' to validated list
            $validated.find('li').each(function() {
                $(this).find('.character  i').remove();
                $(this).removeClass('nosort');
            });

            // Remove 'add button' to refused list
            $refused.find('li').each(function() {
                $(this).find('.character  i').remove();
                $(this).removeClass('nosort');
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
        var $refused = $roleTd.find('.refused');

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
        }else if($button.hasClass('icon-minus')) { // Remove player from roster
            $player = $(this).parents('li');
            var $newPlayer = $player.clone();
            $newPlayer.find('i').remove();
            $newPlayer.find('.character').prepend('<i class="icon-plus text-success"></i>');
            $newPlayer.find('.character').prepend('<i class="icon-minus-sign text-error"></i>');
            $waiting.append($newPlayer);
            $player.remove();

            var currentPlayers = parseInt($roleTh.find('.current').text());
            currentPlayers = currentPlayers > 0?currentPlayers - 1:0;
            $roleTh.find('.current').text(currentPlayers);
        }else if($button.hasClass('icon-minus-sign')) { // Refused player from roster
            $player = $(this).parents('li');
            var $newPlayer = $player.clone();
            $newPlayer.find('i').remove();
            $newPlayer.find('.character').prepend('<i class="icon-plus text-success"></i>');
            $refused.append($newPlayer);
            $player.remove();
        }
    });

    $('#eventRoles .sortWaiting').sortable({
        connectWith: "#eventRoles .sortWaiting",
        placeholder: "sortable-placeholder",
        cursor: "move",
        containment: "#eventRoles",
        handle: '.icon-move',
        receive: function(event, ui) {            
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

    $('#EventTimeInvitationHour, #EventTimeInvitationMin').on('change', function() {
        var $EventTimeInvitationHour = $('#EventTimeInvitationHour');
        var $EventTimeInvitationMin = $('#EventTimeInvitationMin');
        var $EventTimeStartHour = $('#EventTimeStartHour');
        var $EventTimeStartMin = $('#EventTimeStartMin');
        var invitationHour = $EventTimeInvitationHour.val();
        var invitationMin = $EventTimeInvitationMin.val();
        var startHour = $EventTimeStartHour.val();
        var startMin = $EventTimeStartMin.val();
        if(parseInt(startHour) < parseInt(invitationHour)) {
            $EventTimeStartHour.find('option[value="'+invitationHour+'"]').prop('selected', true);
            if(parseInt(startMin) < parseInt(invitationMin)) {
                $EventTimeStartMin.find('option[value="'+invitationMin+'"]').prop('selected', true);
            }
        }else if(parseInt(startHour) >= parseInt(invitationHour) && parseInt(startMin) < parseInt(invitationMin)) {
            $EventTimeStartMin.find('option[value="'+invitationMin+'"]').prop('selected', true);
        }
    });

    $('#EventTimeStartHour, #EventTimeStartMin').on('change', function() {
        var $EventTimeInvitationHour = $('#EventTimeInvitationHour');
        var $EventTimeInvitationMin = $('#EventTimeInvitationMin');
        var $EventTimeStartHour = $('#EventTimeStartHour');
        var $EventTimeStartMin = $('#EventTimeStartMin');
        var invitationHour = $EventTimeInvitationHour.val();
        var invitationMin = $EventTimeInvitationMin.val();
        var startHour = $EventTimeStartHour.val();
        var startMin = $EventTimeStartMin.val();
        if(parseInt(startHour) < parseInt(invitationHour)) {
            $EventTimeInvitationHour.find('option[value="'+startHour+'"]').prop('selected', true);
            if(parseInt(invitationHour) < parseInt(startHour)) {
                $EventTimeInvitationMin.find('option[value="'+startMin+'"]').prop('selected', true);
            }
        }else if(parseInt(invitationHour) >= parseInt(startHour) && parseInt(startMin) < parseInt(invitationMin)) {
            $EventTimeInvitationMin.find('option[value="'+startMin+'"]').prop('selected', true);
        }
    });

    // Templating
    $('#createTemplate').on('click', function(e) {
        e.preventDefault();

        var $tpl = $('#tplName');
        $tpl.fadeIn();
    });

    $('#tplName').on('click', '.text-error', function(e) {
        $('#tplName').fadeOut();
    });

    $('#tplName').on('click', '.text-success', function(e) {
        var tplName = $('#tplName input').val();
        var eventId = $('#tplName').data('event');
        if(tplName.length > 1) {
            $.ajax({
                type: 'get',
                url: site_url+'ajax/copyEvent',
                data: 'e='+eventId+'&name='+tplName,
                success: function(msg) {
                    $('#tplName').fadeOut();
                }
            });            
        }else {
            $('#tplName input').addClass('form-error');
        }
    });
    
    $('#loadTemplate').on('click', function(e) {
        e.preventDefault();

        var $tpl = $('#tplList');
        $(this).fadeOut(function() {
            $tpl.fadeIn();
        });
    });

    $('#TemplateList').on('change', function() {
        var $tplList = $(this);
        var $imgLoading = $(imgLoading);
        var templateId = $(this).val();

        if(templateId.length && templateId > 0) {
            $tplList.after($imgLoading);
            $.ajax({
                type: 'get',
                url: site_url+'ajax/loadTemplate',
                data: 't='+templateId,
                dataType: 'json',
                success: function(json) {                    
                    if(json.type == 'error') {
                        $tplList.addClass('form-error');
                        alert(json.msg);
                    }else {
                        $('#EventTitle').val(json.msg.EventsTemplate.event_title);
                        $('#EventDescription').val(json.msg.EventsTemplate.event_description);
                        editorObject[0].updateFrame();
                        $('#EventGameId').val(json.msg.EventsTemplate.game_id);
                        loadDungeons($EventGame, json.msg.EventsTemplate.dungeon_id);
                        if(json.msg.EventsTemplatesRole.length > 0) {
                            for(var i = json.msg.EventsTemplatesRole.length - 1; i >= 0; i--) {
                                $('#EventRoles'+json.msg.EventsTemplatesRole[i].raids_role_id).val(json.msg.EventsTemplatesRole[i].count);
                            };
                        }
                        $('#EventCharacterLevel').val(json.msg.EventsTemplate.character_level);
                        if(json.msg.EventsTemplate.time_invitation != null) {
                            var invitationTime = json.msg.EventsTemplate.time_invitation.split(' ');
                            var invitationTimes = invitationTime[1].split(':');
                            $('#EventTimeInvitationHour').val(invitationTimes[0]);
                            $('#EventTimeInvitationMin').val(invitationTimes[1]);
                            var startTime = json.msg.EventsTemplate.time_start.split(' ');
                            var startTimes = startTime[1].split(':');
                            $('#EventTimeStartHour').val(startTimes[0]);
                            $('#EventTimeStartMin').val(startTimes[1]);
                        }
                    }
                    $imgLoading.remove();
                }
            });         
        }
    });

    $('#EventAddForm').on('submit', function(e) {
        if($('#EventTemplate').is(':checked')) {
            var inputVal = $(this).find('.tplName').val();
            if(!inputVal.length) {
                $(this).find('.tplName').addClass('form-error');
                e.preventDefault();
            }
        }
    });

    /*
    * Responsive    
    */
    if(window.innerWidth < 800) {
        $daysList = '<ul id="calendarResponsive" class="unstyled">';
        $calendar = $('#calendar');
        $calendar.find('table td.day').each(function() {
            $daysList += '<li class="calendarRow">'+$(this).html()+'</li>';
        });
        $daysList += '</ul>';

        $calendar.find('table.dates').remove();
        $calendar.after($daysList);
    }

    /*
    * Lightbox
    */
    // ACTIVITY INDICATOR
    var activityIndicatorOn = function() {
        $( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
    },
    activityIndicatorOff = function() {
        $( '#imagelightbox-loading' ).remove();
    },

    // OVERLAY
    overlayOn = function() {
        $( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
    },
    overlayOff = function() {
        $( '#imagelightbox-overlay' ).remove();
    },

    // CLOSE BUTTON
    closeButtonOn = function( instance ) {
        $( '<button type="button" id="imagelightbox-close" title="Close"></button>' ).appendTo( 'body' ).on( 'click touchend', function(){ $( this ).remove(); instance.quitImageLightbox(); return false; });
    },
    closeButtonOff = function() {
        $( '#imagelightbox-close' ).remove();
    },

    // CAPTION
    captionOn = function() {
        var description = $( 'a[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"] img' ).attr( 'alt' );
        if( description.length > 0 )
            $( '<div id="imagelightbox-caption">' + description + '</div>' ).appendTo( 'body' );
    },
    captionOff = function() {
        $( '#imagelightbox-caption' ).remove();
    },

    // NAVIGATION
    navigationOn = function( instance, selector ) {
        var images = $( selector );
        if( images.length ) {
            var nav = $( '<div id="imagelightbox-nav"></div>' );
            for( var i = 0; i < images.length; i++ )
                nav.append( '<button type="button"></button>' );

            nav.appendTo( 'body' );
            nav.on( 'click touchend', function(){ return false; });

            var navItems = nav.find( 'button' );
            navItems.on( 'click touchend', function() {
                var $this = $( this );
                if( images.eq( $this.index() ).attr( 'href' ) != $( '#imagelightbox' ).attr( 'src' ) )
                    instance.switchImageLightbox( $this.index() );

                navItems.removeClass( 'active' );
                navItems.eq( $this.index() ).addClass( 'active' );

                return false;
            })
            .on( 'touchend', function(){ return false; });
        }
    },
    navigationUpdate = function( selector ) {
        var items = $( '#imagelightbox-nav button' );
        items.removeClass( 'active' );
        items.eq( $( selector ).filter( '[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"]' ).index( selector ) ).addClass( 'active' );
    },
    navigationOff = function() {
        $( '#imagelightbox-nav' ).remove();
    },

    // ARROWS
    arrowsOn = function( instance, selector ) {
        var $arrows = $( '<button type="button" class="imagelightbox-arrow imagelightbox-arrow-left"></button><button type="button" class="imagelightbox-arrow imagelightbox-arrow-right"></button>' );
        $arrows.appendTo( 'body' );
        $arrows.on( 'click touchend', function( e ) {
            e.preventDefault();

            var $this   = $( this ),
                $target = $( selector + '[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"]' ),
                index   = $target.index( selector );

            if( $this.hasClass( 'imagelightbox-arrow-left' ) ) {
                index = index - 1;
                if( !$( selector ).eq( index ).length )
                    index = $( selector ).length;
            }else {
                index = index + 1;
                if( !$( selector ).eq( index ).length )
                    index = 0;
            }

            instance.switchImageLightbox( index );
            return false;
        });
    },
    arrowsOff = function(){
        $( '.imagelightbox-arrow' ).remove();
    };    

    var selectorF = '.lb';
    if($( selectorF ).length) {
        var instanceF = $( selectorF ).imageLightbox({
            onStart:        function() { overlayOn(); closeButtonOn( instanceF ); arrowsOn( instanceF, selectorF ); },
            onEnd:          function() { overlayOff(); captionOff(); closeButtonOff(); arrowsOff(); activityIndicatorOff(); },
            onLoadStart:    function() { captionOff(); activityIndicatorOn(); },
            onLoadEnd:      function() { captionOn(); activityIndicatorOff(); $( '.imagelightbox-arrow' ).css( 'display', 'block' ); }
        });
    }
});