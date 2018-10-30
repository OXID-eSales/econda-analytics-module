<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Application;

use OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\HttpErrorsDisplayer;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Helper\CategoryPathBuilder;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Helper\SearchDataProvider;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Modifiers\OrderStepsMapModifier;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Modifiers\PageMapModifier;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Modifiers\EntityModifierByCurrentAction;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Modifiers\EntityModifierByCurrentBasketAction;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\Page\PageMap;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\ProductPreparation\ProductDataPreparator;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\ProductPreparation\ProductTitlePreparator;
use OxidEsales\EcondaAnalyticsModule\Application\Tracking\ActivePageEntityPreparator;
use OxidEsales\EcondaAnalyticsModule\Component\Tracking\ActivePageEntity;
use OxidEsales\EcondaAnalyticsModule\Component\Tracking\ActivePageEntityInterface;
use OxidEsales\EcondaAnalyticsModule\Component\Tracking\File\EmosFileData;
use OxidEsales\EcondaAnalyticsModule\Component\Tracking\TrackingCodeGenerator;
use OxidEsales\EcondaAnalyticsModule\Component\File\FileSystem;
use OxidEsales\EcondaAnalyticsModule\Component\File\JsFileLocator;
use OxidEsales\EcondaAnalyticsModule\Component\File\JsFileUploadFactory;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
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
        return new JsFileLocator(Registry::getConfig()->getOutDir(), EmosFileData::TRACKING_CODE_FILE_NAME, Registry::getConfig()->getOutUrl());
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
