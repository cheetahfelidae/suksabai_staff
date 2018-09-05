function Confirmation() {
    var guest;
    var selectedRooms;
    this.initialise = function (no_guest) {
        // auto scroll to top left of page
        window.scrollTo(0, 0);

        guest = guestForm.getGuest();
        if (no_guest) {
            guest.id = -1;
        }
        createReceipt(no_guest);

        if (guest.id === 0) {
            $('#formLegend-confirmation-tab').html('(ผู้พักเก่า)');
        } else if (guest.id === -1) {
            $('#formLegend-confirmation-tab').html('(ไม่มีข้อมูลผู้พัก');
        } else {
            $('#formLegend-confirmation-tab').html('(ผู้พักใหม่)');
        }

        // target breadcrumb is switched
        $('.custom-breadcrumb a').removeClass('active');
        $('#confirmation-tab-breadcrumb').addClass('active');
        // target tab is switched
        $('.tabs-content section.content').removeClass("active");
        $('#confirmation-tab').addClass("active");
    };
    this.createConfirmButt = function () {
        $('#confirm-butt').click(function () {
            startLoadingAni();

            URL = phpUrl;
            if (guest.id === 0) {
                URL += "booking/booking-storedGuest.php";
            } else if (guest.id === -1) {
                URL += "booking/booking_no_guest";
            } else {
                URL += "booking/booking-newGuest.php";
            }

            $.ajax({
                type: "POST",
                contentType: "application/json; charset=utf-8",
                url: URL,
                data: JSON.stringify({
                    "guest": guest,
                    "selectedRooms": selectedRooms
                }),
                success: function (response) {
                    if (response.length === 0) {
                        window.print();
                        $('#bookingSuccess-reveal').foundation('reveal', 'open');
                    }
                    else {
                        showErrorReveal(response);
                    }
                    stopLoadingAni();
                }
            });
        });
    };
    var createReceipt = function (noGuest) {
        if (!noGuest) {
            $('#name-confirmation-tab').val(guest.title + " " + guest.firstName + " " + guest.lastName);
            $('#name-receipt').html(guest.title + " " + guest.firstName + " " + guest.lastName);
            $('#email-confirmation-tab').val(guest.email);
            $('#email-receipt').html(guest.email);
            $('#tel-confirmation-tab').val(guest.tel);
            $('#tel-receipt').html(guest.tel);
            $('#address-confirmation-tab').val(guest.address + " " + guest.district + " " + guest.amphur + " " + guest.province);
            $('#address-receipt').html(guest.address + " " + guest.district + " " + guest.amphur + " " + guest.province);
            // check if taxNumber is specified
            if ((guest.taxNumber).length > 0) {
                $('#taxNumber-confirmation-tab').val(guest.taxNumber);
                $('#taxCaption-receipt').html('หมายเลขประจำตัวผู้เสียภาษีอากร :');
                $('#taxNumber-receipt').html(guest.taxNumber);
            }
            else {
                $('#taxNumber-confirmation-tab').val('');
                $('#taxCaption-receipt').html('');
                $('#taxNumber-receipt').html('');
            }
            // check if other details are specified
            (guest.otherDetails.length === 0) ? $('#otherDetails-confirmation-tab').val("ไม่มีข้อมูลอื่นๆ..") : $('#otherDetails-confirmation-tab').val(guest.otherDetails);
        }

        $('#printTimeDate-receipt').html(clock.getTimeDate());
        $('#stayList-receipt').empty();
        selectedRooms = roomRatesSelection.getSelectedRooms();
        var sumRoomPrice = 0,
            sumBreakfastPrice = 0,
            sumExtraBedPrice = 0,
            num_dates = getNumDates($('#arriv-input').datepicker("getDate"), $('#depar-input').datepicker("getDate"));

        for (var i = 0; i < selectedRooms.length; i++) {
            if (typeof selectedRooms[i].type !== 'undefined') {
                $('#stayList-receipt').append('<tr>\
                                    <td class="text-center">' + yy_mm_dd_to_dd_mm_yy(selectedRooms[i].checkinDate) + '</td>\
                                    <td class="text-center">' + yy_mm_dd_to_dd_mm_yy(selectedRooms[i].checkoutDate) + '</td>\
                                    <td class="text-center">ห้อง ' + selectedRooms[i].num + '</td>\
                                    <td class="text-center">฿' + selectedRooms[i].price + '</td>\
                                </tr>');
                sumRoomPrice += selectedRooms[i].price * num_dates;
                // show breakfast price
                if (selectedRooms[i].breakfastPrice > 0) {
                    $('#stayList-receipt').append('<tr>\
                                    <td class="text-center"></td>\
                                    <td class="text-center">อาหารเช้า</td>\
                                    <td class="text-center">฿' + selectedRooms[i].breakfastPrice + ' (จำนวน ' + (selectedRooms[i].breakfastPrice / BREAKFAST_PRICE) + ' ที่)</td>\
                                </tr>');
                    sumBreakfastPrice += selectedRooms[i].breakfastPrice * num_dates;
                }
                // show extra bed price
                if (selectedRooms[i].extraBedPrice > 0) {
                    $('#stayList-receipt').append('<tr>\
                                    <td class="text-center"></td>\
                                    <td class="text-center">เตียงเสริม</td>\
                                    <td class="text-center">฿' + selectedRooms[i].extraBedPrice + '</td>\
                                </tr>');
                    sumExtraBedPrice += selectedRooms[i].extraBedPrice * num_dates;
                }
            }
            else {
                showErrorReveal("typeof selectedRooms[ i ].type === 'undefined'");
            }
        }
        $('#numDates-receipt').html($('#numDates-dateSelection').html());
        $('#sumRoomPrice-receipt').html('ราคารวมห้องพัก : ฿' + sumRoomPrice);
        if (sumBreakfastPrice > 0) {
            $('#sumBreakfastPrice-receipt').html('ราคารวมอาหารเช้า : ฿' + sumBreakfastPrice);
        }
        if (sumExtraBedPrice > 0) {
            $('#sumExtraBedPrice-receipt').html('ราคารวมเตียงเสริม : ฿' + sumExtraBedPrice);
        }
        $('#total-receipt').html('ราคารวมสุทธิ : ฿' + (sumRoomPrice + sumBreakfastPrice + sumExtraBedPrice));
        $('#checkInDate-receipt').html($('#arriv-time-input').val() + ' &nbsp; ' + date_obj_to_dd_mm_yy($('#arriv-input').datepicker("getDate")));
        $('#checkOutDate-receipt').html($('#depar-time-input').val() + ' &nbsp; ' + date_obj_to_dd_mm_yy($('#depar-input').datepicker("getDate")));
    };
    this.createBackButt = function () {
        $('#back-toEntGuDetailsTab').click((function () {
            // target breadcrumb is switched
            $('.custom-breadcrumb a').removeClass('active');
            $('#guest-form-tab-breadcrumb').addClass('active');
            // target tab is switched
            $('.tabs-content section.content').removeClass("active");
            $('#guest-form-tab').addClass("active");
        }));
    };
}