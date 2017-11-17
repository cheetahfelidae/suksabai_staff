function FinanceTable() {
    var dateMonth;
    // max min net total
    var maxNetTotal,
        minNetTotal;
    var maxNetTotalRow,
        minNetTotalRow;
    // max min total rooms
    var maxTotalRooms,
        minTotalRooms;
    var maxTotalRoomsRow,
        minTotalRoomsRow;
    var maxExtraBedPrice;
    var maxExtraBedPriceRow;
    var maxBreakfastPrice;
    var maxBreakfastPriceRow;
    var date,
        cmpDate,
        grandTotal_outerTable;
    var roomNum,
        roomPrice,
        breakfastPrice,
        extraBedPrice;
    // total for inner table
    var totalRooms_innerTable,
        totalPrice_innerTable,
        totalBreakfastPrice_innerTable,
        totalExtraBedPrice_innerTable,
        netTotal_innerTable;
    var initVariables = function() {
        // max min net total
        maxNetTotal = 0;
        minNetTotal = 9007199254740992;
        maxNetTotalRow = "";
        minNetTotalRow = "";
        // max min total rooms
        maxTotalRooms = 0;
        minTotalRooms = 9007199254740992;
        maxTotalRoomsRow = "";
        minTotalRoomsRow = "";
        maxExtraBedPrice = 0;
        maxExtraBedPriceRow = "";
        maxBreakfastPrice = 0;
        maxBreakfastPriceRow = 0;
        date = "";
        cmpDate = "";
        grandTotal_outerTable = 0;
        roomNum = 0;
        roomPrice = 0;
        breakfastPrice = 0;
        extraBedPrice = 0;
        // total for inner table
        totalRooms_innerTable = 0;
        totalPrice_innerTable = 0;
        totalBreakfastPrice_innerTable = 0;
        totalExtraBedPrice_innerTable = 0;
        netTotal_innerTable = 0;
    };
    this.initialise = function( data, numberDates ) {
        dateMonth = numberDates;
        initVariables();
        // convert json data
        data = jQuery.parseJSON( data );
        // clear previous resutls
        $( '#monthlyFinance-outerTable, #summary-outerTable' ).empty();
        // create booking rows
        $.each( data, function( i, booking ) {
            cmpDate = booking[ "stayDate" ];
            roomNum = booking[ "roomNums" ];
            roomPrice = booking[ "roomPrice" ];
            breakfastPrice = booking[ "breakfastPrice" ];
            extraBedPrice = booking[ "extraBedPrice" ];
            if ( date !== cmpDate ) {
                // create summary for recently inner table
                if ( i > 0 ) {
                    createSummary_innerTable();
                }
                // reset inner table variables
                totalRooms_innerTable = 0;
                totalPrice_innerTable = 0;
                totalBreakfastPrice_innerTable = 0;
                totalExtraBedPrice_innerTable = 0;
                date = cmpDate;
                // create inner table for first date or next date
                $( '#monthlyFinance-outerTable' ).append( '<tr>\
                                                        <td class="text-center">' + convertDateFormatToFullDateFormat( date ) + '</td>\
                                                        <td class="text-center">\
                                                            <table class="noBottomMargin" id="booking-' + date + '-innerTable">\
                                                                <tr>\
                                                                    <th class="text-center">ห้องหมายเลข</th>\
                                                                    <th class="text-center">ราคา</th>\
                                                                    <th class="text-center">อาหารเช้า</th>\
                                                                    <th class="text-center">เตียงเสริม</th>\
                                                                    <th class="text-center">ราคาสุทธิ</th>\
                                                                </tr>\
                                                                ' + getRow_innerTable() + '\
                                                            </table>\
                                                        </td>\
                                                    </tr>' );
            }
            else {
                // add row for inner table
                $( '#booking-' + date + '-innerTable' ).append( getRow_innerTable() );
            }
            totalRooms_innerTable++;
            totalPrice_innerTable += roomPrice;
            totalBreakfastPrice_innerTable += breakfastPrice;
            totalExtraBedPrice_innerTable += extraBedPrice;

            // create summary for recently inner table
            if ( i === ( data.length - 1 ) ) {
                createSummary_innerTable();
            }
        } );
        createSummary_outerTable();
    };
    /************************************************* Inner Table *****************************************************/
    var createSummary_innerTable = function() {
        netTotal_innerTable = totalPrice_innerTable + totalBreakfastPrice_innerTable + totalExtraBedPrice_innerTable;
        $( '#booking-' + date + '-innerTable' ).append( '<tr>\
                                                            <th class="text-center" style="background-color: #9b59b6; color: white">' + totalRooms_innerTable + ' ห้อง</th>\
                                                            <th class="text-center">' + totalPrice_innerTable + ' บาท</th>\
                                                            ' + ( totalBreakfastPrice_innerTable ? '<th class="text-center" style="background-color: #16a085; color: white">' + totalBreakfastPrice_innerTable + ' บาท</th>' : '<th class="text-center">' + totalBreakfastPrice_innerTable + ' บาท</th>' ) + '\
                                                            ' + ( totalExtraBedPrice_innerTable ? '<th class="text-center" style="background-color: #3498db; color: white">' + totalExtraBedPrice_innerTable + ' บาท</th>' : '<th class="text-center">' + totalExtraBedPrice_innerTable + ' บาท</th>' ) + '\
                                                            <th class="text-center" style="background-color: #34495e; color: white;">' + netTotal_innerTable + ' บาท</th>\
                                                        </tr>' );
        grandTotal_outerTable += netTotal_innerTable;
        // find max total rooms row
        if ( maxTotalRooms < totalRooms_innerTable ) {
            maxTotalRooms = totalRooms_innerTable;
            maxTotalRoomsRow = getSummaryRow_innerTable( "#2ecc71", date, maxTotalRooms, "ห้อง" );
        }
        else if ( maxTotalRooms === totalRooms_innerTable ) {
            maxTotalRoomsRow += getSummaryRow_innerTable( "#2ecc71", date, maxTotalRooms, "ห้อง" );
        }
        // find min total rooms row
        if ( minTotalRooms > totalRooms_innerTable ) {
            minTotalRooms = totalRooms_innerTable;
            minTotalRoomsRow = getSummaryRow_innerTable( "#e74c3c", date, minTotalRooms, "ห้อง" );
        }
        else if ( minTotalRooms === totalRooms_innerTable ) {
            minTotalRoomsRow += getSummaryRow_innerTable( "#e74c3c", date, minTotalRooms, "ห้อง" );
        }
        // find max net total row
        if ( maxNetTotal < netTotal_innerTable ) {
            maxNetTotal = netTotal_innerTable;
            maxNetTotalRow = getSummaryRow_innerTable( "#2ecc71", date, maxNetTotal, "บาท" );
        }
        else if ( maxNetTotal === netTotal_innerTable ) {
            maxNetTotalRow += getSummaryRow_innerTable( "#2ecc71", date, maxNetTotal, "บาท" );
        }
        // find min net total row
        if ( minNetTotal > netTotal_innerTable ) {
            minNetTotal = netTotal_innerTable;
            minNetTotalRow = getSummaryRow_innerTable( "#e74c3c", date, minNetTotal, "บาท" );
        }
        else if ( minNetTotal === netTotal_innerTable ) {
            minNetTotalRow += getSummaryRow_innerTable( "#e74c3c", date, minNetTotal, "บาท" );
        }
        // find max breakfast price row
        if ( totalBreakfastPrice_innerTable > 0 ) {
            if ( maxBreakfastPrice < totalBreakfastPrice_innerTable ) {
                maxBreakfastPrice = totalBreakfastPrice_innerTable;
                maxBreakfastPriceRow = getSummaryRow_innerTable( "#16a085", date, maxBreakfastPrice, "บาท" );
            }
            else if ( maxBreakfastPrice === totalBreakfastPrice_innerTable ) {
                maxBreakfastPriceRow += getSummaryRow_innerTable( "#16a085", date, maxBreakfastPrice, "บาท" );
            }
        }
        // find max extra bed price row
        if ( totalExtraBedPrice_innerTable > 0 ) {
            if ( maxExtraBedPrice < totalExtraBedPrice_innerTable ) {
                maxExtraBedPrice = totalExtraBedPrice_innerTable;
                maxExtraBedPriceRow = getSummaryRow_innerTable( "#3498db", date, maxExtraBedPrice, "บาท" );
            }
            else if ( maxExtraBedPrice === totalExtraBedPrice_innerTable ) {
                maxExtraBedPriceRow += getSummaryRow_innerTable( "#3498db", date, maxExtraBedPrice, "บาท" );
            }
        }
    };
    /************************************************* Outer Table *****************************************************/
    var createSummary_outerTable = function() {
        $( '#summary-outerTable' ).show();
        $( '#summary-outerTable' ).append( '<tr>\
                                            <th class="text-center">วันที่รายรับมากที่สุด</th>\
                                            <th>\
                                                <table class="noBottomMargin">\
                                                    <tr>\
                                                        <th class="text-center">วันที่</th>\
                                                        <th class="text-center">รายรับสุทธิ</th>\
                                                    </tr>\
                                                    ' + maxNetTotalRow + '\
                                                </table>\
                                            </th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center">วันที่รายรับน้อยที่สุด</th>\
                                            <th>\
                                                <table class="noBottomMargin">\
                                                    <tr>\
                                                        <th class="text-center">วันที่</th>\
                                                        <th class="text-center">รายรับสุทธิ</th>\
                                                    </tr>\
                                                    ' + minNetTotalRow + '\
                                                </table>\
                                            </th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center">วันที่เข้าพักมากที่สุด</th>\
                                            <th>\
                                                <table class="noBottomMargin">\
                                                    <tr>\
                                                        <th class="text-center">วันที่</th>\
                                                        <th class="text-center">รายรับสุทธิ</th>\
                                                    </tr>\
                                                    ' + maxTotalRoomsRow + '\
                                                </table>\
                                            </th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center">วันที่เข้าพักน้อยที่สุด</th>\
                                            <th>\
                                                <table class="noBottomMargin">\
                                                    <tr>\
                                                        <th class="text-center">วันที่</th>\
                                                        <th class="text-center">รายรับสุทธิ</th>\
                                                    </tr>\
                                                    ' + minTotalRoomsRow + '\
                                                </table>\
                                            </th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center">วันที่มีอาหารเช้ามากที่สุด</th>\
                                            <th>\
                                                <table class="noBottomMargin">\
                                                    <tr>\
                                                        <th class="text-center">วันที่</th>\
                                                        <th class="text-center">ราคา</th>\
                                                    </tr>\
                                                    ' + maxBreakfastPriceRow + '\
                                                </table>\
                                            </th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center">วันที่ใช้เตียงเสริมมากที่สุด</th>\
                                            <th>\
                                                <table class="noBottomMargin">\
                                                    <tr>\
                                                        <th class="text-center">วันที่</th>\
                                                        <th class="text-center">ราคา</th>\
                                                    </tr>\
                                                    ' + maxExtraBedPriceRow + '\
                                                </table>\
                                            </th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center">รายรับเฉลี่ยต่อวัน (ทั้งเดือน)</th>\
                                            <th class="text-center">' + ( grandTotal_outerTable / dateMonth ).toFixed( 3 ) + ' บาท</th>\
                                      </tr>\
                                      <tr>\
                                            <th class="text-center"> รายรับทั้งหมดในเดือนนี้</th>\
                                            <th class ="text-center" style="background-color: #34495e; color: white;"> ' + grandTotal_outerTable + ' บาท</th>\
                                    </tr>' );
    };
    /************************************************* Row Table *****************************************************/
    var getSummaryRow_innerTable = function( color, date, total, unit ) {
        return '<tr style="background-color: ' + color + ';">\
                                    <td class="text-center" style="color: white;">' + convertDateFormatToFullDateFormat( date ) + '</td>\
                                    <td class="text-center" style="color: white;">' + total + ' ' + unit + '</td>\
                                </tr>';
    };
    var getRow_innerTable = function() {
        return '<tr>\
                    <td class="text-center">' + roomNum + '</td>\
                    <td class="text-center">' + roomPrice + '</td>\
                    <td class="text-center">' + breakfastPrice + '</td>\
                    <td class="text-center">' + extraBedPrice + '</td>\
                    <td class="text-center">' + ( roomPrice + extraBedPrice ) + '</td>\
                </tr>';
    };
}