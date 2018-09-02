function DateSelection() {
    var numSelectedRooms = 0;
    this.getNumSelectedRooms = function () {
        return numSelectedRooms;
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
                showNumDates();
            }
        });
        $('#depar-input').datepicker({
            onSelect: function (selectedDate) {
                var max = $('#depar-input').datepicker('getDate');
                max.setDate(max.getDate() - 1);
                $('#arriv-input').datepicker("option", "maxDate", max);
                showNumDates();
            }
        });
        $("#arriv-butt").click(function () {
            $('#arriv-input').datepicker("show");
        });
        $("#depar-butt").click(function () {
            $('#depar-input').datepicker("show");
        });
    };
    var showNumDates = function () {
        var arr = $('#arriv-input').datepicker("getDate");
        var dep = $('#depar-input').datepicker("getDate");
        if (arr !== null && dep !== null) {
            $('#numDates-dateSelection').html("จำนวนคืนที่พัก : " + getNumDates(arr, dep) + " คืน");
        }
        else {
            $('#numDates-dateSelection').html("");
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
            for (var i = 0; i < numSelectedRooms; i++) $('#selectedRoomsGuests-list').append('<div class="row">\
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
                    content: "วันเวลาจอง : " + eventObj.description,
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
            $('#arriv-input, #depar-input').attr("disabled", "disabled");
            // hide these elements
            $('#numRooms-select-row, #arriv-butt, #depar-butt').hide();
            roomRatesSelection.initialise();
        });
    };
}