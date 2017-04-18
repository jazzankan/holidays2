// React to changes in select element, except default option
$(document).ready(function() {
    $('select').change(function() {
        if($('select').val() != "choose"){
            $('#apply').submit();
        }
    });

    $('.sinners').click(function() {
        $('.sinners').show().featherlight(); //$('.myElement').featherlight($content, configuration);
    });
});
