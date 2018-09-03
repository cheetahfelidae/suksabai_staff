function RoomRatesSelection() {
    var numSelectedRooms;
    var typeClick;
    var iterRoomCount;
    var roomModels;
    var stayDates;
    var selectedRooms;
    this.getStayDates = function () {
        return stayDates;
    };
    this.getSelectedRooms = function () {
        return selectedRooms;
    };
    this.initialise = function () {
        // auto scroll to top left of page
        window.scrollTo(0, 0);
        typeClick = "";
        // get number of selected rooms
        numSelectedRooms = dateSelection.getNumSelectedRooms();
        // clear selected rooms and prices of recently transaction
        sidebar.clearSelectedRoomsShow();
        stayDates = dateSelection.get_stay_dates();
        roomModels = dateSelection.get_room_models();
        showAvailRoomModels();
        // clear selected rooms out
        selectedRooms = [];
        // start to select room type for the first room
        iterRoomCount = 0;
        // update select rooms & rates' sidebar
        showSelectedRoomsRatesBreadcrumbs();
        sidebar.clearTotalPrice();
        // target breadcrumb is switched
        $('.custom-breadcrumb a').removeClass('active');
        $('#selectedRoomsRates-tab-breadcrumb').addClass('active');
        // target tab is switched
        $('.tabs-content section.content').removeClass("active");
        $('#selectedRoomsRates-tab').addClass("active");
    };

    var showAvailRoomModels = function () {
        // clear all and add legend at first
        $('#roomTypes-fieldset').html('<legend>เลือกหมายเลขห้อง</legend>');
        // if there are enough rooms left, then show available rooms
        if (( numSelectedRooms - selectedRooms.length ) <= roomModels.length) {
            var curType = "";
            var countRooms = 0;
            for (var i = 0; i < roomModels.length; i++) {
                var type = roomModels[i].type;
                // create new price table for each room type
                if (curType.localeCompare(type) !== 0) {
                    curType = type;
                    countRooms = 0;
                    $('#roomTypes-fieldset').append('<div class="row collapse">\
                                        <div class="small-12 columns">\
                                            <ul class="pricing-table">\
                                                <li class="title">ห้องประเภท ' + curType + '</li>\
                                                <li class="price">\
                                                    <div class="row large-collapse">\
                                                        <div class="large-offset-2 large-4 columns">\
                                                            <label for="roomType' + curType + '-price" class="right inline">ราคาห้อง/คืน</label>\
                                                        </div>\
                                                        <div class="large-2 columns end">\
                                                            <input type="number" placeholder="ราคา" id="roomType' + curType + '-price" min="50" step="1" value="' + roomModels[i].curPrice + '" required />\
                                                            <small class="error">ราคาห้องควรมีค่ามากกว่า 50 บาท</small>\
                                                        </div>\
                                                    </div>\
                                                </li>\
                                                <li class="description">' + roomModels[i].brief + '</li>\
                                                <li class="bullet-item">\
                                                     <div class="row large-collapse">\
                                                        <div class="large-4 columns">\
                                                            <label for="roomType' + curType + '-breakfastBed-check" class="right inline">อาหารเช้า ' + BREAKFAST_PRICE + ' บาท</label>\
                                                        </div>\
                                                        <div class="large-2 columns end">\
                                                            <select id="roomType' + curType + '-breakfastBed-check" class="noBottomMargin">\
                                                                    <option value="0" selected>0</option>\
                                                                    <option value="1">1</option>\
                                                                    <option value="2">2</option>\
                                                                    <option value="3">3</option>\
                                                                    <option value="4">4</option>\
                                                            </select>\
                                                        </div>\
                                                        <div class="large-6 columns">\
                                                            <input id="roomType' + curType + '-extraBed-check" type="checkbox">\
                                                            <label for="roomType' + curType + '-extraBed-check" class="right inline">เพิ่มเตียงเสริม ' + EXTRA_BED_PRICE + ' บาท</label>\
                                                        </div>\
                                                    </div>\
                                                </li>\
                                                <li class="bullet-item">\
                                                    <div class="row">\
                                                        <div class="large-4 columns">\
                                                            <ul class="no-bullet" id="type' + curType + 'List-stCol"></ul>\
                                                        </div>\
                                                        <div class="large-4 columns">\
                                                            <ul class="no-bullet" id="type' + curType + 'List-ndCol"></ul>\
                                                        </div>\
                                                        <div class="large-4 columns">\
                                                            <ul class="no-bullet" id="type' + curType + 'List-rdCol"></ul>\
                                                        </div>\
                                                    </div>\
                                                </li>\
                                                <li class="cta-button"><button type="submit" id="type' + curType + '-next-toEntGuDetails-butt">เลือกห้องนี้</a></li>\
                                            </ul>\
                                        </div>\
                                    </div>');
                }
                var roomNo = roomModels[i].num;
                switch (countRooms % 3) {
                    case 0:
                        $('#type' + curType + 'List-stCol').append('<li>\
                                                        <input type="radio" name="roomNo-radio" value="' + roomNo + '" id="room' + roomNo + '" required >\
                                                        <label for="room' + roomNo + '">ห้อง ' + roomNo + '</label>\
                                                    </li>');
                        break;
                    case 1:
                        $('#type' + curType + 'List-ndCol').append('<li>\
                                                        <input type="radio" name="roomNo-radio" value="' + roomNo + '" id="room' + roomNo + '" required >\
                                                        <label for="room' + roomNo + '">ห้อง ' + roomNo + '</label>\
                                                    </li>');
                        break;
                    case 2:
                        $('#type' + curType + 'List-rdCol').append('<li>\
                                                        <input type="radio" name="roomNo-radio" value="' + roomNo + '" id="room' + roomNo + '" required >\
                                                        <label for="room' + roomNo + '">ห้อง ' + roomNo + '</label>\
                                                    </li>');
                }
                countRooms++;
            }
        }
        else {
            // ask user to move back to select date again
            $('#noRoomAvail-reveal').foundation('reveal', 'open');
        }
    };
    var showSelectedRoomsRatesBreadcrumbs = function () {
        $('#breadcrumbs-selectedRoomsRates').text("เลือกประเภทห้องที่ " + ( iterRoomCount + 1 ) + ' จาก ' + numSelectedRooms);
        $('#breadcrumbs-selectedRoomsRates-panel').stop(false, true).effect('highlight', {
            color: '#f2c922'
        }, 3000);
    };
    this.createNextButt = function () {
        typeClick = "";
        $('#roomTypes-form').on('valid.fndtn.abide', function () {
            switch (typeClick) {
                case "A":
                    selectRoom("A");
                    break;
                case "B":
                    selectRoom("B");
                    break;
                case "C":
                    selectRoom("C");
                    break;
            }
        });
        $('body').delegate('#typeA-next-toEntGuDetails-butt', "click", function () {
            typeClick = "A";
        });
        $('body').delegate('#typeB-next-toEntGuDetails-butt', "click", function () {
            typeClick = "B";
        });
        $('body').delegate('#typeC-next-toEntGuDetails-butt', "click", function () {
            typeClick = "C";
        });
    };
    var selectRoom = function (type) {
        // start loading animation
        startLoadingAni();
        var numAd = parseInt($('#selectedRoomsGuests-list > .row:nth-child(' + ( iterRoomCount + 2 ) + ') > .columns:nth-child(2) option:selected').text()),
            numCh = parseInt($('#selectedRoomsGuests-list > .row:nth-child(' + ( iterRoomCount + 2 ) + ') > .columns:nth-child(3) option:selected').text()),
            num = parseInt($('input:radio[name="roomNo-radio"]:checked').val()),
            price = parseInt($('#roomType' + type + '-price').val()),
            breakfastPrice = BREAKFAST_PRICE * parseInt($('#roomType' + type + '-breakfastBed-check option:selected').text()),
            extraBedPrice = $('#roomType' + type + '-extraBed-check').is(':checked') ? EXTRA_BED_PRICE : 0;

        // create rooms ( up to the number of night )
        $.each(stayDates, function (i, date) {
            selectedRooms.push(new SelectRoom(num, type, price, numAd, numCh, breakfastPrice, extraBedPrice, date));
        });
        console.log(selectedRooms);
        rmvRoomModelByNum(num);

        // move to either next rooms or enter guest's details
        nextSelectedRoomsRates();
    };
    var rmvRoomModelByNum = function (num) {
        var startLength = roomModels.length;
        roomModels = $.grep(roomModels, function (rModels) {
            return rModels.num !== num;
        });
        if (startLength <= roomModels.length) {
            console.log("rmvRoomModel by room number : not found room model by number!!!");
        }
    };
    var nextSelectedRoomsRates = function () {
        // move to next room if there are more than one requesting room
        iterRoomCount++;
        if (iterRoomCount < numSelectedRooms) {
            // auto scroll to top left of page
            window.scrollTo(0, 0);
            showSelectedRoomsRatesBreadcrumbs();
            showAvailRoomModels();
        }
        else {
            // move to enter guest's detail
            guestForm.initialise();
        }
        // update sidebar
        sidebar.showSelectedRooms();
        sidebar.showTotalPrice();
        // stop loading animation
        stopLoadingAni();
    };
    this.createBackButt = function () {
        $('.back-toSelectDates-butt').click(function () {
            // refresh page instead
            location.reload();
        });
    };
}