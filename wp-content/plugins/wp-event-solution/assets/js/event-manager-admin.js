jQuery(document).ready(function ($) {
    'use strict';
    
    // load color picker   
    $("#etn_primary_color").wpColorPicker();
    $("#etn_secondary_color").wpColorPicker();

    $('body').on('click', '.etn_event_upload_image_button', function (e) {

        e.preventDefault();
        let multiple = $(this).data("multiple") ? !!$(this).data("multiple") : false;
        const button = $(this);
        const custom_uploader = wp.media({
            title: "Insert image",
            library: {
            type: "image",
            },
            button: {
            text: "Use this image", // button label text
            },
            multiple,
        })
        .on("select", function () {
            const attachment = custom_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();

            $(button)
            .removeClass("button")
            .html(
                '<img class="true_pre_image" src="' +
                attachment.url +
                '" style="max-width:95%;display:block;" alt="" />'
            )
            .next()
            .val(attachment.id)
            .next()
            .show();
        })
        .open();    });

    /*
     * Remove image event
     */
    $('body').on('click', '.essential_event_remove_image_button', function () {
        $(this).hide().prev().val('').prev().addClass('button').html('Upload image');
        return false;
    });

    // select2 for meta box
    $('.etn_es_event_select2').select2();

    // social icon
    var etn_selected_social_event_icon = null;
    $(' .social-repeater').on('click', '.etn-social-icon', function () {

        etn_selected_social_event_icon = $(this);

    });

    $('.etn-social-icon-list i').on("click", function () {
        var icon_class_selected = $(this).data('class');
        etn_selected_social_event_icon.val(icon_class_selected);
        $('.etn-search-event-mng-social').val(icon_class_selected);
        etn_selected_social_event_icon.siblings('i').removeClass().addClass(icon_class_selected);
    });


    $('.etn-search-event-mng-social').on('input', function () {
        var search_value = $(this).val().toUpperCase();

        let all_social_list = $(".etn-social-icon-list i");

        $.each(all_social_list, function (key, item) {

            var icon_label = $(item).data('value');

            if (icon_label.toUpperCase().indexOf(search_value) > -1) {
                $(item).show();
            } else {
                $(item).hide();
            }

        });
    });

    var etn_social_rep = $('.social-repeater').length;

    if (etn_social_rep) {
        $('.social-repeater').repeater({

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {

                $(this).slideUp(deleteElement);

            },

        });
    }

    $('.etn-settings-nav li a').on('click', function(){
        const target = $(this).attr('data-id');
        $('.etn-settings-nav li a').removeClass('etn-settings-active');
        $(`#${target}`).fadeIn('slow').siblings(".etn-settings-tab").hide();
        $(this).addClass('etn-settings-active');
        return false;
    });

    // works only this page post_type=etn-schedule
    $('.etn_es_event_repeater_select2').select2();

    // event manager repeater
    var etn_repeater_markup_parent = $(".etn-event-manager-repeater-fld");
    var schedule_repeater = $(".schedule_repeater");
    var schedule_value = $("#etn_schedule_sorting");
    var speaker_sort = {};

    if ((schedule_value.val() !== undefined) && (schedule_value.val() !== '')) {
        speaker_sort = JSON.parse(schedule_value.val());
    }

    if (etn_repeater_markup_parent.length) {
        etn_repeater_markup_parent.repeater({
            show: function () {
                var repeat_length = $(this).parent().find('.etn-repeater-item').length;
                $(this).slideDown();
                $(this).find('.event-title').html($(this).parents('.etn-repeater-item').find(".etn-sub-title").text() + " " + repeat_length);
                $(this).find('.select2').remove();
                $(this).find('.etn_es_event_repeater_select2').select2();

                // make schedule repeater sortable 
                var repeater_items_length = schedule_repeater.find('.sort_repeat').length;
                if (repeater_items_length > 0) {
                    schedule_repeater.find('.sort_repeat:last-child').attr("data-repeater-item", repeater_items_length - 1);
                    etn_drag_and_drop_sorting();
                }  
                 //time picker
                 $(".sort_repeat").on('focus', '#etn_shedule_start_time, #etn_shedule_end_time', function () {
                    $(this).flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        time_24hr: false,
                        dateFormat: "h:i K",
                    });
                });
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
                speaker_sort = {};
                $(this).closest(".sort_repeat").remove();
                $(".sort_repeat").each(function (index, item) {
                    var $this = $(this);
                    if (typeof $this.data('repeater-item') !== undefined) {
                        var check_index = index == $(".sort_repeat").length ? index - 1 : index
                        $this.attr("data-repeater-item", check_index);
                        speaker_sort[index] = check_index;
                    }
                })
                schedule_value.val("").val(JSON.stringify(speaker_sort));
            },

        });
    }

    // Repetaer data re-ordering 
    if (schedule_repeater.length) {

        schedule_repeater.sortable({
            opacity: 0.7,
            revert: true,
            cursor: 'move',
            stop: function (e, ui) {
                etn_drag_and_drop_sorting();
            },
        });
    }

    function etn_drag_and_drop_sorting() {
        $(".sort_repeat").each(function (index, item) {
            var $this = $(this);
            if (typeof $this.data('repeater-item') !== "undefined") {
                var check_index = index == $(".sort_repeat").length ? index - 1 : index
                var repeat_value = $this.data('repeater-item') == $(".sort_repeat").length ? $this.data('repeater-item') - 1 : $this.data('repeater-item')
                speaker_sort[check_index] = repeat_value;
            }
        })
        schedule_value.val(JSON.stringify(speaker_sort));
    }

    // slide repeater
    $(document).on('click', '.etn-event-shedule-collapsible', function () {
        $(this).next('.etn-event-repeater-collapsible-content').slideToggle()
            .parents('.etn-repeater-item').siblings().find('.etn-event-repeater-collapsible-content').slideUp();

    });
    $('.etn-event-shedule-collapsible').first().trigger('click');
    // ./End slide repeater
    // ./end works only this page post_type=etn-schedule

    //  date picker
    $(".etn-date .etn-form-control, #etn_start_date, #etn_end_date").flatpickr();

    // event start date and end date validation
    var etn_start_date = $("#etn_start_date");
    var etn_end_date = $("#etn_end_date");

    $("#etn_start_date,#etn_end_date").on('change', function () {
        var startDate = etn_start_date.val();
        var endDate = etn_end_date.val();
        $(etn_start_date).parent().find(".required-text").remove();
        var $this = $(this);

        if ($this.attr('name') == "etn_start_date") {
            if ((Date.parse(startDate) > Date.parse(endDate))) {
                etn_start_date.val("");
                $(etn_start_date).before(`<span class="required-text">${form_data.start_date_valid}</span>`);
            }else{
                etn_start_date.parent().find(".required-text").remove();
            }
        }
        else if ($this.attr('name') == "etn_end_date") {
            if (startDate == '') {
                etn_end_date.val("");
                etn_start_date.before(`<span class="required-text">${form_data.common_date_valid}</span>`);
            }else if ((Date.parse(startDate) > Date.parse(endDate))) {
                etn_end_date.val("");
                etn_end_date.before(`<span class="required-text">${form_data.end_date_valid}</span>`);
            }else{
                etn_end_date.parent().find(".required-text").remove();
            }
        }

    });

    // change date format to expected format
    const flatpicker_date_format_change = (selectedDates, format) => {
        const date_ar = selectedDates.map(date => flatpickr.formatDate(date, format));
        var new_selected_date = date_ar.toString();

        return new_selected_date;
    }

   // time picker
function timePicker({selector,altInputClass, onCloseSelector, onCloseAttr }){
    $(selector).flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        allowInput: true,
        altInput: true,
        altInputClass,
        onClose: function(dateObj, dateStr, instance){
            // event start and end time validation
            const selected   = flatpicker_date_format_change( dateObj , "H:i" );
            $(onCloseSelector).attr(onCloseAttr, selected );
            // event_time_validation('etn_start_time');
        },
    });
}
// timePicker used in schedule edit/add page(start/end time)
timePicker({selector: ".etn-time, #etn_start_time",altInputClass:"etn-form-control etn_start", onCloseSelector:"etn-form-control etn_start", onCloseAttr: "data-start_time"})

timePicker({selector: "#etn_end_time,#etn_shedule_end_time",altInputClass:"etn-form-control etn_end", onCloseSelector:"#etn_end_time", onCloseAttr: "data-end_time"})


    var eventMnger = '#etn-general_options';
    if (window.location.hash) {
        eventMnger = window.location.hash;
    }

    $('.etn-settings-tab .nav-tab[href="' + eventMnger + '"]').trigger('click');

    // Previous tab active on reload or save
    if ($('.etn-settings-dashboard').length > 0) {
        var tab_get_href = localStorage.getItem('tab_href');
        var getTabId = JSON.parse(tab_get_href);
        let locationHash = tab_get_href === null ? "#etn-general_options" : getTabId.tab_href;
        if (locationHash && $(`.etn-tab li a[href='${locationHash}']`)[0]) {
            $(`.etn-tab li:first-child`).removeClass("attr-active");
            $(`.attr-tab-pane:first-child`).removeClass("attr-active");
            $(`.etn-tab li a[href='${locationHash}']`).parent().addClass("attr-active");
            $(`.attr-tab-pane[id='${locationHash.substr(1)}']`).addClass("attr-active");
        } else {
            $('.etn-tab li:first-child').addClass("attr-active");
            $('.attr-tab-pane:first-of-type').addClass("attr-active");
        }

        // Hide submit button for Hooks tab
        var data_id = $(`.attr-tab-pane[id='${locationHash.substr(1)}']`).attr('data-id');
        var settings_submit = $(".etn_save_settings");
        if (data_id == "tab6") {
            settings_submit.addClass("attr-hide");
        }
        else {
            settings_submit.removeClass("attr-hide");
        }
    }

    //admin settings tab
    $(document).on('click', ".etn-tab > li > a", function (e) {
        e.preventDefault();
        var $this   = $(this);
        var etn_tab = $('.etn_tab');
        $(".etn-tab li").removeClass("attr-active");
        $(this).parent().addClass("attr-active");
        $(".attr-tab-content .attr-tab-pane").removeClass("attr-active");
        $(".attr-tab-pane[data-id='" + $(this).attr('data-id') + "']").addClass("attr-active");

        etn_tab.val($this.attr('data-id'));
        $('.etn-admin-container--body .etn-settings-from').attr('id', etn_tab.val())

        //set hash link
        let tab_href = $(this).attr("href");
        localStorage.setItem('tab_href', JSON.stringify({ tab_href: tab_href }));

        // Hide submit button for Hooks tab
        var data_id = $(this).attr('data-id');
        var settings_submit = $(".etn_save_settings");
        if (data_id == "tab6") {
            settings_submit.addClass("attr-hide ");
        }
        else {
            settings_submit.removeClass("attr-hide ");
        }
    });

    // schedule tab
    $('.postbox .hndle').css('cursor', 'pointer');

    // dashboard menu active class pass
    var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
    $("#toplevel_page_etn-events-manager .wp-submenu-wrap li a").each(function () {
        if ($(this).attr("href") == pgurl || $(this).attr("href") == '')
            $(this).parent().addClass("current");
    });

    // ZOOM MODULE
    // zoom moudle on / off
    const selector = '#zoom_api';
    const toggleBlock = ".zoom_block";
    block_show_hide(selector, toggleBlock);
    jQuery(selector).trigger('change')


    let current_zoom_type = $('.etn-zoom-meeting-type option:selected').val();
    if (current_zoom_type == '2') {
        $('.etn-zoom-webinar-field').fadeOut('slow');
    } else {
        $('.etn-zoom-meeting-field').fadeOut('slow');
    }

    $('.etn-zoom-meeting-type').on('change', function () {
        if ($('option:selected', this).val() == '5') {
            $('.etn-zoom-meeting-field').fadeOut('slow');
            $('.etn-zoom-webinar-field').fadeIn('slow');
        } else {
            $('.etn-zoom-meeting-field').fadeIn('slow');
            $('.etn-zoom-webinar-field').fadeOut('slow');
        }
    })

    // add date time picker
    var start_time = $('#zoom_start_time');

    start_time.flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",
    });

    start_time.attr('required', true);


    $('#zoom_meeting_password').attr('maxlength', '10');

    $(document).on('click', '.eye_toggle_click', function () {
        var get_id = $(this).parents('.etn-secret-key').children().attr('id');
        $(this).toggleClass('fa fa-eye fa fa-eye-slash');
        show_password(get_id);
    });
    // show hide password
    function show_password(id) {
        const pass = document.getElementById(id);
        pass.type = pass.type === "password" ? pass.type = "text" : pass.type = "password";
        
    }
    // check api connection
    $(document).on('click', '.check_api_connection', function (e) {
        e.preventDefault();
        const data = {
            action: 'zoom_connection',
            zoom_nonce: form_data.zoom_connection_check_nonce,
        }
        $.ajax({
            url: form_data.ajax_url,
            method: 'POST',
            data,
            success: function (data) {
                if (typeof data.data.message !== "undefined" && data.data.message.length > 0) {
                    alert(data.data.message[0]);
                }
            }
        });
    });

    $(".etn-settings-select").select2();


    /*-----------------Conditional Block --------------------*/

    $(".etn-conditional-control").on("change", function () {
        const _this = $(this);
        const conditional_control_content = _this.parents(".etn-label-item").next(".etn-label-item");
        if (_this.prop('checked')) {
            conditional_control_content.slideDown();
        } else {
            conditional_control_content.slideUp();
        }
    });
    $(".etn-conditional-control").trigger("change");

    /*------------------Conditional Block------------------*/

    // Set default ticket limit
    $(".repeater_button").on("click", function () {
        available_tickets();
    });

    function available_tickets() {
        const item = $(".etn-repeater-item");
        if ( typeof item !=="undefined" && item.length> 0 ) {
            for (let index = 0; index < item.length ; index++) {
                $('input[name="etn_ticket_variations['+index+'][etn_avaiilable_tickets]"]')
                .attr('placeholder','100,000');
                
            }
        }
    }

    $('input[name="etn_ticket_availability"]').on('change',function(){
        const $this = $(this);
        if ( $this.prop('checked') ) {
            const limit_info = $this.attr("data-limit_info");
           $this.parent('.etn-meta').after('<div class="limit_info">'+ limit_info +'</div>')
        }else{
            $('.limit_info').remove();
        }
        // set default available ticket for 1st row
        $('input[name="etn_ticket_variations[0][etn_avaiilable_tickets]"]')
            .attr('placeholder', '100,000');
    })

    $("#attendee_registration").on("change", function () {
        const _this = $(this);
        const attendeeConditionalInputField = _this.parents(".etn-label-item").nextAll();
        if (_this.prop('checked')) {
            attendeeConditionalInputField.slideDown();
        } else {
            //hide all conditional divs
            attendeeConditionalInputField.slideUp();

            //update input values
            $("#reg_require_phone").prop("checked", false);
            $("#reg_require_email").prop("checked", false);
            $("#disable_ticket_email").prop("checked", false);
        }
    });
    $("#attendee_registration").trigger("change");

    // Zoom password field length validation
    const zoom_password = $("#zoom_password");
    // if the id found , trigger the action
    if (zoom_password.length > 0) {
        zoom_password.prop('maxlength', 10)
    }

    //   custom tabs
    $(document).on('click', '.etn-tab-a', function (event) {
        event.preventDefault();

        $(this).parents(".schedule-tab-wrapper").find(".etn-tab").removeClass('tab-active');
        $(this).parents(".schedule-tab-wrapper").find(".etn-tab[data-id='" + $(this).attr('data-id') + "']").addClass("tab-active");
        $(this).parents(".schedule-tab-wrapper").find(".etn-tab-a").removeClass('etn-active');
        $(this).parent().find(".etn-tab-a").addClass('etn-active');
    });


    // **********************
    //  get from value in shortcode settings
    //  ****************************

    $(document).on('click', '.shortcode-generate-btn', function (event) {
        event.preventDefault();
        var arr = [];

        $(this).parents('.shortcode-generator-wrap').find(".etn-field-wrap").each(function () {
            var $this = $(this);
            var data = $this.find('.etn-setting-input').val();
            var option_name = $this.find('.etn-setting-input').attr('data-cat');
            var post_count = $this.find('.post_count').attr('data-count');

            if (option_name != undefined && option_name != '') {
                data = option_name + ' = ' + (data.length ? data : '""');
            }
            if (post_count != undefined && post_count != '') {
                data = post_count + ' = ' + (data.length ? data : '""');
            }
            arr.push(data);
        });


        var allData = arr.filter(Boolean);
        var shortcode = "[" + allData.join(' ') + "]";

        $(this).parents('.shortcode-generator-wrap').find('.etn_include_shortcode').val(shortcode);
        $(this).parents('.shortcode-generator-wrap').find('.copy_shortcodes').slideDown();

    });

    $(document).on('click', '.s-generate-btn', function (event) {
        var $this = $(this);
        $($this).parents('.shortcode-generator-wrap').find('.shortcode-generator-main-wrap').fadeIn();

        $($this).parents('.shortcode-generator-wrap').mouseup(function (e) {
            var container = $(this).find(".shortcode-generator-inner");
            var container_parent = container.parent(".shortcode-generator-main-wrap");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container_parent.fadeOut();
            }
        });

    });
    $(document).on('click', '.shortcode-popup-close', function (event) {
        $(this).parents('.shortcode-generator-wrap').find('.shortcode-generator-main-wrap').fadeOut();
    });



    $(".etn-field-wrap").each(function () {
        $(this).find(".get_schedule_template").on('change', function () {
            $(this).find("option:selected").each(function () {
                var $this = $(this);
                var optionValue = $this.attr("value");  
                if(optionValue === 'schedules' || optionValue =='etn_pro_schedules_tab'){
                    $this.parents(".shortcode-generator-inner").find('.etn-shortcode-select').attr("multiple", 'multiple');
                } else {
                    $this.parents(".shortcode-generator-inner").find('.etn-shortcode-select').removeAttr("multiple");
                }
            });
        }).change();

    });



    show_conditinal_field($, ".get_template", 'etn_pro_speakers_classic', '.speaker_style');
    show_conditinal_field($, ".get_template", 'etn_pro_events_classic', '.event_pro_style');
    show_conditinal_field($, '.calendar-style select', "style ='style-1'", '.s-display-calendar');


    $('#recurrence_freq').on('change', function (e) {
        var _this = $(this);
        var freq_value = _this.val();
        var day_interval_block = document.querySelector('#event-interval-day');
        var week_interval_block = document.querySelector('#event-interval-week');
        var month_interval_block = document.querySelector('#event-interval-month');
        var year_interval_block = document.querySelector('#event-interval-year');

        const blockArray = [day_interval_block, week_interval_block, month_interval_block, year_interval_block];

        if(freq_value == 'day'){
            addDisplayStyle(blockArray, day_interval_block, "flex");
            
        }else if(freq_value == 'week'){
            addDisplayStyle(blockArray, week_interval_block, "flex");
            
        }else if(freq_value == 'month'){
            addDisplayStyle(blockArray, month_interval_block, "flex");
            
        }else if(freq_value == 'year'){
            addDisplayStyle(blockArray, year_interval_block, "block");
            
        } else {
            addDisplayStyle(blockArray, 'none', "none");    
                    
        }
        
        function addDisplayStyle( list, blockItem, block){
             list.map(item => {
                item.style.display = item == blockItem ? block : 'none'
            })
        }
    });

    $('#recurrence_freq').trigger('change');

    // enable/disable option for woocommerce hide/show div
    $('#sell_tickets').on('change', function () {
        var _this = $(this);
        if (_this.prop('checked')) {
            $('.woocommerce-payment-type').slideDown();
            var _that = $('#etn_sells_engine_stripe');
            if (_that.prop('checked')) {
                _that.prop("checked", false);
                $('.stripe-payment-methods').slideUp();
            }

        } else {
            $('.woocommerce-payment-type').slideUp();
        }
    });
    $('#sell_tickets').trigger('change');

    // show event ticket variation stock count field depending on limited / unlimited settings
    $("input[name='etn_ticket_availability']").on("change", function () {
        var _this = $(this);
        var all_variation_counts = $('.etn-ticket-stock-count');
        if (_this.prop('checked')) {
            all_variation_counts.each(function () {
                $(this).show();
            });
        } else {
            all_variation_counts.each(function () {
                $(this).hide();
            });
        }
    });
    $("input[name='etn_ticket_availability']").trigger("change");

    /**
     * update ticket status from attendee dashboard 
     */
    $('.etn_ticket_status').on('change', function () {
        let current_this = $(this);
        let ticket_label = current_this.next();

        let ticket_wrap = current_this.parent();
        let ticket_msg = ticket_wrap.next();

        let ticket_status = current_this.val();
        let attendee_id = current_this.data('attendee_id');

        ticket_msg.html('').removeAttr('style');
        // .css({"display": "block"});
        $.ajax({
            type: "POST",
            url: form_data.ajax_url,
            dataType: 'json',
            data: {
                attendee_id: attendee_id,
                ticket_status: ticket_status,
                action: 'change_ticket_status',
                security: form_data.ticket_status_nonce,
            },
            beforeSend: function () {
                ticket_wrap.addClass('etn-ajax-loading');

                current_this.addClass('etn-status-changing');
                ticket_label.addClass('etn-status-changing');
            },
            complete: function () {
                ticket_wrap.removeClass('etn-ajax-loading');

                current_this.removeClass('etn-status-changing');
                ticket_label.removeClass('etn-status-changing');
            },
            success: function (res) {
                let res_data = res.data;
                let res_content = res_data.content;
                let msg = res_data.messages[0];

                if (res.success) {
                    current_this.val(res_content.new_val);
                    ticket_label.html(res_content.new_text);

                    // showing and removing update info
                    ticket_msg.html(msg).addClass('status-success').removeClass('status-failed').css({ "display": "block" });
                    const ticket_status_timeout = setTimeout(function () {
                        ticket_msg.fadeOut("slow");
                    }, 2000);
                } else {
                    (current_this.prop('checked')) ? current_this.prop('checked', false) : current_this.prop('checked', true);
                    ticket_msg.html(msg).addClass('status-failed').removeClass('status-success').css({ "display": "block" });
                }
            },
            error: function (res) {
                ticket_msg.html(res.data.messages[0]).addClass('status-failed').removeClass('status-success').css({ "display": "block" });
            }
        });
    });

    // Help page FAQ
    $(document).on('click', '.tw-accordion-title', function () {
        $(this).parent().closest('.tw-accordion-content-wrapper').toggleClass('item-active');
        $(this).siblings().slideToggle('fast');
    });
});


function show_conditinal_field($, selectClass, optionName, showHideClass) {
    $(selectClass).on('change', function () {
        $(this).find("option:selected").each(function () {
            var optionValue = $(this).attr("value");
            if (optionValue === optionName) {
                $(showHideClass).show();
            } else {
                $(showHideClass).hide();
            }
        });
    }).change();
}



//   copy text
function copyTextData(FIledid) {
    var FIledidData = document.getElementById(FIledid);
    if (FIledidData) {
        FIledidData.select();
        document.execCommand("copy");
    }
}

// toggle any block using jQUERY
function block_show_hide(selector, toggleBlock) {
    jQuery(selector).on('change', function () {
        if (jQuery(selector).prop('checked')) {
            jQuery(toggleBlock).slideDown('slow');
        } else {
            jQuery(toggleBlock).slideUp('slow');
        }
    })
}

function etn_remove_block(remove_block_object) {
    jQuery(remove_block_object.parent_block).on('click', remove_block_object.remove_button, function (e) {
        e.preventDefault();
        jQuery(this).parent(remove_block_object.removing_block).remove();
    });
}
