<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\Tab\Container;

use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;

/**
 * @inheritdoc
 */
class TabsListController extends AdminListController
{
    protected $_sThisTemplate = 'oeecondaanalytics_general.tpl';

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->_aViewData['sClassMain'] = __CLASS__;
        parent::__construct();
    }
}
