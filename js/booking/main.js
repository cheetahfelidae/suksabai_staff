// global varibales
var dateSelection;
var sidebar;
var roomRatesSelection;
var guestForm;
var confirmation;
var clock;
// constant variable
var BREAKFAST_PRICE = 100;
var EXTRA_BED_PRICE = 150;

// classes
function Guest(id, title, firstName, lastName, tel, address, district, amphur, province) {
    this.id = id;
    this.title = title;
    this.firstName = firstName;
    this.lastName = lastName;
    // optional
    this.email = "";
    this.tel = tel;
    this.address = address;
    this.district = district;
    this.amphur = amphur;
    this.province = province;
    // optional
    this.taxNumber = "";
    // optional
    this.otherDetails = "";
}

function SelectRoom(num, type, price, numAdults, numChildren, breakfastPrice, extraBedPrice, stayDate, checkinTime) {
    this.num = num;
    this.type = type;
    this.price = price;
    this.numAdults = numAdults;
    this.numChildren = numChildren;
    this.breakfastPrice = breakfastPrice;
    this.extraBedPrice = extraBedPrice;
    this.stayDate = stayDate;
    this.checkinTime = checkinTime;
}

function RoomModel(num, type, curPrice, brief) {
    this.num = num;
    this.type = type;
    this.curPrice = curPrice;
    this.brief = brief;
}

/**************************************************************************************************************************/
/******************************************************** Main Fn *********************************************************/
/**************************************************************************************************************************/
$(document).ready(function () {
    // start with date selection step
    dateSelection = new DateSelection();
    dateSelection.createArrivDeparSelector();
    dateSelection.createRoomsGuestsSelector();
    dateSelection.createCalendar();
    dateSelection.createNextButt();
    // room rates selection
    roomRatesSelection = new RoomRatesSelection();
    roomRatesSelection.createNextButt();
    roomRatesSelection.createBackButt();
    // guest form
    guestForm = new GuestForm();
    guestForm.createNextButt();
    guestForm.createBackButt();
    guestForm.createResetButt();
    guestForm.createEditableFormButt();
    // confirmation
    confirmation = new Confirmation();
    confirmation.createConfirmButt();
    confirmation.createBackButt();
    // sidebar
    sidebar = new Sidebar();
    // create clock for page
    clock = new Clock();
    clock.initialise("th");
    // set all reveals
    createReloadPageButt();
    disableCloseOnBgReveal();
});

function Sidebar() {
    var curr_date = new Date();
    $('#arriv-time-input').val(curr_date.getHours() + ":" + curr_date.getMinutes());

    this.clearSelectedRoomsShow = function () {
        $('#selectedRooms-list-fieldset').html('<legend>ห้องที่เลือก</legend>');
    };
    this.clearTotalPrice = function () {
        $('#totalPrice').val("");
    };
    this.showSelectedRooms = function () {
        this.clearSelectedRoomsShow();
        $('#selectedRooms-list-fieldset').append('<div class="row">\
                                                    <div class="small-3 columns right">\
                                                        <label class="text-center">ราคา</label>\
                                                    </div>\
                                                    <div class="small-3 columns right">\
                                                        <label class="text-center">เด็ก</label>\
                                                    </div>\
                                                    <div class="small-3 columns">\
                                                        <label class="text-center">ผู้ใหญ่</label>\
                                                    </div>\
                                                </div>');
        var numStayDates = (roomRatesSelection.getStayDates()).length;
        var selectedRooms = roomRatesSelection.getSelectedRooms();
        for (var i = 0; i < selectedRooms.length; i++) {
            if (typeof selectedRooms[i].type !== 'undefined') {
                // draw bottom border to separate each room numbers
                if (i !== 0 && i % numStayDates === 0) {
                    $('#selectedRooms-list-fieldset').append('<div class="row collapse">\
                                                    <div class="small-12 columns">\
                                                        <p class="withBottomBorder withTopMargin"></p>\
                                                    </div>\
                                                </div>');
                }
                // show text if number of stay dates are more than one
                if (numStayDates > 1) {
                    $('#selectedRooms-list-fieldset').append('<div class="row collapse">\
                                                    <div class="small-12 columns">\
                                                        <p class="text-left noBottomMargin" style="font-weight: bold;">คืนที่ ' + ((i % numStayDates) + 1) + ' / ' + numStayDates + '</p>\
                                                    </div>\
                                                </div>');
                }
                $('#selectedRooms-list-fieldset').append('<div class="row collapse">\
                                                    <div class="small-3 columns">\
                                                        <label class="text-center inline noBottomMargin">' + selectedRooms[i].type + ' ' + selectedRooms[i].num + '</label>\
                                                    </div>\
                                                    <div class="small-3 columns">\
                                                        <input type="text" class="text-center noBottomMargin" value="' + selectedRooms[i].numAdults + '" disabled>\
                                                    </div>\
                                                    <div class="small-3 columns">\
                                                        <input type="text" class="text-center noBottomMargin" value="' + selectedRooms[i].numChildren + '" disabled>\
                                                    </div>\
                                                    <div class="small-3 columns">\
                                                        <input type="text" class="text-center noBottomMargin" value="' + selectedRooms[i].price + '" disabled>\
                                                    </div>\
                                                </div>');
                if (selectedRooms[i].breakfastPrice > 0) {
                    $('#selectedRooms-list-fieldset').append('<div class="row collapse">\
                                                    <div class="small-3 columns right">\
                                                        <input type="text" class="text-center noBottomMargin" value="' + selectedRooms[i].breakfastPrice + '" disabled>\
                                                    </div>\
                                                    <div class="small-6 columns">\
                                                        <label class="text-center inline noBottomMargin">อาหารเช้า</label>\
                                                    </div>\
                                                </div>');
                }
                // show extra bed price
                if (selectedRooms[i].extraBedPrice > 0) {
                    $('#selectedRooms-list-fieldset').append('<div class="row collapse">\
                                                    <div class="small-3 columns right">\
                                                        <input type="text" class="text-center noBottomMargin" value="' + selectedRooms[i].extraBedPrice + '" disabled>\
                                                    </div>\
                                                    <div class="small-6 columns">\
                                                        <label class="text-center inline noBottomMargin">เตียงเสริม</label>\
                                                    </div>\
                                                </div>');
                }
            }
            else {
                showErrorReveal("typeof selectedRooms[ i ].type === 'undefined'");
            }
        }
    };
    this.showTotalPrice = function () {
        var total = 0;
        var selectedRooms = roomRatesSelection.getSelectedRooms();
        for (var i = 0; i < selectedRooms.length; i++) {
            total += selectedRooms[i].price;
            total += selectedRooms[i].breakfastPrice;
            total += selectedRooms[i].extraBedPrice;
        }
        $('#totalPrice').val(total);
    };
}