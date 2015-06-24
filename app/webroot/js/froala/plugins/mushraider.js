(function ($) {
    // Add an option for your plugin.
    $.Editable.DEFAULTS = $.extend($.Editable.DEFAULTS, {
        widgets: [],
        widgetsEmpty: false
    }),

    $.Editable.prototype.initMushRaider = function () {
        this.getWidgets();
    },
    $.Editable.prototype.refreshWidgets = function() {
    },

    // Define a toolbar button. It will be available in the buttons option.
    $.Editable.commands = $.extend($.Editable.commands, {
        widget: {
            title: 'Widget',
            icon: 'fa fa-puzzle-piece',
            refresh: $.Editable.prototype.refreshDefault,
            refreshOnShow: $.Editable.prototype.refreshWidgets,
            callback: function(command, widgetId) {
                this.addWidget(command, widgetId);
            },
            undo: false // Enable only if it might affect the UNDO stack
        }
    }), 
    $.Editable.prototype.command_dispatcher = $.extend($.Editable.prototype.command_dispatcher, {
        widget: function(command) {
            var content = '<ul class="fr-dropdown-menu f-widget">';
            if(this.options.widgetsEmpty !== false) {
                content += '<li><a href="#">'+this.options.widgetsEmpty+'</a></li>';
            }else {
                $.each(this.options.widgets, function(index, obj) {
                    content += '<li data-cmd="widget" data-val="'+index+'"><a href="#">'+obj.Widget.title+'</a></li>';
                });          
            }
            content += '</ul>';

            var dropdown = this.buildDropdownButton(command, content, "fr-widget");
            return dropdown;
        }
    });

    // Register your plugin.
    $.Editable.initializers.push($.Editable.prototype.initMushRaider),

    $.Editable.prototype.addWidget = function(command, widgetIndex) {
        var widget = this.options.widgets[widgetIndex].Widget;
        var iframe = '<iframe src="'+site_url+'widget/'+widget.controller+'/'+widget.action+'/'+widget.id+'" width="100%" height="100%" frameborder="0"></iframe>';

        this.insertHTML(iframe, !1);
    };

    $.Editable.prototype.getWidgets = function() {
        var $this = this;
        $.ajax({
            type: 'get',
            url: site_url+'widget/ajax/getList',            
            dataType: 'json',
            async: false,
            success: function(widgetsList) {
                if(typeof widgetsList.msg !== 'undefined') {
                    $this.options.widgetsEmpty = widgetsList.msg;
                }else {
                    $this.options.widgetsEmpty = false;
                    $this.options.widgets = widgetsList.widgets;
                }
            }
        });
    };
})(jQuery);