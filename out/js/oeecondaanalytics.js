function oeEcondaAnalyticsOptIn() {
    var emosProps = {};
    econda.privacyprotection.applyAndStoreNewPrivacySettings(
        emosProps,
        {
            "permissions:profile": {
                state: "ALLOW"
            }
        }
    );
    window.emos3.send(emosProps);
}

function oeEcondaAnalyticsOptOut() {
    var emosProps = {};
    econda.privacyprotection.applyAndStoreNewPrivacySettings(
        emosProps,
        {
            "permissions:profile": {
                state: "DENY"
            }
        }
    );
    window.emos3.send(emosProps);
}

$(document).ready(function() {
    $('#cookieNote .oeecondaanalytics-optout').on('click', function() {
        oeEcondaAnalyticsOptOut();
    });
    $('#cookieNote .oeecondaanalytics-optin').on('click', function() {
        oeEcondaAnalyticsOptIn();
    });
    if ($('#oeecondaanalytics-update').length) {
        switch (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state) {
            case 'ALLOW':
                $('#oeecondaanalytics-update input[value="ALLOW"]').attr('checked', true);
                break;
            case 'DENY':
                $('#oeecondaanalytics-update input[value="DENY"]').attr('checked', true);
                break;
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
