<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Tests\Integration\Application;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\EcondaAnalyticsModule\Application\Core\Events;

class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testDoesSetDefaultEnableWidgetsConfigurationOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            false,
            Registry::getConfig()->getConfigParam('blOeEcondaAnalyticsEnableWidgets')
        );
    }

    public function testDoesSetDefaultTrackingShowNoteOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            'no',
            Registry::getConfig()->getConfigParam('sOeEcondaAnalyticsTrackingShowNote')
        );
    }

    public function testDoesNotOverwriteAlreadySetTrackingShowNoteOnActivate()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaAnalyticsTrackingShowNote', 'opt_in');

        Events::onActivate();

        $this->assertEquals(
            'opt_in',
            Registry::getConfig()->getConfigParam('sOeEcondaAnalyticsTrackingShowNote')
        );
    }

    /**
     * @dataProvider customWidgetTemplateConfigurationProvider
     */
    public function testDoesNotOverwriteAlreadySetConfigurationOnActivate($configParamName, $expectedTemplate)
    {
        Registry::getConfig()->setConfigParam($configParamName, $expectedTemplate);

        Events::onActivate();

        $this->assertEquals(
            $expectedTemplate,
            Registry::getConfig()->getConfigParam($configParamName)
        );
    }

    public function customWidgetTemplateConfigurationProvider()
    {
        return [
            'start page bargain articles' => [
                'sOeEcondaAnalyticsWidgetTemplateStartPageBargainArticles',
                'testBargainTemplate'
            ],
            'start page top articles' => [
                'sOeEcondaAnalyticsWidgetTemplateStartPageTopArticles',
                'testTopArticleTemplate'
            ],
            'list page' => [
                'sOeEcondaAnalyticsWidgetTemplateListPage',
                'testListTemplate'
            ],
            'details page' => [
                'sOeEcondaAnalyticsWidgetTemplateDetailsPage',
                'testDetailsTemplate'
            ],
            'thank you page' => [
                'sOeEcondaAnalyticsWidgetTemplateThankYouPage',
                'testThankYouTemplate'
            ],
        ];
    }

    public function testInsertDefaultSnippetForOptIn()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oeecondaanalyticsoptin'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oeecondaanalyticsoptin', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForOptIn()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oeecondaanalyticsoptin'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oeecondaanalyticsoptin');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }

    public function testInsertDefaultSnippetForOptOut()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oeecondaanalyticsoptout'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oeecondaanalyticsoptout', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForOptOut()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oeecondaanalyticsoptout'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oeecondaanalyticsoptout');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }

    public function testInsertDefaultSnippetForUpdate()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oeecondaanalyticsupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oeecondaanalyticsupdate', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForUpdate()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oeecondaanalyticsupdate'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oeecondaanalyticsupdate');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }
}
