<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Tests\Acceptance;

abstract class BaseAcceptanceTestCase extends \OxidEsales\TestingLibrary\AcceptanceTestCase
{
    protected function enableTracking()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOeEcondaAnalyticsTracking' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oeecondaanalytics'
            ]
        ]);
    }
}
