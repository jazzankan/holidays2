/**
 * Created by Anders on 2017-01-30.
 */
$(document).ready(function(){

    $appName = $('#applicant').text();

    if($('ul').has('li').length){
        $('.saved').show();
    }

    $(function() {

        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Rensa'
            },
            startDate: '2017-06-01',
            endDate: '2017-06-01'

        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            //alert(picker.startDate);
            $interval = $(this).val();
            $('.wait').show().html('<p><img src="spinner.gif"</p>');
            $.post( "../holidays2/dbinsert.php", {interval : $interval })
                .done(function( data ) {
                    getPeriods();
                    $('.wait').hide();
                    $('.saved').show();
                    //alert( "Data Loaded: " + data );
                });
            $(this).val('Välj en semesterperiod!');
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('Välj en semesterperiod!');
        });

        function getPeriods(){
            $.get("../holidays2/dbget.php", function(data){
                //alert( "Data Loaded: " + data );
               $('.saved').html('<h4>Valda perioder:</h4><ul>' + data + '</ul>');
            });
        }

        $( '.col-sm-4').on( 'click', '.delete', function(e) {
            var bookingid = $(this).attr('id');
            //alert(bookingid);
            $.post( "../holidays2/dbdelete.php", {bookingid : bookingid})
                .done(function( data ) {
                    getPeriods();
                });
        });
    });

});
