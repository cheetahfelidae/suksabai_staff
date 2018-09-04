function DateSelection() {
    var numSelectedRooms = 0;
    var roomModels;
    var checkinDates;
    this.getNumSelectedRooms = function () {
        return numSelectedRooms;
    };
    var after_select_date_period = function () {
        var arr = $('#arriv-input').datepicker("getDate");
        var dep = $('#depar-input').datepicker("getDate");

        if (arr && dep) {
            $('#numDates-dateSelection').html("จำนวนคืนที่พัก : " + getNumDates(arr, dep) + " คืน");
            checkinDates = generateStayDates();
            roomModels = importRoomModel();
        } else {
            $('#numDates-dateSelection').html("");
        }
    };
    this.createArrivDeparSelector = function () {
        $.datepicker.setDefaults({
            dateFormat: "DD d MM yy"
        });
        $('#arriv-input').datepicker({
            onSelect: function (selectedDate) {
                var min = $('#arriv-input').datepicker('getDate');
                min.setDate(min.getDate() + 1);
                $('#depar-input').datepicker("option", "minDate", min).datepicker("setDate", min);
                after_select_date_period();
            }
        });
        $('#arriv-time-input').datetimepicker({
            datepicker: false,
            format: 'H:i'
        });
        $('#depar-input').datepicker({
            onSelect: function (selectedDate) {
                var max = $('#depar-input').datepicker('getDate');
                max.setDate(max.getDate() - 1);
                $('#arriv-input').datepicker("option", "maxDate", max);
                after_select_date_period();
            }
        });
        $("#arriv-butt").click(function () {
            $('#arriv-input').datepicker("show");
        });
        $('#arriv-time-butt').click(function () {
            $('#arriv-time-input').datetimepicker("show");
        });
        $("#depar-butt").click(function () {
            $('#depar-input').datepicker("show");
        });
    };
    var generateStayDates = function () { // TODO - to be removed
        // clear the array of staying dates
        checkinDates = [];
        // define the interval of staying dates
        var curSelectDate = $('#arriv-input').datepicker("getDate"),
            endSelectDate = $('#depar-input').datepicker("getDate");
        // create a loop between the interval
        while (curSelectDate < endSelectDate) {
            // add it (as string) on array
            checkinDates.push(date_obj_to_yy_mm_dd(curSelectDate));
            // add one day
            curSelectDate = curSelectDate.addDays(1);
        }
        // error handling
        if (checkinDates.length <= 0) {
            showErrorReveal("checkinDates variable is null");
        }

        return checkinDates;
    };
    var importRoomModel = function () {
        roomModels = [];
        startLoadingAni();
        $.ajax({
            type: "POST",
            url: phpUrl + "booking/roomModelsRetriever.php",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({
                "start": checkinDates[0],
                "end": checkinDates[checkinDates.length - 1]
            }),
            success: function (data) {
                if (isJson(data)) {
                    $.each(jQuery.parseJSON(data), function (i, room) {
                        roomModels.push(new RoomModel(parseInt(room["num"]), room["type"], parseInt(room["curPrice"]), room["brief"]));
                    });
                    get_ava_num_rooms();
                }
                else {
                    showErrorReveal(data);
                }
                stopLoadingAni();
            }
        });

        return roomModels;
    };
    var get_ava_num_rooms = function () {
        $('#numRooms-select-row').hide();
        $('#numRooms-select').html('');

        if (roomModels.length > 0) {
            $('#numRooms-select-row').show();
            for (var i = 1; i <= roomModels.length; i++) {
                $('#numRooms-select').append('<option value="' + i + '">' + i + '</option>')
            }
            $('#numRooms-select option[value="1"]').attr('selected', true).change();
        } else {
            $('#noRoomAvail-reveal').foundation('reveal', 'open');
        }
    };
    this.createRoomsGuestsSelector = function () {
        $('#numRooms-select').change(function () {
            numSelectedRooms = $('#numRooms-select').val();
            $('#selectedRoomsGuests-list').html('<div class="row">\
                                                            <div class="small-4 columns right">\
                                                                <label class="text-center">เด็ก</label>\
                                                            </div>\
                                                            <div class="small-4 columns">\
                                                                <label class="text-center">ผู้ใหญ่</label>\
                                                            </div>\
                                                        </div>');
            // add them
            for (var i = 0; i < numSelectedRooms; i++) {
                $('#selectedRoomsGuests-list').append('<div class="row">\
                                                            <div class="small-4 columns">\
                                                                <label class="text-center inline">ห้อง ' + (i + 1) + '</label>\
                                                            </div>\
                                                            <div class="small-4 columns">\
                                                                <select>\
                                                                    <option value="1">1</option>\
                                                                    <option value="2">2</option>\
                                                                    <option value="3">3</option>\
                                                                    <option value="4">4</option>\
                                                                    <option value="5">5</option>\
                                                                </select>\
                                                            </div>\
                                                            <div class="small-4 columns">\
                                                                <select>\
                                                                    <option value="0">0</option>\
                                                                    <option value="1">1</option>\
                                                                    <option value="2">2</option>\
                                                                    <option value="3">3</option>\
                                                                </select>\
                                                            </div>\
                                                        </div>');
            }
        });
        $('#numRooms-select').trigger('change');
    };
    this.createCalendar = function () {
        $('#calendar').fullCalendar({
            eventColor: '#16a085',
            eventLimit: true,
            eventLimitText: "ห้อง",
            eventRender: function (eventObj, $el) {
                $el.popover({
                    title: "ห้อง " + eventObj.title,
                    content: "เข้าพักเวลา : " + eventObj.checkinTime + "\r\n\t\nวันเวลาจอง : " + eventObj.addDate,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
            },
            viewRender: function (view, element) {
                $('#calendar table').css('background-color', 'transparent');
                $('#calendar tr.fc-minor').attr('style', 'background:initial;');
                $('#calendar table').css('margin-bottom', '0');
            }
        });
        var view = $('#calendar').fullCalendar('getView');
        // title prev and next buttons
        updateCalendar();
        $('#prev-calendar-butt').click(function () {
            $('#calendar').fullCalendar('prev');
            updateCalendar();
        });
        $('#next-calendar-butt').click(function () {
            $('#calendar').fullCalendar('next');
            updateCalendar();
        });

        function updateCalendar() {
            $('#calendar').fullCalendar('removeEvents');
            $('#calendar-title').html(view.title);
            // initialise the calendar
            $.ajax({
                type: "POST",
                url: phpUrl + "booking/occRoomsRetriever.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    "start": view.start.format(),
                    "end": view.end.format()
                }),
                success: function (data) {
                    if (isJson(data)) {
                        $('#calendar').fullCalendar('addEventSource', jQuery.parseJSON(data));
                    }
                    else if (data !== "json is empty") {
                        showErrorReveal(data);
                    }
                }
            });
        }
    };
    this.createNextButt = function () {
        $('#arrivDeparForm').on('valid.fndtn.abide', function () {
            // show these elements
            $('#breadcrumbs-selectedRoomsRates-row, #selectedRooms-list-row, #totalPrice-row').show();
            // disable arrive / depart text-input
            $('#arriv-input, #arriv-time-input, #depar-input').attr("disabled", "disabled");
            // hide these elements
            $('#numRooms-select-row, #arriv-butt, #arriv-time-butt, #depar-butt').hide();
            roomRatesSelection.initialise();
        });
    };
    this.get_room_models = function () {
        return roomModels;
    };
    this.get_check_in_dates = function () {
        return checkinDates;
    }
}