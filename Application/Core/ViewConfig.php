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
        return (bool) $this->getConfig()->getConfigParam('blOeEcondaAnalyticsTracking');
    }

    /**
     * @return string
     */
    public function oeEcondaAnalyticsShowTrackingNote(): string
    {
        return $this->getConfig()->getConfigParam('sOeEcondaAnalyticsTrackingShowNote');
    }
}
