<?php
file_exists("guest-liveSearch/" . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Handler.php') ? require_once("guest-liveSearch/" . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Handler.php') : die('There is no such a file: Handler.php');
file_exists("guest-liveSearch/" . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Config.php') ? require_once("guest-liveSearch/" . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Config.php') : die('There is no such a file: Config.php');

use AjaxLiveSearch\core\Config;
use AjaxLiveSearch\core\Handler;

if (session_id() == '') {
    session_start();
}
// For debugging. You can get rid of these two lines safely
//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>สุขสบายรีสอร์ท</title>
    <link rel="stylesheet" href="stylesheets/app.css"/>
    <link rel="stylesheet" href="stylesheets/popover.css"/>
    <script src="bower_components/modernizr/modernizr.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="bower_components/jquery-ui-1.11.4/jquery-ui.min.css">
    <!-- Font Awesom -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <!-- FullCalendar -->
    <!-- <link rel='stylesheet' href="bower_components/fullcalendar/dist/fullcalendar.css" /> -->
    <link rel='stylesheet' href="stylesheets/customFullcalendar.css"/>
    <!-- Footer -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <!-- Live Search Styles -->
    <link rel="stylesheet" href="guest-liveSearch/css/fontello.css">
    <link rel="stylesheet" href="guest-liveSearch/css/animation.css">
    <link rel="stylesheet" type="text/css" href="guest-liveSearch/css/customStyle.css">
    <!-- DatetimePicker -->
    <link rel="stylesheet" type="text/css" href="datetimepicker-master/jquery.datetimepicker.css">
</head>
<body>
<div class="hide-for-print">
    <!-- ************************************************************** Top Bar ***************************************************************** -->
    <div class="contain-to-grid fixed">
        <nav class="top-bar" data-topbar role="navigation">
            <ul class="title-area">
                <li class="name">
                    <h1><a>สุขสบายรีสอร์ท</a></h1>
                </li>
                <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
                <li class="toggle-topbar menu-icon"><a href="#"><span>เมนู</span></a></li>
            </ul>
            <section class="top-bar-section">
                <!-- Right Nav Section -->
                <ul class="right">
                    <li class="divider"></li>
                    <li class="active"><a href="index.php">จองห้องพัก</a></li>
                    <li class="divider"></li>
                    <li><a href="priority/guestFinder.php">ค้นหาข้อมูลผู้พัก</a></li>
                    <li class="divider"></li>
                    <li><a href="priority/monthlyFinance.html">ตรวจสอบบัญชี</a></li>
                    <li class="divider"></li>
                </ul>
            </section>
        </nav>
    </div>
    <!-- ********************************************************* End of Top Bar **************************************************************** -->
    <!-- ************************************************************************ Clock ****************************************************************** -->
    <div class="panel noBorder" id="clock-panel">
        <div class="row">
            <div class="small-12 columns">
                <h6><strong><span id="clock-date"></span> &nbsp; <span id="clock-hours"></span>:<span
                                id="clock-min"></span>:<span id="clock-sec"></span></strong></h6>
            </div>
        </div>
    </div>
    <!-- **********************************************************************End of Clock *************************************************************** -->
    <!-- ********************************************************************** Breadcrumb ********************************************************************** -->
    <div class="row collapse">
        <div class="small-12 columns">
            <div class="custom-breadcrumb-outer">
                <div class="custom-breadcrumb flat">
                    <a class="active" id="selectDates-tab-breadcrumb">เลือกวันที่</a>
                    <a id="selectedRoomsRates-tab-breadcrumb">เลือกประเภทห้อง</a>
                    <a id="guest-form-tab-breadcrumb">กรอกข้อมูลลูกค้า</a>
                    <a id="confirmation-tab-breadcrumb">ยืนยันการทำรายการ</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ********************************************************************** End of Breadcrumb ***************************************************************** -->
    <!-- **************************************************************** Content **************************************************************************************** -->
    <div class="panel noLeftRightPadding noBottomMargin noBorder">
        <div class="row collapse">
            <!-- **************************************************************** Sidebar ************************************************************* -->
            <div class="large-4 columns">
                <div class="panel noLeftPadding noBottomMargin withTransparent">
                    <form id="arrivDeparForm" data-abide="ajax">
                        <div class="row collapse" id="breadcrumbs-selectedRoomsRates-row" style="display: none;">
                            <div class="small-12 columns">
                                <div class="panel withTopMargin" id="breadcrumbs-selectedRoomsRates-panel">
                                    <h5><strong id="breadcrumbs-selectedRoomsRates"></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="row collapse">
                            <div class="small-12 columns">
                                <fieldset>
                                    <legend>เลือกวันที่เข้าพัก & เดินทางออก</legend>
                                    <div class="row collapse">
                                        <label for="arriv-input">เข้าพัก</label>
                                        <div class="small-10 columns">
                                            <input type="text" id="arriv-input"
                                                   placeholder="คลิกปุ่มขวาเพื่อเลือกวันที่" required/>
                                            <small class="error">กรุณาระบุวันที่เข้าพัก</small>
                                        </div>
                                        <div class="small-2 columns">
                                            <a role="button" tabindex="0" id="arriv-butt"
                                               class="button noPadding postfix"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                    <div class="row collapse">
                                        <div class="small-10 columns">
                                            <input type="text" id="arriv-time-input"
                                                   placeholder="คลิกปุ่มขวาเพื่อเลือกเวลาเข้าพัก" required/>
                                            <small class="error">กรุณาระบุเวลาเข้าพัก</small>
                                        </div>
                                        <div class="small-2 columns">
                                            <a role="button" tabindex="0" id="arriv-time-butt"
                                               class="button noPadding postfix"><i class="fa fa-clock-o fa-lg"></i></a>
                                        </div>
                                    </div>
                                    <div class="row collapse">
                                        <label for="depar-input">เดินทางออก</label>
                                        <div class="small-10 columns">
                                            <input type="text" id="depar-input"
                                                   placeholder="คลิกปุ่มขวาเพื่อเลือกวันที่" required/>
                                            <small class="error">กรุณาระบุวันที่เดินทางออก</small>
                                        </div>
                                        <div class="small-2 columns">
                                            <a role="button" tabindex="0" id="depar-butt"
                                               class="button noPadding postfix"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                    <div class="row collapse">
                                        <div class="small-10 columns">
                                            <input type="text" id="depar-time-input"
                                                   placeholder="คลิกปุ่มขวาเพื่อเลือกเวลาเข้าพัก" value="12:00" disabled/>
                                            <small class="error">กรุณาระบุเวลาเดินทางออก</small>
                                        </div>
                                        <div class="small-2 columns">
                                            <a role="button" tabindex="0" id="depar-time-butt"
                                               class="button noPadding postfix" disabled><i class="fa fa-clock-o fa-lg"></i></a>
                                        </div>
                                    </div>
                                    <div class="row collapse">
                                        <div class="small-12 columns">
                                            <p class="withInfoColor"><strong id="numDates-dateSelection"></strong></p>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row collapse" id="numRooms-select-row" hidden>
                            <div class="small-12 columns">
                                <fieldset>
                                    <legend>เลือกจำนวนของห้อง & ผู้เข้าพัก</legend>
                                    <div class="row collapse">
                                        <div class="small-3 columns">
                                            <label class="text-center">ห้อง
                                                <select id="numRooms-select">
                                                </select>
                                            </label>
                                        </div>
                                        <div class="small-9 columns" id="selectedRoomsGuests-list">
                                            <!-- list will go here -->
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row collapse" id="selectedRooms-list-row" style="display: none;">
                            <div class="small-12 columns">
                                <fieldset id="selectedRooms-list-fieldset">
                                    <!-- list will go here -->
                                </fieldset>
                            </div>
                        </div>
                        <div class="row collapse">
                            <div class="small-12 columns">
                                <fieldset id="totalPrice-row" style="display: none;">
                                    <legend>ราคารวม</legend>
                                    <div class="row collapse">
                                        <div class="small-9 columns">
                                            <input type="text" class="text-center" id="totalPrice" disabled>
                                        </div>
                                        <div class="small-3 columns">
                                            <span class="postfix">บาท</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- ********************************************************** End of  Sidebar *********************************************************** -->
            <!-- ******************************************************** Steps for booking *********************************************************** -->
            <div class="large-8 columns">
                <div class="panel noRightPadding noTopBottomPadding withTransparent">
                    <!-- ********************************************* Tab Content ************************************** -->
                    <div class="tabs-content">
                        <!-- **************************** Select Date Tab ****************** -->
                        <section role="tabpanel" aria-hidden="false" class="content active" id="selectDates-tab">
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <form>
                                        <fieldset>
                                            <legend>ปฏิทินผู้เข้าพัก</legend>
                                            <div class="row collapse">
                                                <div class="small-2 columns">
                                                    <a class="button tiny left" id="prev-calendar-butt"><i
                                                                class="fa fa-chevron-left fa-lg"></i></a>
                                                </div>
                                                <div class="small-8 columns">
                                                    <h3 class="text-center"><strong id="calendar-title"></strong></h3>
                                                </div>
                                                <div class="small-2 columns">
                                                    <a class="button tiny right" id="next-calendar-butt"><i
                                                                class="fa fa-chevron-right fa-lg"></i></a>
                                                </div>
                                            </div>
                                            <div id='calendar'></div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <button type="submit" form="arrivDeparForm"
                                            class="noBottomMargin withTopMargin right">ต่อไป &nbsp;<i
                                                class="fa fa-chevron-right fa-lg"></i></button>
                                </div>
                            </div>
                        </section>
                        <!-- *************************** End of Select Date Tab ************* -->
                        <!-- ********************** Select Rooms & Rates Tab **************** -->
                        <section role="tabpanel" aria-hidden="true" class="content" id="selectedRoomsRates-tab">
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <form data-abide="ajax" id="roomTypes-form">
                                        <fieldset id="roomTypes-fieldset">
                                            <!-- list will go here -->
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <button type="button" class="noBottomMargin withTopMargin left"
                                            data-reveal-id="back-toSelectDates-reveal"><i
                                                class="fa fa-chevron-left fa-lg"></i>&nbsp; ก่อนหน้า
                                    </button>
                                </div>
                            </div>
                        </section>
                        <!-- ******************* End of  Select Rooms Rates Tab ************** -->
                        <!-- **************************** Guest Form ************************* -->
                        <section role="tabpanel" aria-hidden="true" class="content" id="guest-form-tab">
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <!-- Search Form -->
                                    <form accept-charset="UTF-8" class="search" id="ls_form" name="ls_form">
                                        <fieldset>
                                            <legend>ค้นหาข้อมูลผู้พัก &nbsp;<span
                                                        class="withInfoColor">(กรณีผู้พักเก่า)</span></legend>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-search"></i></span>
                                                </div>
                                                <div class="small-10 large-11 columns">
                                                    <div class="row collapse">
                                                        <div class="small-12 columns">
                                                            <!-- Set javascript anti bot value in the session -->
                                                            <?php Handler::getJavascriptAntiBot(); ?>
                                                            <input type="hidden" name="ls_anti_bot" id="ls_anti_bot"
                                                                   value="">
                                                            <input type="hidden" name="ls_token" id="ls_token"
                                                                   value="<?php echo Handler::getToken(); ?>">
                                                            <input type="hidden" name="ls_page_loaded_at"
                                                                   id="ls_page_loaded_at" value="<?php echo time(); ?>">
                                                            <input type="hidden" name="ls_current_page"
                                                                   id="ls_current_page" value="1">
                                                            <input type="text" name="ls_query" id="ls_query"
                                                                   style="width: 500px;"
                                                                   placeholder="กรอกชื่อจริงเพื่อค้นหา"
                                                                   autocomplete="off"
                                                                   maxlength="<?php echo Config::getConfig('maxInputLength'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row collapse">
                                                        <div class="small-12 columns" id="ls_result_div"
                                                             style="display:none;">
                                                            <!-- Result -->
                                                            <div id="ls_result_main">
                                                                <table>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- Pagination -->
                                                            <div class="panel noBottomMargin clearfix"
                                                                 style="padding: 10px;" id="ls_result_footer">
                                                                <div class="row">
                                                                    <div class="small-3 large-2 columns page_limit">
                                                                        <select id="ls_items_per_page"
                                                                                class="noBottomMargin"
                                                                                name="ls_items_per_page">
                                                                            <option value="5" selected>5</option>
                                                                            <option value="10">10</option>
                                                                            <option value="0">ทั้งหมด</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="small-3 large-4 columns navigation">
                                                                        <button type="button"
                                                                                class="tiny noBottomMargin arrow right"
                                                                                id="ls_previous_page"><i
                                                                                    class="fa fa-chevron-left fa-lg"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="small-3 large-2 columns navigation pagination">
                                                                        <label class="text-center noBottomMargin"
                                                                               style="margin-top: 5px;"><strong><span
                                                                                        id="ls_current_page_lbl">1</span>
                                                                                /
                                                                                <span id="ls_last_page_lbl"></span></strong></label>
                                                                    </div>
                                                                    <div class="small-3 large-4 columns navigation">
                                                                        <button type="button"
                                                                                class="tiny noBottomMargin arrow left"
                                                                                id="ls_next_page"><i
                                                                                    class="fa fa-chevron-right fa-lg"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <form data-abide="ajax" id="guest-form">
                                        <fieldset>
                                            <legend>กรอกข้อมูลผู้พัก</legend>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-user"></i></span>
                                                </div>
                                                <div class="small-8 large-4 columns end">
                                                    <select id="title-guest-form-tab"
                                                            onchange="showOtherTitles( this.value );" required/>
                                                    <!-- list will go here -->
                                                    </select>
                                                    <small class="error">กรุณาเลือกคำนำหน้า</small>
                                                </div>
                                                <div class="small-8 small-offset-2 large-4 large-offset-0 columns end"
                                                     style="display:none;" id="otherTitle-column-guest-form-tab">
                                                    <input type="text" placeholder="คำนำหน้า"
                                                           id="otherTitle-guest-form-tab" maxlength="25" required/>
                                                    <small class="error">กรุณากรอกคำนำหน้า</small>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-10 small-offset-2 large-5 large-offset-1 columns">
                                                    <input id="firstName-guest-form-tab" type="text" name="firstName"
                                                           placeholder="ชื่อจริง" maxlength="40" required/>
                                                    <small class="error">กรุณากรอกชื่อให้ถูกต้อง</small>
                                                </div>
                                                <div class="small-10 small-offset-2 large-5 large-offset-0 columns end">
                                                    <input id="lastName-guest-form-tab" type="text" name="lastName"
                                                           placeholder="นามสกุล" maxlength="40" required/>
                                                    <small class="error">กรุณากรอกนามสกุลให้ถูกต้อง</small>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-envelope-o"></i></span>
                                                </div>
                                                <div class="small-10 large-5 columns">
                                                    <input id="email-guest-form-tab" name="email" placeholder="อีเมล์"
                                                           maxlength="50" type="email"/>
                                                    <small class="error">กรุณากรอกอีเมล์ให้ถูกต้อง เช่น test@gmail.com
                                                    </small>
                                                </div>
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-phone"></i></span>
                                                </div>
                                                <div class="small-10 large-4 columns end">
                                                    <input id="tel-guest-form-tab" name="phone"
                                                           placeholder="เบอร์โทรศัพท์ 9 หรือ 10 หลัก" type="tel"
                                                           pattern="[0][0-9]{8,9}" maxlength="10" required/>
                                                    <small class="error">กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง</small>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-map-marker"></i></span>
                                                </div>
                                                <div class="small-10 large-5 columns">
                                                    <input id="address-guest-form-tab" name="address" type="text"
                                                           placeholder="บ้านเลขที่, ชื่อถนน" maxlength="50" required/>
                                                    <small class="error">กรุณากรอกที่อยู่ให้ถูกต้อง</small>
                                                </div>
                                                <div class="small-10 small-offset-2 large-4 large-offset-0 columns end">
                                                    <select id="province-guest-form-tab" name="province"
                                                            onchange="getThailandInfo('amphur', '-guest-form-tab', this.value, phpUrl)"
                                                            required>
                                                        <!-- list will go here -->
                                                    </select>
                                                    <small class="error">กรุณาเลือกจังหวัด</small>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-10 small-offset-2 large-4 large-offset-1 columns">
                                                    <select id="amphur-guest-form-tab" name="amphur"
                                                            onchange="getThailandInfo('district', '-guest-form-tab', this.value, phpUrl)"
                                                            required>
                                                        <!-- list will go here -->
                                                    </select>
                                                    <small class="error">กรุณาเลือกอำเภอ</small>
                                                </div>
                                                <div class="small-10 small-offset-2 large-4 large-offset-0 columns end">
                                                    <select id="district-guest-form-tab" name="district" required>
                                                        <!-- list will go here -->
                                                    </select>
                                                    <small class="error">กรุณาเลือกตำบล</small>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-10 large-5 columns">
                                                    <label>หมายเลขประจำตัวผู้เสียภาษีอากร
                                                        <input type="text" placeholder="จำนวน 13 หลัก"
                                                               id="taxNumber-guest-form-tab" pattern="[0-9]{13}"
                                                               maxlength="13"/>
                                                    </label>
                                                    <small class="error">กรุณากรอกหมายเลขให้ถูกต้อง</small>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-12 columns">
                                                    <textarea id="otherDetails-guest-form-tab"
                                                              placeholder="ข้อมูลอื่นๆ.."></textarea>
                                                </div>
                                            </div>
                                            <div class="row collapse" id="formTools-row" style="display: none;">
                                                <div class="small-12 columns">
                                                    <button type="button"
                                                            class="alert withTopMargin noBottomMargin left"
                                                            id="initialiseForm-butt-guest-form-tab">เคลียร์ &nbsp;<i
                                                                class="fa fa-eraser fa-lg"></i></button>
                                                    <button type="button"
                                                            class="warning withTopMargin noBottomMargin right"
                                                            id="editableForm-butt-guest-form-tab">แก้ไข &nbsp;<i
                                                                class="fa fa fa-pencil-square-o fa-lg"></i></button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <button type="button" class="noBottomMargin withTopMargin left"
                                            data-reveal-id="back-toRoomsRatesSelection-reveal"><i
                                                class="fa fa-chevron-left fa-lg"></i>&nbsp; ก่อนหน้า
                                    </button>
                                    <button type="submit" form="guest-form" class="noBottomMargin withTopMargin right">
                                        ต่อไป &nbsp;<i class="fa fa-chevron-right fa-lg"></i></button>
                                </div>
                            </div>
                        </section>
                        <!-- *************************** End of Guest Form ******************* -->
                        <!-- ************************ Confirmation Tab *********************** -->
                        <section role="tabpanel" aria-hidden="true" class="content" id="confirmation-tab">
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <form>
                                        <fieldset>
                                            <legend>ข้อมูลผู้พัก &nbsp;<span class="withInfoColor"
                                                                             id="formLegend-confirmation-tab"></span>
                                            </legend>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-user"></i></span>
                                                </div>
                                                <div class="small-10 large-6 columns end">
                                                    <input type="text" id="name-confirmation-tab" disabled>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-envelope-o"></i></span>
                                                </div>
                                                <div class="small-10 large-5 columns">
                                                    <input type="text" id="email-confirmation-tab" disabled>
                                                </div>
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-phone"></i></span>
                                                </div>
                                                <div class="small-10 large-4 columns end">
                                                    <input type="text" id="tel-confirmation-tab" disabled>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-2 large-1 columns">
                                                    <span class="prefix"><i class="fa fa-map-marker"></i></span>
                                                </div>
                                                <div class="small-10 large-11 columns">
                                                    <input type="text" id="address-confirmation-tab" disabled>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-10 large-5 columns">
                                                    <label>หมายเลขประจำตัวผู้เสียภาษีอากร
                                                        <input type="text" id="taxNumber-confirmation-tab" disabled>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row collapse">
                                                <div class="small-12 columns">
                                                    <textarea id="otherDetails-confirmation-tab" disabled></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="row collapse">
                                <div class="small-12 columns">
                                    <button type="button" class="noBottomMargin withTopMargin left"
                                            id="back-toEntGuDetailsTab"><i class="fa fa-chevron-left fa-lg"></i>&nbsp;
                                        ก่อนหน้า
                                    </button>
                                    <button type="button" class="success noBottomMargin withTopMargin right"
                                            id="confirm-butt">ยืนยัน &nbsp;<i class="fa fa-check-circle fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </section>
                        <!-- ******************** End of Confirmation Tab ********************* -->
                    </div>
                    <!-- *************************************** End of Tab Content ************************************** -->
                </div>
            </div>
            <!-- ****************************************************** End of Steps for booking ******************************************************* -->
        </div>
    </div>
    <!-- ************************************************************************** End of Content ************************************************************************ -->
    <!-- ******************************************************************************** Reveals ************************************************************************* -->
    <!-- error message -->
    <div id="error-reveal" class="reveal-modal small" data-reveal aria-labelledby="error-title" aria-hidden="true"
         role="dialog">
        <h2 id="error-title" class="withAlertColor"><i class="fa fa-exclamation-circle fa-lg"></i>&nbsp;
            ระบบเกิดข้อผิดพลาด</h2>
        <p class="lead">กรุณาแจ้งให้ผู้พัฒนาระบบทราบ ( คีตา )</p>
        <p>กดปุ่ม "รีโหลด" เพื่อโหลดหน้านี้ใหม่ หรือปุ่ม "รายละเอียดข้อผิดพลาด" เพื่อตรวจสอบข้อผิดพลาด</p>
        <a class="button reloadPage-butt" role="button"><i class="fa fa-refresh fa-lg"></i>&nbsp; รีโหลด</a>
        <a href="#" data-reveal-id="error-details" class="secondary button">รายละเอียดข้อผิดพลาด</a>
    </div>
    <div id="error-details" class="reveal-modal" data-reveal aria-labelledby="error-details" aria-hidden="true"
         role="dialog">
        <h2 id="error-details"><i class="fa fa-exclamation-circle fa-lg"></i>&nbsp; รายละเอียดข้อผิดพลาด</h2>
        <p id="error-details-para"></p>
        <p>กดปุ่ม "รีโหลด" เพื่อโหลดหน้านี้ใหม่</p>
        <a class="button reloadPage-butt" role="button"><i class="fa fa-refresh fa-lg"></i>&nbsp; รีโหลด</a>
    </div>
    <!-- back to select date (start page) -->
    <div id="back-toSelectDates-reveal" class="reveal-modal small" data-reveal
         aria-labelledby="back-toSelectDates-title" aria-hidden="true" role="dialog">
        <h2 id="back-toSelectDates-title" class="withWarningColor"><i class="fa fa-exclamation-circle fa-lg"></i>&nbsp;
            ยืนยันการดำเนินการ</h2>
        <p class="lead">การดำเนินการมีผลทำให้ห้องทั้งหมดที่ได้เลือกไว้จะหายไป</p>
        <p>กดปุ่ม "ย้อนกลับ" เพื่อย้อนกลับไปหน้าเดิม หรือกดปุ่มกากบาทเพื่ออยู่หน้านี้</p>
        <a class="button reloadPage-butt" role="button"><i class="fa fa-chevron-left fa-lg"></i>&nbsp; ย้อนกลับ</a>
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    <!-- no room available -->
    <div id="noRoomAvail-reveal" class="reveal-modal small" data-reveal aria-labelledby="noRoomAvail-title"
         aria-hidden="true" role="dialog">
        <h2 id="noRoomAvail-title" class="withWarningColor">ไม่มีห้องพักว่าง</h2>
        <p class="lead">ไม่มีห้องพักว่างในช่วงวันที่เข้าพักที่เลือก</p>
        <p>กรุณาเลือกช่วงวันที่เข้าพักอีกครั้ง</p>
        <a class="button reloadPage-butt right" role="button">ตกลง</a>
    </div>
    <!-- back to select rooms & rates -->
    <div id="back-toRoomsRatesSelection-reveal" class="reveal-modal small" data-reveal
         aria-labelledby="back-toSelectedRoomsRatesTitle" aria-hidden="true" role="dialog">
        <h2 id="back-toSelectedRoomsRatesTitle" class="withWarningColor"><i class="fa fa-exclamation-circle fa-lg"></i>&nbsp;
            ยืนยันการดำเนินการ</h2>
        <p class="lead">การดำเนินการมีผลทำให้ห้องทั้งหมดที่ได้เลือกไว้ และข้อมูลลูกค้าที่ได้กรอกจะหายไป</p>
        <p>กดปุ่ม "ตกลง" เพื่อย้อนกลับไปหน้าเดิม หรือกดปุ่มกากบาทเพื่ออยู่หน้านี้</p>
        <a class="button" role="button" id="back-toSelectedRoomsRates-butt"><i class="fa fa-chevron-left fa-lg"></i>&nbsp;
            ย้อนกลับ</a>
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    <!-- booking into database successfully -->
    <div id="bookingSuccess-reveal" class="reveal-modal small" data-reveal aria-labelledby="bookingSuccess-title"
         aria-hidden="true" role="dialog">
        <h2 id="bookingSuccess-title" class="withSuccessColor"><i class="fa fa-check-circle fa-lg"></i>&nbsp;
            บันทึกข้อมูลสำเร็จ</h2>
        <p class="lead">ข้อมูลผู้เข้าพัก และวันที่เข้าพักได้ถูกบันทึกลงฐานข้อมูลแล้ว</p>
        <a class="button reloadPage-butt" role="button">ตกลง</a>
    </div>
    <!-- ************************************************************** End Of Reveals ************************************************************************************** -->
    <div class="spinner-overlay">
        <div class="spinner"></div>
    </div>
    <!-- ************************************************************************************** Footer ********************************************************************** -->
    <footer class="footer">
        <div class="row">
            <div class="large-6 columns">
                <i class="fa fa-exclamation-triangle fa-3x" style="color: #e74c3c; margin-bottom: 10px;"></i>
                <p style="color: #e74c3c;">ระบบยังอยู่ในระหว่างการพัฒนา<br>กรณีที่ระบบเกิดข้อผิดพลาดกรุณาแจ้งให้ผู้พัฒนาระบบทราบ
                </p>
            </div>
            <div class="large-6 columns">
                <h5>ติดต่อสอบถามเกี่ยวกับระบบ หรือแจ้งข้อผิดพลาดได้ที่</h5>
                <ul class="footer-links">
                    <li><p href="#"><i class="fa fa-mobile fa-2x"></i>&nbsp; 081 670 9417 ( คุณตุ๊ )</p></li>
                    <li><p href="#"><i class="fa fa-mobile fa-2x"></i>&nbsp; +44 74491 68840 ( คุณคีตา )</p></li>
                </ul>
            </div>
        </div>
        <div class="row withTopMargin">
            <div class="small-12 columns">
                <i class="fa fa-copyright fa-lg"></i>&nbsp; ระบบแห่งนี้เป็นลิขสิทธิ์ของสุขสบายรีสอร์ท
            </div>
        </div>
    </footer>
    <!-- ************************************************************** End Of Footer ************************************************************************************** -->
</div>
<!-- ************************************************************************************** Receipt ************************************************************************ -->
<div class="show-for-print">
    <div class="panel noBottomMargin noBorder">
        <h2 class="text-center">สุขสบายรีสอร์ท</h2>
    </div>
    <div class="panel noBottomMargin noLeftRightBorder">
        <ul class="no-bullet text-center">
            <li><i class="fa fa-map-marker fa-lg"></i>&nbsp; สุขสบายรีสอร์ท 399 วังสามหมอ อุดรธานี 41280</li>
            <li><i class="fa fa-mobile fa-lg"></i>&nbsp; 099-3046694</li>
            <li><i class="fa fa-mobile fa-lg"></i>&nbsp; 081-6709417 (คุณตุ๊)</li>
            <li><i class="fa fa-phone fa-lg"></i>&nbsp; 042-387612 (ร้านณัฐสันต์ยนต์)</li>
        </ul>
    </div>
    <div class="panel noBottomMargin noLeftRightBorder">
        <ul class="no-bullet">
            <li><strong>ข้อมูลลูกค้า</strong></li>
            <li id="name-receipt"></li>
            <li id="tel-receipt"></li>
            <li id="email-receipt"></li>
            <li id="address-receipt"></li>
        </ul>
        <ul class="no-bullet">
            <li><strong id="taxCaption-receipt"></strong></li>
            <li id="taxNumber-receipt"></li>
        </ul>
    </div>
    <div class="panel noBottomMargin noLeftRightBorder">
        <ul class="no-bullet">
            <li><strong>วันเวลาออกใบเสร็จ</strong></li>
            <li id="printTimeDate-receipt"></li>
        </ul>
    </div>
    <div class="panel noBottomMargin noLeftRightBorder">
        <table class="noBottomMargin noBorder">
            <thead>
            <tr>
                <th class="text-center">คืนที่พัก</th>
                <th class="text-center" width="33%">รายการ</th>
                <th class="text-center" width="33%">ราคา</th>
            </tr>
            </thead>
            <tbody id="stayList-receipt">
            <!-- list will go here -->
            </tbody>
        </table>
    </div>
    <div class="panel noBottomMargin noLeftRightBorder">
        <ul class="no-bullet">
            <li id="numDates-receipt"></li>
            <li id="sumRoomPrice-receipt"></li>
            <li id="sumBreakfastPrice-receipt"></li>
            <li id="sumExtraBedPrice-receipt"></li>
            <li>Service Charge : ฿0</li>
            <li><strong id="total-receipt"></strong></li>
        </ul>
    </div>
    <div class="panel noBottomMargin noLeftRightBorder">
        <ul class="no-bullet">
            <li><strong>เชคอินได้ตั้งแต่ :</strong></li>
            <li id="checkInDate-receipt"></li>
            <li><strong>เชคเอ้าท์ได้
                    <ins>ไม่เกิน</ins>
                    :</strong></li>
            <li id="checkOutDate-receipt"></li>
            <li><strong>รหัส Wi-Fi : </strong>06036307</li>
        </ul>
        <div class="panel noBottomPadding noBorder">
            <ul class="no-bullet text-center">
                <li>.....................................</li>
                <li>ผู้รับเงิน</li>
            </ul>
        </div>
    </div>
    <div class="panel noBottomBorder noLeftRightBorder">
        <p class="text-center">*** ขอขอบคุณที่ใช้บริการครับ/ค่ะ ***</p>
    </div>
</div>
<!-- *********************************************************************************** End of receipt ******************************************************************** -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="bower_components/foundation/js/foundation.min.js"></script>
<script src="js/app.js"></script>
<!-- Custom Breadcrumb -->
<script src="http://thecodeplayer.com/uploads/js/prefixfree-1.0.7.js" type="text/javascript"
        type="text/javascript"></script>
<!-- Custom Javascripts -->
<script src="js/global.js"></script>
<script src="js/booking/main.js"></script>
<script src="js/booking/dateSelection.js"></script>
<script src="js/booking/roomRatesSelection.js"></script>
<script src="js/booking/guestForm.js"></script>
<script src="js/booking/confirmation.js"></script>
<script src="js/location.js"></script>
<script src="js/digitalClock.js"></script>
<!-- jQuery UI -->
<script src="bower_components/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<!-- FullCalendar -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/fullcalendar/dist/fullcalendar.js"></script>
<script src="bower_components/fullcalendar/dist/lang/th.js"></script>
<!-- Live Search Script -->
<script type="text/javascript" src="guest-liveSearch/js/booking.js"></script>
<!-- DatetimePicker -->
<script src="datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
</body>
</html>