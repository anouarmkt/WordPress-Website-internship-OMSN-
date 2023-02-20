(function($, elementor) {
    "use strict";
    var etn = {

        init: function() {
            var widgets = {
                'etn-countdown.default': etn.Etn_Count_Down,
                'etn-event-calendar.default': etn.Etn_Event_Calendar,
            };
            $.each(widgets, function(widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });

        },
        //Countdown start
        Etn_Count_Down: function($scope) {
            var $container = $scope.find('.etn_countdown');
            var date_time = $container.data("event_time");
            if ($container.length > 0) {
                $container.jCounter({
                    date: date_time,
                    serverDateSource: '',

                    fallback: function() {

                    }
                });
            }

        },

    };
    $(window).on('elementor/frontend/init', etn.init);
}(jQuery, window.elementorFrontend));