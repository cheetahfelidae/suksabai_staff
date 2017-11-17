<?php
    file_exists("../guest-liveSearch/".DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'Handler.php') ? require_once("../guest-liveSearch/".DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'Handler.php') : die('There is no such a file: Handler.php');
    file_exists("../guest-liveSearch/".DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'Config.php') ? require_once("../guest-liveSearch/".DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'Config.php') : die('There is no such a file: Config.php');
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
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>สุขสบายรีสอร์ท</title>
        <link rel="stylesheet" href="../stylesheets/app.css" />
        <script src="../bower_components/modernizr/modernizr.js"></script>
        <!-- jQuery UI -->
        <link rel="stylesheet" href="../bower_components/jquery-ui-1.11.4/jquery-ui.min.css">
        <!-- Font Awesom -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <!-- Footer -->
        <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
        <!-- Live Search Styles -->
        <link rel="stylesheet" href="../guest-liveSearch/css/fontello.css">
        <link rel="stylesheet" href="../guest-liveSearch/css/animation.css">
        <link rel="stylesheet" type="text/css" href="../guest-liveSearch/css/customStyle.css">
    </head>
    <body>
        <!-- ************************************** Top Bar ***************************************** -->
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
                        <li><a href="../index.php">จองห้องพัก</a></li>
                        <li class="divider"></li>
                        <li class="active"><a href="../priority/guestFinder.php">ค้นหาข้อมูลผู้พัก</a></li>
                        <li class="divider"></li>
                        <li><a href="../priority/monthlyFinance.html">ตรวจสอบบัญชี</a></li>
                        <li class="divider"></li>
                    </ul>
                </section>
            </nav>
        </div>
        <!-- ********************************* End of Top Bar **************************************** -->
        <!-- clock -->
        <div class="panel noBorder" id="clock-panel">
            <div class="row">
                <div class="small-12 columns">
                    <h6><strong><span id="clock-date"></span> &nbsp; <span id="clock-hours"></span>:<span id="clock-min"></span>:<span id="clock-sec"></span></strong></h6>
                </div>
            </div>
        </div>
        <!-- **************************************** Content **************************************** -->
        <div class="panel noLeftRightPadding noBottomMargin noBorder">
            <div class="row collapse">
                <div class="small-12 columns">
                    <div class="panel noLeftRightPadding withTransparent">
                        <!-- Search Form -->
                        <div class="row collapse">
                            <div class="small-12 columns">
                                <form accept-charset="UTF-8" class="search" id="ls_form" name="ls_form">
                                    <fieldset>
                                        <legend>ค้นหาข้อมูลผู้พัก</legend>
                                        <div class="row collapse">
                                            <div class="small-2 large-1 columns">
                                                <span class="prefix"><i class="fa fa-search"></i></span>
                                            </div>
                                            <div class="small-10 large-11 columns">
                                                <div class="row collapse">
                                                    <div class="small-12 columns">
                                                        <!-- Set javascript anti bot value in the session -->
                                                        <?php Handler::getJavascriptAntiBot(); ?>
                                                        <input type="hidden" name="ls_anti_bot" id="ls_anti_bot" value="">
                                                        <input type="hidden" name="ls_token" id="ls_token" value="<?php echo Handler::getToken(); ?>">
                                                        <input type="hidden" name="ls_page_loaded_at" id="ls_page_loaded_at" value="<?php echo time(); ?>">
                                                        <input type="hidden" name="ls_current_page" id="ls_current_page" value="1">
                                                        <input type="text" name="ls_query" id="ls_query" placeholder="กรอกชื่อจริงเพื่อค้นหา" autocomplete="off" maxlength="<?php echo Config::getConfig('maxInputLength'); ?>">
                                                    </div>
                                                </div>
                                                <div class="row collapse">
                                                    <div class="small-12 columns" id="ls_result_div" style="display:none;">
                                                        <!-- Result -->
                                                        <div id="ls_result_main">
                                                            <table>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- Pagination -->
                                                        <div class="panel noBottomMargin clearfix" style="padding: 10px;" id="ls_result_footer">
                                                            <div class="row">
                                                                <div class="small-3 large-2 columns page_limit">
                                                                    <select id="ls_items_per_page" class="noBottomMargin" name="ls_items_per_page">
                                                                        <option value="5" selected>5</option>
                                                                        <option value="10">10</option>
                                                                        <option value="0">ทั้งหมด</option>
                                                                    </select>
                                                                </div>
                                                                <div class="small-3 large-4 columns navigation">
                                                                    <button type="button" class="tiny noBottomMargin arrow right" id="ls_previous_page"><i class="fa fa-chevron-left fa-lg"></i></button>
                                                                </div>
                                                                <div class="small-3 large-2 columns navigation pagination">
                                                                    <label class="text-center noBottomMargin" style="margin-top: 5px;"><strong><span id="ls_current_page_lbl">1</span> / <span id="ls_last_page_lbl"></span></strong></label>
                                                                </div>
                                                                <div class="small-3 large-4 columns navigation">
                                                                    <button type="button" class="tiny noBottomMargin arrow left" id="ls_next_page"><i class="fa fa-chevron-right fa-lg"></i></button>
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
                                        <legend>ข้อมูลผู้พัก</legend>
                                        <div class="row collapse">
                                            <div class="small-2 large-1 columns">
                                                <span class="prefix"><i class="fa fa-user"></i></span>
                                            </div>
                                            <div class="small-8 large-4 columns end">
                                                <select id="title" onchange="showOtherTitles( this.value );" required disabled />
                                                    <!-- list will go here -->
                                                </select>
                                                <small class="error">กรุณาเลือกคำนำหน้า</small>
                                            </div>
                                            <div class="small-8 small-offset-2 large-4 large-offset-0 columns end" style="display:none;" id="otherTitle-column">
                                                <input type="text" placeholder="คำนำหน้า" id="otherTitle" maxlength="25" required disabled />
                                                <small class="error">กรุณากรอกคำนำหน้า</small>
                                            </div>
                                        </div>
                                        <div class="row collapse">
                                            <div class="small-10 small-offset-2 large-5 large-offset-1 columns">
                                                <input id="firstName" type="text" name="firstName" placeholder="ชื่อจริง" maxlength="40" required disabled />
                                                <small class="error">กรุณากรอกชื่อให้ถูกต้อง</small>
                                            </div>
                                            <div class="small-10 small-offset-2 large-5 large-offset-0 columns end">
                                                <input id="lastName" type="text" name="lastName" placeholder="นามสกุล" maxlength="40" required disabled />
                                                <small class="error">กรุณากรอกนามสกุลให้ถูกต้อง</small>
                                            </div>
                                        </div>
                                        <div class="row collapse">
                                            <div class="small-2 large-1 columns">
                                                <span class="prefix"><i class="fa fa-envelope-o"></i></span>
                                            </div>
                                            <div class="small-10 large-5 columns">
                                                <input id="email" name="email" placeholder="อีเมล์" maxlength="50" type="email" disabled />
                                                <small class="error">กรุณากรอกอีเมล์ให้ถูกต้อง เช่น test@gmail.com</small>
                                            </div>
                                            <div class="small-2 large-1 columns">
                                                <span class="prefix"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <div class="small-10 large-4 columns end">
                                                <input id="tel" name="phone" placeholder="เบอร์โทรศัพท์ 9 หรือ 10 หลัก" type="tel" pattern="[0][0-9]{8,9}" maxlength="10" required disabled />
                                                <small class="error">กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง</small>
                                            </div>
                                        </div>
                                        <div class="row collapse">
                                            <div class="small-2 large-1 columns">
                                                <span class="prefix"><i class="fa fa-map-marker"></i></span>
                                            </div>
                                            <div class="small-10 large-5 columns">
                                                <input id="address" name="address" type="text" placeholder="บ้านเลขที่, ชื่อถนน" maxlength="50" required disabled />
                                                <small class="error">กรุณากรอกที่อยู่ให้ถูกต้อง</small>
                                            </div>
                                            <div class="small-10 small-offset-2 large-4 large-offset-0 columns end">
                                                <select id="province" name="province" onchange="getThailandInfo('amphur', '', this.value, alt_phpUrl)" required disabled >
                                                    <!-- list will go here -->
                                                </select>
                                                <small class="error">กรุณาเลือกจังหวัด</small>
                                            </div>
                                        </div>
                                        <div class="row collapse">
                                            <div class="small-10 small-offset-2 large-4 large-offset-1 columns">
                                                <select id="amphur" name="amphur" onchange="getThailandInfo('district', '', this.value, alt_phpUrl)" required disabled >
                                                    <!-- list will go here -->
                                                </select>
                                                <small class="error">กรุณาเลือกอำเภอ</small>
                                            </div>
                                            <div class="small-10 small-offset-2 large-4 large-offset-0 columns end">
                                                <select id="district" name="district" required disabled >
                                                    <!-- list will go here -->
                                                </select>
                                                <small class="error">กรุณาเลือกตำบล</small>
                                            </div>
                                        </div>
                                        <div class="row collapse">
                                            <div class="small-10 large-5 columns">
                                                <label>หมายเลขประจำตัวผู้เสียภาษีอากร
                                                    <input type="text" placeholder="จำนวน 13 หลัก" id="taxNumber" pattern="[0-9]{13}" maxlength="13" disabled />
                                                </label>
                                                <small class="error">กรุณากรอกหมายเลขให้ถูกต้อง</small>
                                            </div>
                                        </div>
                                        <div class="row collapse">
                                            <div class="small-12 columns">
                                                <textarea id="otherDetails" placeholder="ข้อมูลอื่นๆ.." disabled ></textarea>
                                            </div>
                                        </div>
                                        <div class="row collapse" id="formTools-row" style="display: none;">
                                            <div class="small-12 columns">
                                                <button type="button" class="warning withTopMargin noBottomMargin right" id="editableForm-butt">แก้ไข &nbsp;<i class="fa fa fa-pencil-square-o fa-lg"></i></button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="row collapse" id="save-row" style="display: none;">
                            <div class="small-12 columns">
                                <button type="submit" form="guest-form" class="success withTopMargin noBottomMargin right" id="save-butt">บันทึก &nbsp;<i class="fa fa-floppy-o fa-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ************************************ End of Content **************************************** -->
        <!-- ****************************************** reveals ***************************************** -->
        <!-- error message -->
        <div id="error-reveal" class="reveal-modal small" data-reveal aria-labelledby="error-title" aria-hidden="true" role="dialog">
            <h2 id="error-title" class="withAlertColor"><i class="fa fa-exclamation-circle fa-lg"></i>&nbsp; ระบบเกิดข้อผิดพลาด</h2>
            <p class="lead">กรุณาแจ้งให้ผู้พัฒนาระบบทราบ ( คีตา )</p>
            <p>กดปุ่ม "รีโหลด" เพื่อโหลดหน้านี้ใหม่ หรือปุ่ม "รายละเอียดข้อผิดพลาด" เพื่อตรวจสอบข้อผิดพลาด</p>
            <a class="button reloadPage-butt" role="button"><i class="fa fa-refresh fa-lg"></i>&nbsp; รีโหลด</a>
            <a href="#" data-reveal-id="error-details" class="secondary button">รายละเอียดข้อผิดพลาด</a>
        </div>
        <div id="error-details" class="reveal-modal" data-reveal aria-labelledby="error-details" aria-hidden="true" role="dialog">
            <h2 id="error-details"><i class="fa fa-exclamation-circle fa-lg"></i>&nbsp; รายละเอียดข้อผิดพลาด</h2>
            <p id="error-details-para"></p>
            <p>กดปุ่ม "รีโหลด" เพื่อโหลดหน้านี้ใหม่</p>
            <a class="button reloadPage-butt" role="button"><i class="fa fa-refresh fa-lg"></i>&nbsp; รีโหลด</a>
        </div>
        <!-- booking into database successfully -->
        <div id="updateGuest-reveal" class="reveal-modal small" data-reveal aria-labelledby="updateGuest-title" aria-hidden="true" role="dialog">
            <h2 id="updateGuest-title" class="withSuccessColor"><i class="fa fa-check-circle fa-lg"></i>&nbsp; บันทึกการแก้ไขข้อมูลสำเร็จ</h2>
            <p class="lead">ข้อมูลผู้เข้าพักของผู้พักที่ได้ถูกแก้ไขไว้ได้ถูกบันทึกลงฐานข้อมูลแล้ว</p>
            <a class="button reloadPage-butt" role="button">ตกลง</a>
        </div>
        <!-- ************************************** end of reveals ************************************** -->
        <div class="spinner-overlay">
            <div class="spinner"></div>
        </div>
        <!-- ************************************** Footer ********************************************** -->
        <footer class="footer">
            <div class="row">
                <div class="large-6 columns">
                    <i class="fa fa-exclamation-triangle fa-3x" style="color: #e74c3c; margin-bottom: 10px;"></i>
                    <p style="color: #e74c3c;">ระบบยังอยู่ในระหว่างการพัฒนา<br>กรณีที่ระบบเกิดข้อผิดพลาดกรุณาแจ้งให้ผู้พัฒนาระบบทราบ</p>
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
        <!-- ************************************** end of Footer ************************************** -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../bower_components/foundation/js/foundation.min.js"></script>
        <script src="../js/app.js"></script>
        <!-- Custom Javascripts -->
        <script src="../js/global.js"></script>
        <script src="../js/guestFinder/main.js"></script>
        <script src="../js/guestFinder/guestForm.js"></script>
        <script src="../js/location.js"></script>
        <script src="../js/digitalClock.js"></script>
        <!-- jQuery UI -->
        <script src="../bower_components/jquery-ui-1.11.4/jquery-ui.min.js"></script>
        <!-- Live Search Script -->
        <script type="text/javascript" src="../guest-liveSearch/js/guestFinder.js"></script>
    </body>
</html>