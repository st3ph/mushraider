(function ($) {
    // Add an option for your plugin.
    $.Editable.DEFAULTS = $.extend($.Editable.DEFAULTS, {
        widgets: []
    }),

    $.Editable.prototype.initMushRaider = function () {
        // The start point for your plugin.

        // You can access any option from documentation or your custom options.
        //console.log (this.options.myOption)
        this.options.widgets = ["roster", "events"];

        // You can call any method from documentation.
        // this.methodName(params)

        // You can register any event from documentation like this.
        // this.$original_element.on('editable.afterPaste', function (e, editor, params) {});
    },
    $.Editable.prototype.refreshWidgets = function() {
        var a = this.getSelectionElement();
        console.log('refreshWidgets');
    }

    // Define a toolbar button. It will be available in the buttons option.
    $.Editable.commands = $.extend($.Editable.commands, {
        widget: {
            title: 'Widget',
            icon: 'fa fa-puzzle-piece',
            refresh: $.Editable.prototype.refreshDefault,
            refreshOnShow: $.Editable.prototype.refreshWidgets,
            callback: function () {
                this.addWidget()
            },
            callbackWithoutSelection: function(b, c, d) {
                console.log('callbackWithoutSelection');
            },
            undo: !0 // Enable only if it might affect the UNDO stack
        }
    }), 
    $.Editable.prototype.command_dispatcher = $.extend($.Editable.prototype.command_dispatcher, {
        widget: function(command) {
            console.log('dispatcher');
            console.log(command);

            return '<ul class="fr-dropdown-menu f-widget"><li>meh</li></ul>';
        }
    })

    // Register your plugin.
    $.Editable.initializers.push($.Editable.prototype.initMushRaider),

    $.Editable.prototype.addWidget = function(b) {
        console.log('add');
        console.log(b);
    };
})(jQuery);