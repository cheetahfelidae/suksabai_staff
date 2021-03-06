// edit php's url here
var phpUrl = "php/";
var alt_phpUrl = "../php/";
/**************************************************************************************************************************/
/***************************************************** Utilities Fn *******************************************************/
/**************************************************************************************************************************/
Date.prototype.addDays = function (days) {
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
};

function isJson(str) {
    try {
        JSON.parse(str);
    }
    catch (e) {
        return false;
    }
    return true;
}

function startLoadingAni() {
    $('.spinner-overlay').fadeIn(500);
}

function stopLoadingAni() {
    $('.spinner-overlay').fadeOut(500);
}

function disableCloseOnBgReveal() {
    $('.reveal-modal').foundation({
        reveal: {
            close_on_background_click: false
        }
    });
}

function showErrorReveal(message) {
    $('#error-reveal').foundation('reveal', 'open');
    $('#error-details-para').html(message);
}

function createReloadPageButt() {
    $('.reloadPage-butt').click(function () {
        // refresh page instead
        location.reload();
    });
}

/**************************************************************************************************************************/
/******************************************************* Stinrg ***********************************************************/

/**************************************************************************************************************************/
function isStringEmpty(str) {
    return (!str || 0 === str.length);
}

/**************************************************************************************************************************/
/**************************************************** Date Time ***********************************************************/

/**************************************************************************************************************************/
function getDaysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate();
}

function yy_mm_dd_to_dd_mm_yy(date) {
    var d = (date).split("-");
    return d[2] + "/" + d[1] + "/" + d[0];
}

function date_obj_to_yy_mm_dd(date) {
    return $.datepicker.formatDate("yy-mm-dd", date);
}

function date_obj_to_dd_mm_yy(date) {
    return $.datepicker.formatDate("dd/mm/yy", date);
}


function getNumDates(first, second) {
    return (second - first) / (1000 * 60 * 60 * 24);
}

function addMonths(dateObj, num) {
    var currentMonth = dateObj.getMonth();
    dateObj.setMonth(dateObj.getMonth() + num);
    if (dateObj.getMonth() != ((currentMonth + num) % 12)) {
        dateObj.setDate(0);
    }
    return dateObj;
}

function convertDateFormatToFullDateFormat(date) {
    // convert date to readable format string
    var temp = date.split("-");
    var d = new Date(temp[0], temp[1] - 1, temp[2]);
    return $.datepicker.formatDate("DD d MM yy", d);
}