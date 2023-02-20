(function ($) {
    'use strict';
	
    $(document).ready(function () {
        $('.wrap>a').after('<span class=""><input type="button" id="etn_sync_zoom" class="page-title-action etn-sync-zoom " value="'+zoom_localized_data.sync_with_zoom+'"></span>');

        $('#etn_sync_zoom').on('click', function(e) {
            e.preventDefault();

            if( confirm(zoom_localized_data.sync_confirmation) ){
                let current_this = $(this);
                let zoom_type    = 'meeting';

                var request_data = {
                    'zoom_type'    : zoom_type,
                    'action'       : 'sync_zoom_data', 
                    'sync_nonce'   : zoom_localized_data.zoom_sync_nonce,
                };

                $.ajax({
                    url: zoom_localized_data.ajax_url,
                    type: 'POST',
                    data: request_data,
                    beforeSend: function() {
                        current_this.prop('disabled', true).css('cursor', 'wait');
                        current_this.parent().addClass('etn-ajax-loading');
                    },
                    success: function(response) {
                        if(response.data.status_code == 201) {
                            alert(response.data.message[0]);
                            window.location.reload(true);
                        } else if(response.data.status_code == 401 || response.data.status_code == 500) {
                            alert(response.data.message[0]);
                        }
                        current_this.prop('disabled', false).removeAttr("style");
                        current_this.parent().removeClass('etn-ajax-loading');
                    },
                    error: function (response) {
                        current_this.prop('disabled', false).removeAttr("style");
                        current_this.parent().removeClass('etn-ajax-loading');
                    }
                })
            }
        });
    })
})(jQuery);