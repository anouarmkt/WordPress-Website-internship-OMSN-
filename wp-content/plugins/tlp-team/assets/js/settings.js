(function (global, $) {
    $.browser = {};
    $.browser.msie = false;
    $.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        $.browser.msie = true;
        $.browser.version = RegExp.$1;
    }
    var editor,
        syncCSS = function () {
            $('.rt-custom-css').each(function () {
                var e = ace.edit($(this).find('.custom-css').attr('id'));
                $(this).find('.rt-custom-css-textarea').val(e.getSession().getValue());
            });
        },
        loadAce = function () {
            $('.rt-custom-css').each(function () {
                var id = $(this).find('.custom-css').attr('id');
                editor = ace.edit(id);
                global.safecss_editor = editor;
                editor.getSession().setUseWrapMode(true);
                editor.setShowPrintMargin(false);
                editor.getSession().setValue($(this).find('.rt-custom-css-textarea').val());
                editor.getSession().setMode("ace/mode/css");
            });

            $.fn.spin && $('.rt-custom-css-container').spin(false);
        },
        AjaxCallTeam = function (element, action, arg, handle) {
            var data;
            if (action) data = "action=" + action;
            if (arg) data = arg + "&action=" + action;
            if (arg && !action) data = arg;
            var n = data.search(ttp.nonceID);
            if (n < 0) {
                data = data + "&" + ttp.nonceID + "=" + ttp.nonce;
            }

            $.ajax({
                type: "post",
                url: ttp.ajaxurl,
                data: data,
                beforeSend: function () {
                    $("<span class='tlp_loading'></span>").insertAfter(element);
                },
                success: function (data) {
                    element.next(".tlp_loading").remove();
                    handle(data);
                },
                error: function (error) {
                    element.next(".tlp_loading").remove();
                    handle(error);
                }
            });
        },
        renderTeamMediaUploader = function () {
            var file_frame, image_data;
            if (undefined !== file_frame) {
                file_frame.open();
                return;
            }
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or Upload Media For your member profile gallery',
                button: {
                    text: 'Use this media'
                },
                multiple: false
            });
            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                var imgId = attachment.id;
                var imgUrl = (typeof attachment.sizes.thumbnail === "undefined") ? attachment.url : attachment.sizes.thumbnail.url;
                $("ul#tlp-team-gallery").append("<li><span class='dashicons dashicons-dismiss'></span><img src='" + imgUrl + "' /><input type='hidden' name='tlp_team_gallery[]' value='" + imgId + "' /></li>");
                $("ul#tlp-team-gallery li.no-img").hide();
            });
            // Now display the actual file_frame
            file_frame.open();
        };


    if ($.browser.msie && parseInt($.browser.version, 10) <= 7) {
        $('.rt-custom-css-container').hide();
        $('.rt-custom-css-textarea').show();
        return false;
    } else {
        $(global).load(loadAce);
    }
    global.aceSyncCSS = syncCSS;

    $("#tagsdiv-tlp_designation, #tagsdiv-tlp_skill").remove();

    $(function () {

        //techlabpro23
        if ($(".rttm-select2").length && $.fn.select2) {
            $(".rttm-select2").select2({
                multiple: true,
                width: '80%',
                placeholder: "Choose Items"
            });
        }

        if ($(".rttm-select2-single").length && $.fn.select2) {
            $(".rttm-select2-single").select2();
        }

        $("#sc-tabs, #tlp_team_meta, #settings-tabs").on('click', '.pro-field', function (e) {
            e.preventDefault();
            $('.rttm-pro-alert').show();
        });

        //pro alert close
        $('.rttm-pro-alert-close').on('click', function (e) {
            e.preventDefault();
            $('.rttm-pro-alert').hide();
        });

        $('#license_key').on('keyup', function () {
            $('.license-status').hide();
        });

        // dynamic layout list
        dynamicLayoutList();
        $('input[name=layout_type]').on('change', function () {
            dynamicLayoutList();
        });
        //end tachlabpro23

        if ($('.tlp-color').length) {
            $('.tlp-color').wpColorPicker();
        }

        if ($("#metaSocialHolder").length || $("#metaSkillHolder").length) {
            $("#metaSocialHolder, #metaSkillHolder").sortable();
        }

        changeEffect();
    });

    function dynamicLayoutList() {

        let layout_type = $('input[name=layout_type]:checked').val(),
            layout = $("#rttm-style");

        if (layout_type) {
            let layout_option = '';
            for (const [key, value] of Object.entries(ttp.layout_group[layout_type])) {
                let checked = (ttp.layout == value.value) ? 'checked' : '';
                layout_option += `<label for="rttm-style-${value.value}">
                <input type="radio" id="rttm-style-${value.value}" name="layout" ${checked} value="${value.value}" data-pro="">
                <div class="rttm-radio-image-pro-wrap">
                <img src="${value.img}" title="${value.name}" alt="${value.value}">
                <div class="rttm-checked"><span class="dashicons dashicons-yes"></span></div>
                </div><div class="rttm-demo-url"><a href="${value.demo}" target="_blank">${value.name}</a></div>
                </label>`;
            }

            layout.empty();
            layout.append(layout_option);
            layout_style();
            changeEffect();
        }
    }

    $("#addNewSocial").on('click', function (e) {
        var total = $('.socialLink').length;
        arg = "id=" + total;
        bindElement = $('#addNewSocial');
        $('.socialLink').each(function (index, e) {
            $(e).attr('id', 'slh-' + index);
            $(e).children('.tlp-label').children('select').attr('name', 'social[' + index + '][id]');
            $(e).children('.tlp-field').children('.tlpfield').attr('name', 'social[' + index + '][url]');
            $(e).children('.sRemove').attr('data-id', index);
        });
        AjaxCallTeam(bindElement, 'tlpTeamSocialInput', arg, function (data) {
            $("#metaSocialHolder").append(data);
        });
    });

    $(document).on('click', ".sRemove", function (e) {
        var id = $(this).data("id");
        $('#slh-' + id).slideUp("slow", function () {
            $(this).remove();
            $('.socialLink').each(function (index, e) {
                $(e).attr('id', 'slh-' + index);
                $(e).children('.tlp-label').children('select').attr('name', 'social[' + index + '][id]');
                $(e).children('.tlp-field').children('.tlpfield').attr('name', 'social[' + index + '][url]');
                $(e).children('.sRemove').attr('data-id', index);
            });
        });
    });

    $("#addNewSkill").on('click', function () {
        var total = $('.skillHolder').length;
        arg = "id=" + total;
        bindElement = jQuery('#addNewSkill');
        $('.skillHolder').each(function (index, e) {
            $(e).attr('id', 'sh-' + index);
            $(e).children('.tlp-label').children('select').attr('name', 'skill[' + index + '][id]');
            $(e).children('.tlp-field').children('.tlpfield').attr('name', 'skill[' + index + '][percent]');
            $(e).children('.sRemove').attr('data-id', index);
        });
        AjaxCallTeam(bindElement, 'tlpTeamSkillInput', arg, function (data) {
            $("#metaSkillHolder").append(data);
        });
    });

    $(document).on('click', ".skRemove", function (e) {
        var id = $(this).data("id");
        $('#sh-' + id).slideUp("slow", function () {
            $(this).remove();
            $('.skillHolder').each(function (index, e) {
                $(e).attr('id', 'sh-' + index);
                $(e).children('.tlp-label').children('select').attr('name', 'skill[' + index + '][id]');
                $(e).children('.tlp-field').children('.tlpfield').attr('name', 'skill[' + index + '][percent]');
                $(e).children('.skRemove').attr('data-id', index);
            });
        });
    });

    $("#add-new-team-img").on('click', function (evt) {
        evt.preventDefault();
        renderTeamMediaUploader();
    });

    if ($('ul#tlp-team-gallery').length) {
        $('ul#tlp-team-gallery').sortable({
            items: 'li',
            opacity: 0.5,
            cursor: 'pointer'
        });
    }

    //$(document).on('click', 'ul#tlp-team-gallery li span.dashicons-dismiss', function(e) {
    $("ul#tlp-team-gallery li span.dashicons-dismiss").on("click", function (e) {
        e.preventDefault();
        if (confirm("Are you sure?")) {
            var li = $(this).parent('li');
            var id = li.find('input').val();
            var post_ID = $("#post_ID").val();
            var tlp_nonce = $("#tlp_nonce").val();
            if (id && post_ID && tlp_nonce) {
                var arg = "id=" + id + "&post_ID=" + post_ID + "&" + ttp.nonceID + "=" + ttp.nonce;
                li.find('img').css('opacity', .3);
                AjaxCallTeam($(this), 'tlp_team_profile_img_remove', arg, function (data) {
                    console.log(data.msg);
                    if (!data.error) {
                        li.slideUp('slow').remove();
                    }
                });
            } else {
                alert("Image or Post ID Not found");
            }
        }
    });

    $(".rt-tab-nav li").on('click', 'a', function (e) {
        e.preventDefault();
        var container = $(this).parents('.rt-tab-container');
        var nav = container.children('.rt-tab-nav');
        var content = container.children(".rt-tab-content");
        var $this, $id;
        $this = $(this);
        $id = $this.attr('href');

        switch ($id) {
            case '#sc-layout':
                $('#_rttm_sc_tab').val('layout');
                break;

            case '#sc-filtering':
                $('#_rttm_sc_tab').val('filtering');
                break;

            case '#sc-field-selection':
                $('#_rttm_sc_tab').val('field-selection');
                break;

            case '#sc-styling':
                $('#_rttm_sc_tab').val('styling');
                break;
        }

        content.hide();
        nav.find('li').removeClass('active');
        $this.parent().addClass('active');
        container.find($id).show();
    });

    if (rttm.is_pro && $('.post-type-team table.posts #the-list').length) {
        var fixHelper = function (e, ui) {
            ui.children().children().each(function () {
                $(this).width($(this).width());
            });
            return ui;
        };
        $('.post-type-team table.posts #the-list').sortable({
            'items': 'tr',
            'axis': 'y',
            'helper': fixHelper,
            'update': function (e, ui) {
                var order = $('#the-list').sortable('serialize');
                $.ajax({
                    type: "post",
                    url: ajaxurl,
                    data: order + "&action=tlp-team-update-menu-order",
                    beforeSend: function () {
                        $('body').append($("<div id='rt-loading'><span class='rt-loading'>Updating ...</span></div>"));
                    },
                    success: function (data) {
                        console.log(data);
                        $("#rt-loading").remove();
                    }
                });
            }
        });
    }

    $("#tlp-team-settings").on("click", "#tlpSaveButton", function (e) {
        e.preventDefault();
        syncCSS();
        $('#rt-response').hide();
        var self = $(this),
            form = self.parents('form'),
            arg = form.serialize(),
            bindElement = $('#tlpSaveButton', form),
            responseHolder = form.next('#rt-response');
        AjaxCallTeam(bindElement, 'tlpTeamSettings', arg, function (data) {
            responseHolder.show('slow').text(data.msg).addClass('updated');
            if (data.error) {
                responseHolder.addClass('error');
            } else {
                responseHolder.removeClass('error');
                var holder = $("#license_key_holder");
                if (!$(".license-status", holder).length && $("#license_key", holder).val()) {
                    var bindElement = $("#license_key", holder),
                        target = $(".description", holder);
                    target.find(".rt-licence-msg").remove();
                    AjaxCallTeam(bindElement, 'rtTeam_active_Licence', '', function (data) {
                        if (data.html) {
                            target.append("<span class='license-status'>" + data.html + "</span>");
                        }
                        if (data.msg) {
                            if (target.find(".rt-licence-msg").length) {
                                target.find(".rt-licence-msg").html(data.msg);
                            } else {
                                target.append("<span class='rt-licence-msg'>" + data.msg + "</span>");
                            }
                            if (!data.error) {
                                target.find(".rt-licence-msg").addClass('success');
                            }
                        }
                    });
                }
                if (!$("#license_key", holder).val()) {
                    $('.license-status', holder).remove();
                }
            }
        });

        return false;
    });

    $("#tlp-team-settings").on('click', '.rt-team-licensing-btn', function (e) {
        e.preventDefault();
        var self = $(this),
            type = self.attr('name'),
            data = 'type=' + type;
        $("#license_key_holder").find(".rt-licence-msg").remove();
        AjaxCallTeam(self, 'rtTeamManageLicencing', data, function (data) {
            console.log(data);
            if (!data.error) {
                self.val(data.value);
                self.attr('name', data.name);
                self.addClass(data.class);
                if (data.name == 'license_deactivate') {
                    self.removeClass('button-primary');
                    self.addClass('danger');
                } else if (data.name == 'license_activate') {
                    self.removeClass('danger');
                    self.addClass('button-primary');
                }
            }
            if (data.msg) {
                $("<div class='rt-licence-msg'>" + data.msg + "</div>").insertAfter(self);
            }
            self.blur();
        });

        return false;
    });

    function layout_style() {
        $("input[type=radio][name=layout]").on('change', function () {
            changeEffect();
        });
    }

    layout_style();

    $("#ttp_pagination").on('change', function () {
        paginationEffect();
    });
    $("#ttp_image").on('change', function () {
        featureImageEffect();
    });
    $("#ttp_detail_page_link").on('change', function () {
        detailLinkEffect();
    });
    $("#ttp_image_size").on('change', function () {
        imageSizeEffect();
    });
    $("#ttp_filter_taxonomy").on('change', function () {
        setDefaultItemsForFilterGrid();
    });
    $("#ttp_isotope_filter_taxonomy").on('change', function () {
        setDefaultItemsForFilterIso();
    });
    $("#ttp_filter-_taxonomy_filter").on('change', function () {
        taxonomyFilterEffect();
    });
    $("#ttp_filter input[name='ttp_filter']").on('change', function () {
        filterEffectToPagination();
    });
    $("#ttp_detail_page_link_type  input[name='ttp_detail_page_link_type']").on('change', function () {
        linkTypeEffect();
    });

    function changeEffect() {
        featureImageEffect();
        imageSizeEffect();
        let layout = $("input[name=layout]:checked").val();
        if (layout) {
            let isGrid = layout.match(/^layout/i),
                isCarousel = layout.match(/^carousel/i),
                isIsotope = layout.match(/^isotope/i),
                plType = $("#ttp_pagination_type");
            plType.find("label[for='ttp_pagination_type-pagination'],label[for='ttp_pagination_type-pagination_ajax']").show();
            $("#ttl_image_column_holder").hide();
            if (isGrid) {
                $(".tlp-field-holder.ttp-isotope-filter-item, .tlp-field-holder.ttp-carousel-item").hide();
                $("#ttp_filter_holder,.tlp-field-holder.ttp-pagination-item.pagination").show();
                if (layout == "layout2") {
                    $("#ttl_image_column_holder").show();
                }
            } else if (isCarousel) {
                $(".tlp-field-holder.ttp-pagination-item,.tlp-field-holder.ttp-isotope-filter-item,.tlp-field-holder.sc-ttp-grid-filter").hide();
                $(".tlp-field-holder.ttp-carousel-item").show();
            } else if (isIsotope) {
                $(".tlp-field-holder.ttp-carousel-item,.tlp-field-holder.sc-ttp-grid-filter").hide();
                $(".tlp-field-holder.ttp-pagination-item.pagination,.tlp-field-holder.ttp-isotope-filter-item").show();
                plType.find("label[for='ttp_pagination_type-pagination'],label[for='ttp_pagination_type-pagination_ajax']").hide();
                var ltype = plType.find("input[name=ttp_pagination_type]:checked").val();
                if (ltype == "pagination" || ltype == "pagination_ajax") {
                    plType.find("label[for='ttp_pagination_type-load_more'] input").prop("checked", true);
                }
                if ($("#rt-tpg-sc-isotope-filter option:selected").length) {
                    setDefaultItems();
                }
            }
            if ($(".tlp-field-holder.ttp-pagination-item.pagination").is(':visible')) {
                paginationEffect()
            }
            if ($("#ttp_filter_holder").is(':visible')) {
                taxonomyFilterEffect();
                filterEffectToPagination();
            }
            detailLinkEffect();
            setDefaultItemsForFilterGrid();
            setDefaultItemsForFilterIso();
        }
    }

    function paginationEffect() {
        var pagination = $("#ttp_pagination").is(':checked');
        if (pagination) {
            $(".tlp-field-holder.ttp-pagination-item").show();
        } else {
            $(".tlp-field-holder.ttp-pagination-item,.tlp-field-holder.ttp_link_target").not('.pagination').hide();
        }
    }

    function featureImageEffect() {
        if ($("#ttp_image").is(':checked')) {
            $(".tlp-field-holder.ttp-feature-image-option").hide();
        } else {
            $(".tlp-field-holder.ttp-feature-image-option").show();
        }
    }

    function detailLinkEffect() {
        var detailPageLink = $("#ttp_detail_page_link").is(':checked');
        if (detailPageLink) {
            $(".tlp-field-holder.ttp_detail_page_link_type").show();
        } else {
            $(".tlp-field-holder.ttp_detail_page_link_type,.tlp-field-holder.ttp_link_target,.tlp-field-holder.ttp_popup_type").hide();
        }
        linkTypeEffect();
    }

    function linkTypeEffect() {
        var linkType = $("#ttp_detail_page_link_type input[name='ttp_detail_page_link_type']:checked").val();
        if (linkType == "new_page" || linkType == "external_link") {
            $(".tlp-field-holder.ttp_link_target").show();
            $(".tlp-field-holder.ttp_popup_type").hide();
        } else {
            $(".tlp-field-holder.ttp_popup_type").show();
            $(".tlp-field-holder.ttp_link_target").hide();
        }
    }

    function imageSizeEffect() {
        var size = $("#ttp_image_size").val();
        if (size == "ttp_custom") {
            $("#ttp_custom_image_size_holder").show();
        } else {
            $("#ttp_custom_image_size_holder").hide();
        }
    }

    function taxonomyFilterEffect() {
        if ($("#ttp_filter-_taxonomy_filter").is(':checked')) {
            $(".sc-ttp-grid-filter.sc-ttp-filter-item").show();
        } else {
            $(".sc-ttp-grid-filter.sc-ttp-filter-item").not("#ttp_filter_holder").hide();
        }
    }

    function filterEffectToPagination() {
        var plType = $("#ttp_pagination_type"),
            ltype = plType.find("input[name=ttp_pagination_type]:checked").val();
        if ($("#ttp_filter input[name='ttp_filter[]']").is(':checked')) {
            plType.find("label[for='ttp_pagination_type-pagination']").hide();
            if (ltype == "pagination") {
                plType.find("label[for='ttp_pagination_type-pagination_ajax'] input").prop("checked", true);
            }
        } else {
            plType.find("label[for='ttp_pagination_type-pagination']").show();
        }
    }

    function setDefaultItemsForFilterGrid() {
        var target_from = $("#ttp_filter_taxonomy"),
            target = $('#ttp_default_filter'),
            $fId = target_from.val();
        if ($fId) {
            var data = 'action=ttpDefaultFilterItem&filter=' + $fId + "&" + ttp.nonceID + "=" + ttp.nonce;
            $.ajax({
                type: "post",
                url: ttp.ajaxurl,
                data: data,
                beforeSend: function () {
                    $("<span class='rt-loading'></span>").insertAfter(target);
                },
                success: function (data) {
                    if (!data.error) {
                        var selected = target.data('selected');
                        target.html(data.data);
                        if (selected) {
                            target.val(selected).trigger("change");
                        }

                    } else {
                        console.log(data.msg);
                    }
                    target.next(".rt-loading").remove();
                }
            });
        }
    }

    function setDefaultItemsForFilterIso() {

        var target_from_iso = $("#ttp_isotope_filter_taxonomy"),
            target_iso = $('#ttp_isotope_selected_filter'),
            $fId_iso = target_from_iso.val();
        if ($fId_iso) {
            var data = 'action=ttpDefaultFilterItem&filter=' + $fId_iso + "&" + ttp.nonceID + "=" + ttp.nonce;
            $.ajax({
                type: "post",
                url: ttp.ajaxurl,
                data: data,
                beforeSend: function () {
                    $("<span class='rt-loading'></span>").insertAfter(target_iso);
                },
                success: function (data) {
                    if (!data.error) {
                        var selected = target_iso.data('selected');
                        target_iso.html(data.data);
                        if (selected) {
                            target_iso.val(selected).trigger("change");
                        }

                    } else {
                        console.log(data.msg);
                    }
                    target_iso.next(".rt-loading").remove();
                }
            });
        }
    }

})(this, jQuery);
