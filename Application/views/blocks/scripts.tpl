[{$smarty.block.parent}]

[{block name="oeecondaanalytics_add_js_in_head"}]
    [{if $oViewConf->oeEcondaAnalyticsIsTrackingEnabled() === true}]
        <script type="text/javascript">
            function oeEcondaAnalyticsOptIn() {
                oeEcondaAnalyticsSetCookie('emos_optout', '0', 365);
            }

            function oeEcondaAnalyticsOptOut() {
                oeEcondaAnalyticsSetCookie('emos_optout', '1', 365);
            }

            function oeEcondaAnalyticsSetCookie(name, value, days) {
                var expires = '';
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days*24*60*60*1000));
                    expires = '; expires=' + date.toUTCString();
                }
                document.cookie = name + '=' + (value || '')  + expires + '; path=/';
            }
            function oeEcondaAnalyticsGetCookie(name) {
                var nameExpression = name + "=";
                var splitCookie = document.cookie.split(';');
                for(var i=0;i < splitCookie.length;i++) {
                    var c = splitCookie[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameExpression) == 0) return c.substring(nameExpression.length,c.length);
                }
                return null;
            }

            [{if $oViewConf->oeEcondaAnalyticsShowTrackingNote() == 'opt_in'}]
                if (oeEcondaAnalyticsGetCookie('emos_optout') === null) {
                    oeEcondaAnalyticsOptOut();
                }
            [{/if}]
            [{if $oViewConf->oeEcondaAnalyticsShowTrackingNote() == 'opt_out'}]
                if (oeEcondaAnalyticsGetCookie('emos_optout') === null) {
                    oeEcondaAnalyticsOptIn();
                }
            [{/if}]

            window.emos3 = {
                stored : [],
                send : function(p){this.stored.push(p);}
            };
        </script>
        [{oxscript include=$oViewConf->getModuleUrl('oeecondaanalytics','out/js/oeecondaanalytics.js')}]
    [{/if}]
[{/block}]
