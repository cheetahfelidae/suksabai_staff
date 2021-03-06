function Form() {
    var guest;
    var guestID;
    var tempFirst,
        tempLast;
    this.getGuest = function() {
        return guest;
    };
    this.initialise = function() {
        // auto scroll to top left of page
        window.scrollTo( 0, 0 );
        // clear everything into the form
        initialiseForm();
    };
    var createTitleField = function() {
        $( '#title' ).html( '<option value="">- เลือกคำนำหน้า -</option>\
                                            <option value="นาย">นาย</option><option value="นาง">นาง</option><option value="น.ส.">นางสาว</option><option value="พล.ต.อ.">พลตำรวจเอก</option><option value="พล.ต.อ. หญิง">พลตำรวจเอก หญิง</option><option value="พล.ต.ท">พลตำรวจโท</option><option value="พล.ต.ท หญิง">พลตำรวจโท หญิง</option><option value="พล.ต.ต">พลตำรวจตรี</option><option value="พล.ต.ต หญิง">พลตำรวจตรี หญิง</option><option value="พ.ต.อ.">พันตำรวจเอก</option><option value="พ.ต.อ. หญิง">พันตำรวจเอก หญิง</option><option value="พ.ต.อ.(พิเศษ)">พันตำรวจเอกพิเศษ</option><option value="พ.ต.อ.(พิเศษ) หญิง">พันตำรวจเอกพิเศษ หญิง</option><option value="พ.ต.ท.">พันตำรวจโท</option><option value="พ.ต.ท. หญิง">พันตำรวจโท หญิง</option><option value="พ.ต.ต.">พันตำรวจตรี</option><option value="พ.ต.ต. หญิง">พันตำรวจตรี หญิง</option><option value="ร.ต.อ.">ร้อยตำรวจเอก</option><option value="ร.ต.อ. หญิง">ร้อยตำรวจเอก หญิง</option><option value="ร.ต.ท.">ร้อยตำรวจโท</option><option value="ร.ต.ท. หญิง">ร้อยตำรวจโท หญิง</option><option value="ร.ต.ต.">ร้อยตำรวจตรี</option><option value="ร.ต.ต. หญิง">ร้อยตำรวจตรี หญิง</option><option value="ด.ต.">นายดาบตำรวจ</option><option value="ด.ต. หญิง">ดาบตำรวจหญิง</option><option value="ส.ต.อ.">สิบตำรวจเอก</option><option value="ส.ต.อ. หญิง">สิบตำรวจเอก หญิง</option><option value="ส.ต.ท.">สิบตำรวจโท</option><option value="ส.ต.ท. หญิง">สิบตำรวจโท หญิง</option><option value="ส.ต.ต.">สิบตำรวจตรี</option><option value="ส.ต.ต. หญิง">สิบตำรวจตรี หญิง</option><option value="จ.ส.ต.">จ่าสิบตำรวจ</option><option value="จ.ส.ต. หญิง">จ่าสิบตำรวจ หญิง</option><option value="พลฯ">พลตำรวจ</option><option value="พลฯ หญิง">พลตำรวจ หญิง</option><option value="พล.อ.">พลเอก</option><option value="พล.อ. หญิง">พลเอก หญิง</option><option value="พล.ท.">พลโท</option><option value="พล.ท. หญิง">พลโท หญิง</option><option value="พล.ต.">พลตรี</option><option value="พล.ต.หญิง">พลตรี หญิง</option><option value="พ.อ.">พันเอก</option><option value="พ.อ.หญิง">พันเอก หญิง</option><option value="พ.อ.(พิเศษ)">พันเอกพิเศษ</option><option value="พ.อ.(พิเศษ) หญิง">พันเอกพิเศษ หญิง</option><option value="พ.ท.">พันโท</option><option value="พ.ท.หญิง">พันโท หญิง</option><option value="พ.ต.">พันตรี</option><option value="พ.ต.หญิง">พันตรี หญิง</option><option value="ร.อ.">ร้อยเอก</option><option value="ร.อ.หญิง">ร้อยเอก หญิง</option><option value="ร.ท.">ร้อยโท</option><option value="ร.ท.หญิง">ร้อยโท หญิง</option><option value="ร.ต.">ร้อยตรี</option><option value="ร.ต.หญิง">ร้อยตรี หญิง</option><option value="ส.อ.">สิบเอก</option><option value="ส.อ.หญิง">สิบเอก หญิง</option><option value="ส.ท.">สิบโท</option><option value="ส.ท.หญิง">สิบโท หญิง</option><option value="ส.ต.">สิบตรี</option><option value="ส.ต.หญิง">สิบตรี หญิง</option><option value="จ.ส.อ.">จ่าสิบเอก</option><option value="จ.ส.อ.หญิง">จ่าสิบเอก หญิง</option><option value="จ.ส.ท.">จ่าสิบโท</option><option value="จ.ส.ท.หญิง">จ่าสิบโท หญิง</option><option value="จ.ส.ต.">จ่าสิบตรี</option><option value="จ.ส.ต.หญิง">จ่าสิบตรี หญิง</option><option value="พลฯ">พลทหารบก</option><option value="ว่าที่ พ.ต.">ว่าที่ พ.ต.</option><option value="ว่าที่ พ.ต. หญิง">ว่าที่ พ.ต. หญิง</option><option value="ว่าที่ ร.อ.">ว่าที่ ร.อ.</option><option value="ว่าที่ ร.อ. หญิง">ว่าที่ ร.อ. หญิง</option><option value="ว่าที่ ร.ท.">ว่าที่ ร.ท.</option><option value="ว่าที่ ร.ท. หญิง">ว่าที่ ร.ท. หญิง</option><option value="ว่าที่ ร.ต.">ว่าที่ ร.ต.</option><option value="ว่าที่ ร.ต. หญิง">ว่าที่ ร.ต. หญิง</option><option value="พล.ร.อ.">พลเรือเอก</option><option value="พล.ร.อ.หญิง">พลเรือเอก หญิง</option><option value="พล.ร.ท.">พลเรือโท</option><option value="พล.ร.ท.หญิง">พลเรือโท หญิง</option><option value="พล.ร.ต.">พลเรือตรี</option><option value="พล.ร.ต.หญิง">พลเรือตรี หญิง</option><option value="น.อ.">นาวาเอก</option><option value="น.อ.หญิง">นาวาเอก หญิง</option><option value="น.อ.(พิเศษ)">นาวาเอกพิเศษ</option><option value="น.อ.(พิเศษ) หญิง">นาวาเอกพิเศษ หญิง</option><option value="น.ท.">นาวาโท</option><option value="น.ท.หญิง">นาวาโท หญิง</option><option value="น.ต.">นาวาตรี</option><option value="น.ต.หญิง">นาวาตรี หญิง</option><option value="ร.อ.">เรือเอก</option><option value="ร.อ.หญิง">เรือเอก หญิง</option><option value="ร.ท.">เรือโท</option><option value="ร.ท.หญิง">เรือโท หญิง</option><option value="ร.ต.">เรือตรี</option><option value="ร.ต.หญิง">เรือตรี หญิง</option><option value="พ.จ.อ.">พันจ่าเอก</option><option value="พ.จ.อ.หญิง">พันจ่าเอก หญิง</option><option value="พ.จ.ท.">พันจ่าโท</option><option value="พ.จ.ท.หญิง">พันจ่าโท หญิง</option><option value="พ.จ.ต.">พันจ่าตรี</option><option value="พ.จ.ต.หญิง">พันจ่าตรี หญิง</option><option value="จ.อ.">จ่าเอก</option><option value="จ.อ.หญิง">จ่าเอก หญิง</option><option value="จ.ท.">จ่าโท</option><option value="จ.ท.หญิง">จ่าโท หญิง</option><option value="จ.ต.">จ่าตรี</option><option value="จ.ต.หญิง">จ่าตรี หญิง</option><option value="พลฯ">พลทหารเรือ</option><option value="พล.อ.อ.">พลอากาศเอก</option><option value="พล.อ.อ.หญิง">พลอากาศเอก หญิง</option><option value="พล.อ.ท.">พลอากาศโท</option><option value="พล.อ.ท.หญิง">พลอากาศโท หญิง</option><option value="พล.อ.ต.">พลอากาศตรี</option><option value="พล.อ.ต.หญิง">พลอากาศตรี หญิง</option><option value="น.อ.">นาวาอากาศเอก</option><option value="น.อ.หญิง">นาวาอากาศเอก หญิง</option><option value="น.อ.(พิเศษ)">นาวาอากาศเอกพิเศษ</option><option value="น.อ.(พิเศษ) หญิง">นาวาอากาศเอกพิเศษ หญิง</option><option value="น.ท.">นาวาอากาศโท</option><option value="น.ท.หญิง">นาวาอากาศโท หญิง</option><option value="น.ต.">นาวาอากาศตรี</option><option value="น.ต.หญิง">นาวาอากาศตรี หญิง</option><option value="ร.อ.">เรืออากาศเอก</option><option value="ร.อ.หญิง">เรืออากาศเอก หญิง</option><option value="ร.ท.">เรืออากาศโท</option><option value="ร.ท.หญิง">เรืออากาศโท หญิง</option><option value="ร.ต.">เรืออากาศตรี</option><option value="ร.ต.หญิง">เรืออากาศตรี หญิง</option><option value="พ.อ.อ.">พันจ่าอากาศเอก</option><option value="พ.อ.อ.หญิง">พันจ่าอากาศเอก หญิง</option><option value="พ.อ.ท.">พันจ่าอากาศโท</option><option value="พ.อ.ท.หญิง">พันจ่าอากาศโท หญิง</option><option value="พ.อ.ต.">พันจ่าอากาศตรี</option><option value="พ.อ.ต.หญิง">พันจ่าอากาศตรี หญิง</option><option value="จ.อ.">จ่าอากาศเอก</option><option value="จ.อ.หญิง">จ่าอากาศเอก หญิง</option><option value="จ.ท.">จ่าอากาศโท</option><option value="จ.ท.หญิง">จ่าอากาศโท หญิง</option><option value="จ.ต.">จ่าอากาศตรี</option><option value="จ.ต.หญิง">จ่าอากาศตรี หญิง</option><option value="พลฯ">พลทหารอากาศ</option><option value="หม่อม">หม่อม</option><option value="ม.จ.">หม่อมเจ้า</option><option value="ม.ร.ว.">หม่อมราชวงศ์</option><option value="ม.ล.">หม่อมหลวง</option><option value="ดร.">ดร.</option><option value="ศ.ดร.">ศ.ดร.</option><option value="ศ.">ศ.</option><option value="ผศ.ดร.">ผศ.ดร.</option><option value="ผศ.">ผศ.</option><option value="รศ.ดร.">รศ.ดร.</option><option value="รศ.">รศ.</option><option value="นพ.">นพ.</option><option value="พญ.">แพทย์หญิง</option><option value="นสพ.">สัตวแพทย์</option><option value="สพญ.">สพญ.</option><option value="ทพ.">ทพ.</option><option value="ทพญ.">ทพญ.</option><option value="ภก.">เภสัชกร</option><option value="ภกญ.">ภกญ.</option><option value="พระ">พระ</option><option value="พระครู">พระครู</option><option value="พระมหา">พระมหา</option><option value="พระสมุห์">พระสมุห์</option><option value="พระอธิการ">พระอธิการ</option><option value="สามเณร">สามเณร</option><option value="แม่ชี">แม่ชี</option><option value="บาทหลวง">บาทหลวง</option>\
                                            <option value="อื่นๆ">อื่นๆ (กรุณาระบุด้วย)</option>' );
    };
    var disableForm = function() {
        $( '#guest-form input, #guest-form textarea, #guest-form select' ).prop( 'disabled', true );
        $( '#editableForm-butt' ).removeClass( 'disabled' );
        $( '#save-row' ).hide();
    };
    var protectName = function() {
        $( '#title, #otherTitle, #firstName, #lastName' ).prop( 'disabled', true );
    };
    var enableForm = function() {
        $( '#guest-form input, #guest-form textarea, #guest-form select' ).prop( 'disabled', false );
        $( '#editableForm-butt' ).addClass( 'disabled' );
        $( '#save-row' ).show();
    };
    var showFormTools = function() {
        $( '#formTools-row' ).show();
    };
    this.createEditableFormButt = function() {
        $( '#editableForm-butt' ).click( function() {
            // make form editable
            enableForm();
            // protect first and last name to be changed
            protectName();
        } );
    };
    var initialiseForm = function() {
        // initialise variables
        guest = {};
        // guestID = -1;
        tempFirst = "";
        tempLast = "";
        // disable form
        disableForm();
        // clear all fields
        $( '#guest-form' ).trigger("reset");
        // create title field
        createTitleField();
        // amphur
        $( '#amphur' ).html( '<option value="">- เลือกอำเภอ -</option>' );
        // district
        $( '#district' ).html( '<option value="">- เลือกตำบล -</option>' );
        // province
        getThailandInfo( 'province', "", -1, alt_phpUrl );
    };
    var getBookingDetails = function() {
        var title = ( $( '#title' ).val() === "อื่นๆ" ) ? $( '#otherTitle' ).val() : $( '#title' ).val(),
            firstName = $( '#firstName' ).val(),
            lastName = $( '#lastName' ).val(),
            email = $( '#email' ).val(),
            tel = $( '#tel' ).val(),
            address = $( '#address' ).val(),
            district = $( "#district option:selected" ).text(),
            amphur = $( "#amphur option:selected" ).text(),
            province = $( "#province option:selected" ).text(),
            taxNumber = $( '#taxNumber' ).val(),
            otherDetails = $( '#otherDetails' ).val();
        guest = new Guest( title, firstName, lastName, tel, address, district, amphur, province );
        if ( email.length > 0 ) {
            guest.email = email;
        }
        if ( taxNumber.length > 0 ) {
            guest.taxNumber = taxNumber;
        }
        if ( otherDetails.length > 0 ) {
            guest.otherDetails = otherDetails;
        }
    };
    var fillInForm = function( data ) {
        // disable form
        disableForm();
        // show editable button
        showFormTools();
        guestID = data[ 'id' ];
        // check if title is existed
        if ( $( '#title option[value="' + data[ 'title' ] + '"]' ).length > 0 ) {
            $( '#title option[value="' + data[ 'title' ] + '"]' ).attr( 'selected', true );
            $( '#otherTitle-column' ).hide();
        }
        else {
            $( '#title option[value="อื่นๆ"]' ).attr( 'selected', true );
            $( '#otherTitle-column' ).show();
            $( '#otherTitle' ).val( data[ 'title' ] );
        }
        $( '#firstName' ).val( data[ 'firstName' ] );
        $( '#lastName' ).val( data[ 'lastName' ] );
        $( '#email' ).val( data[ 'email' ] )
        $( '#tel' ).val( data[ 'tel' ] );
        $( '#address' ).val( data[ 'address' ] );
        /************************ province ************************/
        var provinceNum = $( '#province option' ).filter( function() {
            return $( this ).html() === data[ "province" ];
        } ).val();
        if ( !isNaN( provinceNum ) ) {
            $( '#province option[value="' + provinceNum + '"]' ).attr( 'selected', true );
        }
        else {
            // reset province selection
            getThailandInfo( 'province', "", -1, alt_phpUrl );
        }
        /*********************** end of province *******************/
        $( '#amphur' ).html( '<option value="1">' + data[ "amphur" ] + '</option>' );
        $( '#district' ).html( '<option value="1">' + data[ "district" ] + '</option>' );
        $( '#taxNumber' ).val( data[ 'taxNumber' ] );
        $( '#otherDetails' ).val( data[ 'otherDetails' ] );
    };
    this.retrieveGuests = function( firstName, lastName ) {
        if ( tempFirst !== firstName || tempLast !== lastName ) {
            tempFirst = firstName;
            tempLast = lastName;
            startLoadingAni();
            $.ajax( {
                type: "POST",
                url: alt_phpUrl + "guestFinder/guestRetriever.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify( {
                    "firstName": tempFirst,
                    "lastName": tempLast
                } ),
                success: function( data ) {
                    if ( isJson( data ) ) {
                        fillInForm( jQuery.parseJSON( data ) );
                    }
                    else {
                        showErrorReveal( data );
                    }
                    stopLoadingAni();
                }
            } );
        }
    };
    this.createConfirmButt = function() {
        $( '#save-butt' ).click( function() {
            // check if all guest's details are valid
            $( '#guest-form' ).on( 'valid.fndtn.abide', function() {
                startLoadingAni();
                getBookingDetails();
                $.ajax( {
                    type: "POST",
                    contentType: "application/json; charset=utf-8",
                    url: alt_phpUrl + "guestFinder/updateGuest.php",
                    data: JSON.stringify( guest ),
                    success: function( response ) {
                        if ( response.length === 0 ) {
                            $( '#updateGuest-reveal' ).foundation( 'reveal', 'open' );
                        }
                        else {
                            showErrorReveal( response );
                        }
                        stopLoadingAni();
                    }
                } );
            } );
        } );
    };
}

function showOtherTitles( value ) {
    value === "อื่นๆ" ? $( '#otherTitle-column' ).show() : $( '#otherTitle-column' ).hide();
}