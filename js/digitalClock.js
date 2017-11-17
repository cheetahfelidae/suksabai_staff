function Clock() {
    var MONTH_NAMES,
        DAY_NAMES;
    var newDate;
    this.initialise = function( language ) {
        // Create two variable with the names of the months and days in an array
        if ( language === "th" ) {
            DAY_NAMES = [ "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์" ];
            MONTH_NAMES = [ "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" ];
        }
        else {
            DAY_NAMES = [ "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" ];
            MONTH_NAMES = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
        }
        setInterval( function() {
            // Create a newDate() object
            newDate = new Date();
            second();
            minute();
            hour();
            date();
        }, 1000 );
    };
    this.getTimeDate = function() {
        return $( "#clock-hours" ).html() + ":" + $( "#clock-min" ).html() + ":" + $( "#clock-sec" ).html() + " &nbsp; " + $( '#clock-date' ).html();
    };
    var second = function() {
        var seconds = newDate.getSeconds();
        // Add a leading zero to seconds value
        $( "#clock-sec" ).html( ( seconds < 10 ? "0" : "" ) + seconds );
    };
    var minute = function() {
        var minutes = newDate.getMinutes();
        // Add a leading zero to the minutes value
        $( "#clock-min" ).html( ( minutes < 10 ? "0" : "" ) + minutes );
    };
    var hour = function() {
        var hours = newDate.getHours();
        // Add a leading zero to the hours value
        $( "#clock-hours" ).html( ( hours < 10 ? "0" : "" ) + hours );
    };
    var date = function() {
        // Extract the current date from Date object
        newDate.setDate( newDate.getDate() );
        // Output the day, date, month and year    
        $( '#clock-date' ).html( DAY_NAMES[ newDate.getDay() ] + " " + newDate.getDate() + " " + MONTH_NAMES[ newDate.getMonth() ] + " " + newDate.getFullYear() );
    };
}