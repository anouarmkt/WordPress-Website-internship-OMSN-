jQuery(document).ready(function ($) {

    var rtl, winWidth;

    winWidth = $(window).width();

    if (the_conference_data.rtl == '1') {
        rtl = true;
    } else {
        rtl = false;
    }

    //Header Search form show/hide
    $('.site-header .nav-holder .form-holder').prepend('<div class="btn-close-form"><span></span></div>');

    $('.site-header .form-section').on('click', function (event) {
        event.stopPropagation();
    });
    $("#btn-search").on('click', function () {
        $(".site-header .form-holder").show("fast");
    });

    $('.btn-close-form').on('click', function () {
        $('.site-header .nav-holder .form-holder').hide("fast");
    });

    /**
     *
     * Custom js from theme
     *
     */
    new WOW().init();

    $('.scroll-down').on('click', function () {
        $('html, body').animate({
            scrollTop: $(".scroll-down").parent('.item').parents('.site-banner').next().offset().top
        }, 800);
    });

    $('button.toggle-btn').on('click', function () {
        $(' .mobile-menu-wrapper ').addClass('toggled');
        $('.primary-menu-list').addClass('toggled');
        $('body').addClass('menu-open');

    });

    $('.mobile-menu-wrapper .close-main-nav-toggle').on('click', function () {
        $('.mobile-menu-wrapper').removeClass('toggled');
        $('.primary-menu-list').removeClass('toggled');
        $('body').removeClass('menu-open');
        $('body').removeClass('showing-modal');
    });

    $('.overlay').on('click', function () {
        $('.mobile-menu-wrapper').removeClass('toggled');
        $('.primary-menu-list').removeClass('toggled');
        $('body').removeClass('menu-open');
        $('body').removeClass('showing-modal');
    });

    $('<button class="submenu-toggle"><i class="fa fa-angle-down"></i></button>').insertAfter($('.mobile-menu-wrapper ul .menu-item-has-children > a'));
    $('.mobile-menu-wrapper ul li .submenu-toggle').on('click', function () {
        $(this).next().slideToggle();
        $(this).toggleClass('active');
    });

    //wrap widget title content with span
    $('#secondary .widget-title, .site-footer .widget-title').wrapInner('<span class="title-wrap"></span>');

    //calculate header height
    var headerHeight = $('header.site-header').outerHeight();
    $('header.page-header, body.home:not(.hasbanner) .site').css('padding-top', headerHeight);
    $('.site-header + .site-banner .banner-caption').css('padding-top', headerHeight);

    //banner countdown
    if (!(the_conference_data.banner_event_timer === undefined || the_conference_data.banner_event_timer.length == 0)) {
        $('#bannerClock .days').countdown(the_conference_data.banner_event_timer, function (event) {
            $(this).html(event.strftime('%D'));
        });
        $('#bannerClock .hours').countdown(the_conference_data.banner_event_timer, function (event) {
            $(this).html(event.strftime('%H'));
        });
        $('#bannerClock .minutes').countdown(the_conference_data.banner_event_timer, function (event) {
            $(this).html(event.strftime('%M'));
        });
        $('#bannerClock .seconds').countdown(the_conference_data.banner_event_timer, function (event) {
            $(this).html(event.strftime('%S'));
        });
    }

    //custom scroll bar
    if ($('.widget_rrtc_description_widget').length) {
        $('.description').each(function () {
            var ps = new PerfectScrollbar($(this)[0]);
        });
    }

    // Fix for Accessibility in Edge
    $("#site-navigation ul li a").on('focus', function () {
        $(this).parents("li").addClass("hover");
    }).on('blur', function () {
        $(this).parents("li").removeClass("hover");
    });

    $(".widget_rrtc_description_widget a").on('focus', function () {
        $(this).parents(".widget_rrtc_description_widget").addClass("hover");
    }).on('blur', function () {
        $(this).parents(".widget_rrtc_description_widget").removeClass("hover");
    });


    window.addEventListener('resize', function () {
        let viewportWidth = window.innerWidth;
        console.log(viewportWidth);
        if (viewportWidth >= 1025) {
            document.body.classList.remove('showing-modal');
        }
    });
});