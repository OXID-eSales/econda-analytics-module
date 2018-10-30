[{$smarty.block.parent}]

[{block name="oeecondaanalytics_add_js_in_head"}]
    [{capture append="oxidBlock_pageHead"}]
        [{*<script type="text/javascript" src="[{$oViewConf->getModuleUrl('oeecondaanalytics', 'out/js/econda-recommendations.js')}]"></script>*}]
        <script type="text/javascript">
            [{if $oViewConf->oeEcondaAnalyticsShowTrackingNote() == 'opt_in'}]
            if (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === 'UNKNOWN') {
                var emosProps = {};
                econda.privacyprotection.applyAndStoreNewPrivacySettings(
                    emosProps,
                    {
                        "permissions:profile": {
                            state: "DENY"
                        }
                    }
                );
            }
            [{/if}]
            [{if $oViewConf->oeEcondaAnalyticsShowTrackingNote() == 'opt_out'}]
            if (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === 'UNKNOWN') {
                var emosProps = {};
                econda.privacyprotection.applyAndStoreNewPrivacySettings(
                    emosProps,
                    {
                        "permissions:profile": {
                            state: "ALLOW"
                        }
                    }
                );
            }
            [{/if}]
        </script>
    [{/capture}]
    [{oxscript include=$oViewConf->getModuleUrl('oeecondaanalytics','out/js/oeecondaanalytics.js')}]
    [{if $oViewConf->oeEcondaAnalyticsEnableWidgets()}]
        [{if $oViewConf->oeEcondaAnalyticsIsLoginAction()}]
        <script type="text/javascript">
            econda.data.visitor.login({
                ids: {userId: '[{$oViewConf->oeEcondaAnalyticsGetLoggedInUserHashedId()}]', emailHash: '[{$oViewConf->oeEcondaAnalyticsGetLoggedInUserHashedEmail()}]'}
            });
        </script>
        [{/if}]
        [{if $oViewConf->oeEcondaAnalyticsIsLogoutAction()}]
        <script type="text/javascript">
            econda.data.visitor.logout();
        </script>
        [{/if}]
        [{if $oViewConf->oeEcondaAnalyticsIsLoginAction() || $oViewConf->isStartPage()}]
        <script type="text/javascript">
            econda.privacyprotection.updatePrivacySettingsFromBackend('[{$oViewConf->oeEcondaAnalyticsGetClientKey()}]', 'privacy_protection');
        </script>
        [{/if}]
    [{/if}]
[{/block}]
