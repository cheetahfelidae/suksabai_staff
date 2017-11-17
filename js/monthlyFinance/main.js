var financeTable;
var date;
/**************************************************************************************************************************/
/******************************************************** Main Fn *********************************************************/
/**************************************************************************************************************************/
$( document ).ready( function() {
    financeTable = new FinanceTable();
    createSelectMonth();
    createSearchFinanceButt();
    ( new Clock() ).initialise( "th" );
     // set all reveals
    createReloadPageButt();
    disableCloseOnBgReveal();
} );

function createSelectMonth() {
    $( '#month-input' ).datepicker( {
        dateFormat: "MM yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        closeText: "ตกลง",
        onClose: function( dateText, inst ) {
            function isDonePressed() {
                return ( $( '#ui-datepicker-div' ).html().indexOf( 'ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover' ) > -1 );
            }
            if ( isDonePressed() ) {
                var month = $( "#ui-datepicker-div .ui-datepicker-month :selected" ).val();
                var year = $( "#ui-datepicker-div .ui-datepicker-year :selected" ).val();
                date = new Date( year, month, 1 );
                $( this ).datepicker( 'setDate', date ).trigger( 'change' );
                $( '#month-input' ).focusout(); //Added to remove focus from datepicker input box on selecting date
            }
        },
        beforeShow: function( input, inst ) {
            inst.dpDiv.addClass( 'month_year_datepicker' );
            if ( ( datestr = $( this ).val() ).length > 0 ) {
                year = datestr.substring( datestr.length - 4, datestr.length );
                month = datestr.substring( 0, 2 );
                $( this ).datepicker( 'option', 'defaultDate', new Date( year, month - 1, 1 ) );
                $( this ).datepicker( 'setDate', new Date( year, month - 1, 1 ) );
                $( ".ui-datepicker-calendar" ).hide();
            }
        }
    } );
    $( "#month-butt" ).click( function() {
        $( '#month-input' ).datepicker( "show" );
    } );
}

function createSearchFinanceButt() {
    $( '#request-form' ).on( 'valid.fndtn.abide', function() {
        getBooking( date.getMonth(), date.getFullYear() );
    } );
}

function getBooking( month, year ) {
    var startDay = new Date( year, month, 1 ),
        endDay = new Date( year, month + 1, 0 );
    startLoadingAni();
    $.ajax( {
        type: "POST",
        url: alt_phpUrl + "monthlyFinance/monthlyFinance.php",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify( {
            "start": convertDateObjToDateFormat( startDay ),
            "end": convertDateObjToDateFormat( endDay )
        } ),
        success: function( data ) {
            if ( isJson( data ) ) {
                financeTable.initialise( data, getDaysInMonth( month, year ) );
            }
            else {
                if ( data === "json is empty" ) {
                    $( '#notFound-reveal' ).foundation( 'reveal', 'open' );
                }
                else {
                    showErrorReveal( data );
                }
            }
            stopLoadingAni();
        }
    } );
}