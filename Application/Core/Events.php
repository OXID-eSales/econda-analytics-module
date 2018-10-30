<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Application\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Application\Model\Content;

/**
 * Module events while activating/deactivating module.
 */
class Events
{
    const MODULE_NAME = 'module:oeecondaanalytics';

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        $config = Registry::getConfig();
        $activeShopId = $config->getShopId();

        foreach (self::getConfVarsSettings() as $confVar) {
            if (array_key_exists('value', $confVar)) {
                $value = $config->getConfigParam($confVar['name']);
                if (empty($value)) {
                    $value = $confVar['value'];
                }
                $config->saveShopConfVar(
                    $confVar['type'],
                    $confVar['name'],
                    $value,
                    $activeShopId,
                    self::MODULE_NAME
                );
            }
        }

        self::addContentSnippetOptIn();
        self::addContentSnippetOptOut();
        self::addContentSnippetUpdate();
    }

    /**
     * Add content snippet for Opt-In case
     */
    protected static function addContentSnippetOptIn()
    {
        $text = <<<'EOT'
Die [FIRMA] erstellt auf dieser Webseite pseudonyme Nutzerprofile auf Basis Ihres Online-Nutzungsverhaltens und nutzt dazu Cookies.
Dieses Profil ermöglicht es uns, Sie online so ausführlich zu beraten, wie es ein Verkäufer in einem persönlichen Gespräch in einem [FIRMA] Store kann.
Weiterhin können wir unser Angebot und unsere Werbung auf Partnerseiten an Ihre persönlichen Bedürfnisse anpassen,
sodass für Sie relevante Produkte angezeigt und uninteressante Angebote ausgeblendet werden.
Die Verarbeitung erfolgt gemäß Art. 6 Abs. 1 lit. f DSGVO und Sie können jederzeit von Ihren Betroffenenrechten Gebrauch machen.
Wenn Sie dies nicht möchten, können Sie hier widersprechen. Wenn Sie unsicher sind, finden Sie hier die gesamten Datenschutzhinweise.
<div>
    <a class="oeecondaanalytics-optin" href="#" data-dismiss="alert">Ich bin einverstanden (Personalisierung aktivieren)</a>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oeecondaanalyticsoptin'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->setLanguage(0);
            $content->oxcontents__oxloadid = new Field('oeecondaanalyticsoptin');
            $content->oxcontents__oxtitle = new Field('Cookie "Ich bin einverstanden (Personalisierung aktivieren)" Hinweis');
            $content->oxcontents__oxcontent = new Field($text);
            $content->save();

            $content->setLanguage(1);
            $content->oxcontents__oxtitle = new Field('Cookie "I agree (activate econdaanalytics)" hint');
            $content->oxcontents__oxcontent = new Field('Please update this text. An example can be found in German language of this entry.');
            $content->save();
        }
    }

    /**
     * Add content snippet for Opt-Out case
     */
    protected static function addContentSnippetOptOut()
    {
        $text = <<<'EOT'
Die [FIRMA] erstellt auf dieser Webseite pseudonyme Nutzerprofile auf Basis Ihres Online-Nutzungsverhaltens und nutzt dazu Cookies.
Dieses Profil ermöglicht es uns, Sie online so ausführlich zu beraten, wie es ein Verkäufer in einem persönlichen Gespräch in einem [FIRMA] Store kann.
Weiterhin können wir unser Angebot und unsere Werbung auf Partnerseiten an Ihre persönlichen Bedürfnisse anpassen,
sodass für Sie relevante Produkte angezeigt und uninteressante Angebote ausgeblendet werden.
Die Verarbeitung erfolgt gemäß Art. 6 Abs. 1 lit. f DSGVO und Sie können jederzeit von Ihren Betroffenenrechten Gebrauch machen.
Wenn Sie dies nicht möchten, können Sie hier widersprechen. Wenn Sie unsicher sind, finden Sie hier die gesamten Datenschutzhinweise.
<div>
    <a class="oeecondaanalytics-optout" href="#" data-dismiss="alert">Widerspruch (Personalisierung deaktivieren)</a>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oeecondaanalyticsoptout'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->setLanguage(0);
            $content->oxcontents__oxloadid = new Field('oeecondaanalyticsoptout');
            $content->oxcontents__oxtitle = new Field('Cookie "Widerspruch (Personalisierung deaktivieren)" Hinweis');
            $content->oxcontents__oxcontent = new Field($text);
            $content->save();

            $content->setLanguage(1);
            $content->oxcontents__oxtitle = new Field('Cookie "I disagree (deactivate econdaanalytics)" hint');
            $content->oxcontents__oxcontent = new Field('Please update this text. An example can be found in German language of this entry.');
            $content->save();
        }
    }

    /**
     * Add content snippet to update privacy settings
     */
    protected static function addContentSnippetUpdate()
    {
        $textGerman = <<<'EOT'
<div id="oeecondaanalytics-update">
    <h4>Tracking</h4>
    <input type="radio" name="oeecondaanalytics-state" value="ALLOW"> Zulassen
    <input type="radio" name="oeecondaanalytics-state" value="DENY"> Verbieten
    <div><button type="button" class="btn btn-primary">Aktualisieren</button></div>
</div>
EOT;
        $textEnglish = <<<'EOT'
<div id="oeecondaanalytics-update">
    <h4>Tracking</h4>
    <input type="radio" name="oeecondaanalytics-state" value="ALLOW"> Allow
    <input type="radio" name="oeecondaanalytics-state" value="DENY"> Deny
    <div><button type="button" class="btn btn-primary">Update</button></div>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oeecondaanalyticsupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->setLanguage(0);
            $content->oxcontents__oxloadid = new Field('oeecondaanalyticsupdate');
            $content->oxcontents__oxtitle = new Field('Privacy Protection-Einstellungen');
            $content->oxcontents__oxcontent = new Field($textGerman);
            $content->save();

            $content->setLanguage(1);
            $content->oxcontents__oxtitle = new Field('Privacy protection settings');
            $content->oxcontents__oxcontent = new Field($textEnglish);
            $content->save();
        }
    }

    /**
     * Get configuration variables settings.
     *
     * @return array
     */
    protected static function getConfVarsSettings()
    {
        return [
            [
                'group' => '',
                'name' => 'blOeEcondaAnalyticsTracking',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => '',
                'name' => 'sOeEcondaAnalyticsTrackingShowNote',
                'type' => 'str',
                'value' => 'no'
            ],
        ];
    }
}
