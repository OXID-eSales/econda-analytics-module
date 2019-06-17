<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Application\Core;

/**
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    /**
     * @return bool
     */
    public function oeEcondaAnalyticsIsTrackingEnabled(): bool
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        return (bool) $config->getConfigParam('blOeEcondaAnalyticsTracking');
    }

    /**
     * @return string
     */
    public function oeEcondaAnalyticsShowTrackingNote(): string
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        return $config->getConfigParam('sOeEcondaAnalyticsTrackingShowNote');
    }
}
