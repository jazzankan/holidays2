/**
 * Created by Anders on 2017-01-30.
 */
$(document).ready(function(){

    $('.comiseo-daterangepicker-triggerbutton').click(function() {
       //var inneh = $('.comiseo-daterangepicker-triggerbutton').text();
        //alert(inneh);
        //var range = _dateRangePicker.getRange();
        var range = $.widget.getRange;
        alert(range);
    });


});
