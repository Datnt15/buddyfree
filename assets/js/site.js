var clndr = {};

$(function() {

    // PARDON ME while I do a little magic to keep these events relevant for the rest of time...
    var currentMonth = moment().format('YYYY-MM');
    var currentYear = moment().format('YYYY');
    var currentMonthName = moment().format('MMMM');
    var nextMonth = moment().add('month', 1).format('YYYY-MM');
    jQuery.post(
        ajaxurl, {
            action: 'get_schedule_by_id_ajax'
        },
        function(schedule_data) {
            schedule_data = JSON.parse(schedule_data.toString());
            var schedules = [];
            for (var i = 0; i < schedule_data.length; i++) {
                var t = new Date(parseInt(schedule_data[i].day));
                schedules.push({
                    date: t.getFullYear() + '-' + (t.getMonth() + 1) + '-' + t.getDate(),
                    schedule: schedule_data[i].time,
                    timestamp: schedule_data[i].day

                });
            }
            $('#mini-clndr').clndr({
                template: $('#mini-clndr-template').html(),
                events: schedules,
                clickEvents: {
                    click: function(target) {
                        var daysContainer = $('#mini-clndr').find('.days-container');
                        daysContainer.toggleClass('show-events', true);
                        $("#detail_" + $(target.element).attr('id')).toggleClass('show-detail', true);
                        $('#mini-clndr').find('.x-button').click(function() {
                            daysContainer.toggleClass('show-events', false);
                            $("#detail_" + $(target.element).attr('id')).toggleClass('show-detail', false);
                        });
                    }
                },
                month: currentMonthName,
                year: currentYear,
                eventsThisMonth: schedules,
                adjacentDaysChangeMonth: true,
                forceSixRows: true
            });
            for (var i = 0; i < schedules.length; i++) {
                var schedule = schedules[i].schedule;
                if (schedule != '') {
                    schedule = JSON.parse(schedule);
                    for (var j = 0; j < schedule.length; j++) {
                        $("#detail_" + schedules[i].timestamp).find("input[value='" + schedule[j] + "']").each(function() {
                            $(this).attr('checked', 'checked');
                        });
                    }
                }
            }
        }
    );

    $('#mini-clndr').on('click', '.add-schedule-btn', function() {
        var _this = $(this);
        var times = [];
        _this.parent().find('input[name="time[]"]:checked').each(function(i) {
            times.push($(this).val());
        });
        if (!times.length) {
            alert("You need to choose some periods before saving them!")
            return;
        }
        var overlay = $("#mini-clndr .clndr .days-container .overlayer");
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'add_schedule_ajax',
                time: times,
                date: _this.parent().attr('id').replace('detail_', '')
            },
            beforeSend: function() {
                overlay.css('display', 'block');
            },
            success: function(data) {
                // if (data == '1') {
                overlay.html('<i class="fa fa-check-square-o fa-3x" style="color:#69a776 !important;" aria-hidden="true"></i>' +
                    '<h4 style="color:#69a776 !important;">Saving successfully!</h4>');
                $("#" + _this.parent().attr('id').replace('detail_', '')).addClass('event');
                // }
                // else {
                // overlay.html('<i class="fa fa-exclamation-triangle fa-3x" style="color:#F48024 !important;" aria-hidden="true"></i>' +
                // '<h4>Some errors occured. Please reload this page!</h4>');
                // }
                return false;
            },
            complete: function() {
                overlay.html('<i class="fa fa-check-square-o fa-3x" style="color:#69a776 !important;" aria-hidden="true"></i>' +
                    '<h4 style="color:#69a776 !important;">Saving successfully!</h4>');
                $("#" + _this.parent().attr('id').replace('detail_', '')).addClass('event');
            }
        }).done(function() {
            overlay.fadeOut(2000, function() {
                overlay.css('display', 'none');
                overlay.html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
            });
            // $('#mini-clndr').find('.x-button').click();
        });

    });


});
