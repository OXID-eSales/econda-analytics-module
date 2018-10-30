<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Tests\Integration\Application;

use OxidEsales\Eshop\Core\UtilsObject;
use \OxidEsales\EcondaAnalyticsModule\Application\Core\ViewConfig;
use \OxidEsales\Eshop\Core\Registry;
use OxidEsales\EcondaAnalyticsModule\Application\Factory;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Helper\UserActionIdentifier;
use OxidEsales\EcondaAnalyticsModule\Tests\Helper\ActiveControllerPreparatorTrait;

class ViewConfigTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    use ActiveControllerPreparatorTrait;

    public function testShowTrackingNote()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaAnalyticsTrackingShowNote', 'opt_in');
        $this->assertEquals('opt_in', $this->getViewConfig()->oeEcondaAnalyticsShowTrackingNote());
    }

    /**
     * @return object|\OxidEsales\Eshop\Core\ViewConfig|ViewConfig
     */
    protected function getViewConfig()
    {
        return oxNew(\OxidEsales\Eshop\Core\ViewConfig::class);
    }
}
