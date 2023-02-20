jQuery(window).on("elementor/frontend/init", function () {
    /*
    * Checks if in the editor, if not stop the rest from executing
    */
    if ( !window.elementorFrontend.config.environmentMode.edit ) {
        return;
    }
    
    elementor.channels.editor.on('elementor:editor:create', function (panel) {
        if( panel.$el.hasClass('open') ){ return false; }
        var parent  = panel.$el.parents('#elementor-controls'),            
        cache_fld   = parent.find("[data-setting='meeting_cache']"),
        type        = parent.find("[data-setting='zoom_type']"),
        topic       = parent.find("[data-setting='topic']"),
        agenda      = parent.find("[data-setting='agenda']"),
        user_id     = parent.find("[data-setting='user_id']").val(),
        start_time  = parent.find("[data-setting='start_time']").val(),
        timezone    = parent.find("[data-setting='timezone']").val(),
        duration    = parent.find("[data-setting='duration']"),
        password    = parent.find("[data-setting='password']");
     
        let host_video                  = parent.find("[data-setting='host_video']");
        let participant_panelists_video = parent.find("[data-setting='participant_panelists_video']");
        let meeting_authentication      = parent.find("[data-setting='meeting_authentication']");
        let auto_recording              = parent.find("[data-setting='auto_recording']");

        var empty_arr = [ 
                            {"key":"user_id",    "value" : "Please select meeting hosts."} ,
                            {"key":"start_time", "value" : "Please select start time."} ,
                            {"key":"password",   "value" : "Password length can't be more than 10."} 
                        ];

        let type_val = type.val();

        var meeting_id   = "";                
        var meeting_type = "";                
        if ( cache_fld.val() !=="" ) {
            var meeting_data = JSON.parse( cache_fld.val() );
            if ( typeof meeting_data.id !=="undefined" ) {
                meeting_id   = meeting_data.id;
                meeting_type = meeting_data.type;

                if( type_val != meeting_type ){
                    alert(zoom_js.zoom_type_change_alert);
                    return false;
                }
            }
        }

        var invalid_param = [];                
        if ( !user_id ) {
            invalid_param.push( 'user_id' );
        }
        if ( !start_time ) {
            invalid_param.push( 'start_time' );
        }
        if ( password.length > 10 ) {
            invalid_param.push( 'password' );
        }
        if (invalid_param.length > 0) {
            jQuery.each( empty_arr, function( index , value ){
                if (jQuery.inArray( value.key , invalid_param ) != -1) {
                    panel.$el.find('.elementor-control-input-wrapper').append(
                        '<div class="alert alert-danger" role="alert">'+ value.value +'</div>');
                }
                setTimeout(function(){
                    panel.$el.find('.alert').fadeOut().remove();
                }, 2000 )
            })
            return false;
        }

        var form_data = {
            'meeting_id': meeting_id,
            'user_id'   : user_id,
            'start_time': start_time,
            'timezone'  : timezone,
            'duration'  : duration.val(),
            'password'  : password.val(),
            'topic'     : topic.val(),
            'agenda'    : agenda.val(),
            'type'      : type_val.toString(),
            'host_video'                : document.getElementsByClassName('control_host_video')[0].value,
            'meeting_authentication'    : document.getElementsByClassName('control_meeting_authentication')[0].value,
            'auto_recording'            : auto_recording.val(),
            'action'    : 'elementor_create_meeting', 
            'zoom_nonce': zoom_js.zoom_create_meeting_nonce,
        };
        

        if ( type_val == '2' ) {
            form_data.participant_video = document.getElementsByClassName('control_participant_panelists_video')[0].value;

            form_data.waiting_room      = document.getElementsByClassName('control_waiting_room')[0].value;
            form_data.join_before_host  = document.getElementsByClassName('control_join_before_host')[0].value;
            form_data.mute_upon_entry   = document.getElementsByClassName('control_mute_upon_entry')[0].value;
        } else if ( type_val == '5' ) {
            form_data.panelists_video   = document.getElementsByClassName('control_participant_panelists_video')[0].value;

            form_data.question_and_answer    = document.getElementsByClassName('control_question_and_answer')[0].value;
            form_data.practice_session       = document.getElementsByClassName('control_practice_session')[0].value;
            form_data.hd_video               = document.getElementsByClassName('control_hd_video')[0].value;
            form_data.hd_video_for_attendees = document.getElementsByClassName('control_hd_video_for_attendees')[0].value;
        }

        jQuery.ajax({
            data: form_data,
            type: 'post',
            url: zoom_js.ajax_url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', zoom_js.nonce);
                panel.$el.addClass('open');
            },
            success: function (response) {
                if( typeof response !=='undefined' && typeof response.data !== 'undefined') {
                    let response_data = response.data.data;

                    cache_fld.val( JSON.stringify( response_data ) );
                    cache_fld.trigger('input');
 
                    topic.val( response_data.topic );
                    topic.trigger('input');

                    duration.val( response_data.duration );
                    duration.trigger('input');

                    password.val( response_data.password );
                    password.trigger('input');

                    // if(parent.find('.elementor-control-zoom_type .elementor-control-input-wrapper').is(":visible")) {
                    //     parent.find('.elementor-control-zoom_type .elementor-control-input-wrapper').after(
                    //         '<div><input type="text" value="'+ (response_data.type != '5') ? zoom_js.zoom_type_meeting : zoom_js.zoom_type_webinar  +'" readonly></input></div>' 
                    //     );
                    // }

                    // parent.find('.elementor-control-zoom_type .elementor-control-input-wrapper').css('display', 'none');

                    panel.$el.find('.elementor-control-input-wrapper').append('<div class="alert alert-success" role="alert">'+ response.data.message[0] +'</div>');
                } else {
                    panel.$el.find('.elementor-control-input-wrapper').append('<div class="alert alert-danger" role="alert"> Something is wrong </div>');
                }
                panel.$el.removeClass('open');
                setTimeout(function(){
                    panel.$el.find('.alert').fadeOut().remove();
                }, 2000)
            },
            error : function (response){
            }
        });
    });

});
