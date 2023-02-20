(function ($) {

    $(function () {

        $('#ttp-taxonomy').on('change', function () {
            var self = $(this),
                tax = self.val(),
                target = $('#term-wrapper');
            if(tax){
                var data ="tax="+ tax;
                AjaxCall('ttp-get-term-list', data, function (data) {
                    if(!data.error) {
                        target.html(data.data);
                        var fixHelper = function (e, ui) {
                            ui.children().children().each(function () {
                                $(this).width($(this).width());
                            });
                            return ui;
                        };
                        $('#order-target').sortable({
                            items: 'li',
                            axis: 'y',
                            helper: fixHelper,
                            placeholder: 'placeholder',
                            opacity: 0.65,
                            update: function (e, ui) {
                                var target = $('#order-target');
                                var taxonomy = target.data('taxonomy'),
                                    terms = target.find('li').map(function () {
                                        return $(this).data('id');
                                    }).get(),
                                    data = "taxonomy="+taxonomy+"&terms="+terms;
                                console.log(terms);
                                AjaxCall('ttp-term-update-order', data, function (data) {
                                    console.log(data);
                                    if(data.error){
                                        alert('Error !!!');
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

    });

    function AjaxCall(action, arg, handle) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;
        data = data + "&"+ttp.nonceID+"=" + ttp.nonce;
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            beforeSend: function() {
                $('body').append($("<div id='ttp-loading'><span class='ttp-loading'>Updating ...</span></div>"));
            },
            success: function(data) {
                $("#ttp-loading").remove();
                handle(data);
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                $("#ttp-loading").remove();
                alert( textStatus + ' (' + errorThrown + ')' );
            }
        });
    }
})(jQuery);
