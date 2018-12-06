<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Application;

use OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\HttpErrorsDisplayer;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\ActiveUserDataProvider;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\CategoryPathBuilder;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\SearchDataProvider;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\OrderStepsMapModifier;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\PageMapModifier;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\EntityModifierByCurrentAction;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\EntityModifierByCurrentBasketAction;
use OxidEsales\EcondaTrackingComponent\Adapter\Page\PageIdentifiers;
use OxidEsales\EcondaTrackingComponent\Adapter\Page\PageMap;
use OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation\ProductDataPreparator;
use OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation\ProductTitlePreparator;
use OxidEsales\EcondaTrackingComponent\Adapter\ActivePageEntityPreparator;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\ActivePageEntity;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\ActivePageEntityInterface;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\File\EmosFileData;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\TrackingCodeGenerator;
use OxidEsales\EcondaTrackingComponent\File\FileSystem;
use OxidEsales\EcondaTrackingComponent\File\JsFileLocator;
use OxidEsales\EcondaTrackingComponent\File\JsFileUploadFactory;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopIdCalculator;
use Smarty;

/**
 * Class responsible for building objects.
 */
class Factory
{
    /**
     * @return JsFileLocator
     */
    public function makeEmosJsFileLocator()
    {
        return new JsFileLocator(
            Registry::getConfig()->getOutDir(),
            'oeecondaanalytics',
            EmosFileData::TRACKING_CODE_FILE_NAME,
            Registry::getConfig()->getOutUrl(null, null, true),
            ShopIdCalculator::BASE_SHOP_ID
        );
    }

    /**
     * @return FileSystem
     */
    public function makeFileSystem()
    {
        return new FileSystem(new \Symfony\Component\Filesystem\Filesystem());
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function makeEmosJsFileUploader()
    {
        $fileLocator = $this->makeEmosJsFileLocator();
        $jsFileUploadFactory = new JsFileUploadFactory(
            $fileLocator->getJsDirectoryLocation(),
            $fileLocator->getFileName()
        );

        return $jsFileUploadFactory->makeFileUploader();
    }

    /**
     * @param ActivePageEntityInterface $activePageEntity
     *
     * @return TrackingCodeGenerator
     */
    public function makeTrackingCodeGenerator(ActivePageEntityInterface $activePageEntity)
    {
        return new TrackingCodeGenerator(
            $activePageEntity,
            $this->makeEmosJsFileLocator()->getJsFileUrl()
        );
    }

    /**
     * @param Smarty $templateEngine
     *
     * @return ActivePageEntityPreparator
     */
    public function makeActivePageEntityPreparator($templateEngine)
    {
        $activeUser = oxNew(User::class);
        $activeUser->loadActiveUser();
        $categoryPathBuilder = oxNew(CategoryPathBuilder::class);
        $pageIdentifiers = oxNew(PageIdentifiers::class);
        $productTitlePreparator = oxNew(ProductTitlePreparator::class);
        /** @var PageMapModifier $pageMapModifier */
        $pageMapModifier = oxNew(
            PageMapModifier::class,
            $categoryPathBuilder,
            $productTitlePreparator,
            oxNew(ActiveControllerCategoryPathBuilder::class),
            $pageIdentifiers,
            oxNew(PageMap::class)
        );
        $productDataPreparator = oxNew(ProductDataPreparator::class, $productTitlePreparator);
        /** @var EntityModifierByCurrentAction $emosModifier */
        $trackingCodeGeneratorModifier = oxNew(
            EntityModifierByCurrentAction::class,
            $categoryPathBuilder,
            $productDataPreparator,
            oxNew(ProductTitlePreparator::class),
            oxNew(PageIdentifiers::class),
            oxNew(ActiveUserDataProvider::class),
            oxNew(SearchDataProvider::class, $templateEngine)
        );
        $orderStepsMapModifier = oxNew(
            OrderStepsMapModifier::class,
            oxNew(PageMap::class),
            oxNew(PageIdentifiers::class)
        );
        $trackingCodeGeneratorModifierForBasketEvents = oxNew(
            EntityModifierByCurrentBasketAction::class,
            $categoryPathBuilder,
            $productDataPreparator
        );
        $trackingCodePreparator = oxNew(
            ActivePageEntityPreparator::class,
            oxNew(ActivePageEntity::class),
            $pageIdentifiers,
            $activeUser,
            $pageMapModifier,
            $trackingCodeGeneratorModifier,
            $orderStepsMapModifier,
            $trackingCodeGeneratorModifierForBasketEvents,
            oxNew(ActiveUserDataProvider::class)
        );

        return $trackingCodePreparator;
    }

    /**
     * @return HttpErrorsDisplayer
     */
    public function makeHttpErrorDisplayer()
    {
        return oxNew(HttpErrorsDisplayer::class);
    }
}
