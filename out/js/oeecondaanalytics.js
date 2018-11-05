$(document).ready(function() {
    $('#cookieNote .oeecondaanalytics-optout').on('click', function() {
        oeEcondaAnalyticsOptOut();
    });
    $('#cookieNote .oeecondaanalytics-optin').on('click', function() {
        oeEcondaAnalyticsOptIn();
    });
    if ($('#oeecondaanalytics-update').length) {

        if (oeEcondaAnalyticsGetCookie('emos_optout') === '1') {
            $('#oeecondaanalytics-update input[value="DENY"]').attr('checked', true);
        }
        if (oeEcondaAnalyticsGetCookie('emos_optout') === '0') {
            $('#oeecondaanalytics-update input[value="ALLOW"]').attr('checked', true);
        }
        $('#oeecondaanalytics-update button').on('click', function() {
            switch($('#oeecondaanalytics-update input:checked').val()) {
                case 'ALLOW':
                    oeEcondaAnalyticsOptIn();
                    break;
                case 'DENY':
                    oeEcondaAnalyticsOptOut();
                    break;
            }
        });
    }
});
