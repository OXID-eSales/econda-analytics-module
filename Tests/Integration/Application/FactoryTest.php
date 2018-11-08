<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Tests\Integration\Application;

use FileUpload\FileUpload;
use OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\HttpErrorsDisplayer;
use OxidEsales\EcondaAnalyticsModule\Application\Factory;
use OxidEsales\EcondaTrackingComponent\Adapter\ActivePageEntityPreparator;
use OxidEsales\EcondaTrackingComponent\File\FileSystem;
use OxidEsales\EcondaTrackingComponent\File\JsFileLocator;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\TrackingCodeGenerator;

class FactoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testmakeJsFileLocator()
    {
        $this->assertInstanceOf(JsFileLocator::class, $this->getFactory()->makeEmosJsFileLocator());
    }

    public function testGetFileUploader()
    {
        $this->assertInstanceOf(FileUpload::class, $this->getFactory()->makeEmosJsFileUploader());
    }

    public function testGetFileSystem()
    {
        $this->assertInstanceOf(FileSystem::class, $this->getFactory()->makeFileSystem());
    }

    public function testMakeActivePageEntityPreparator()
    {
        $entityPraparator = $this->getFactory()->makeActivePageEntityPreparator(new \Smarty());
        $this->assertInstanceOf(ActivePageEntityPreparator::class, $entityPraparator);
    }

    public function testMakeTrackingCodeGenerator()
    {
        $entity = $this->getFactory()->makeActivePageEntityPreparator(new \Smarty())->prepareEntity([]);
        $this->assertInstanceOf(TrackingCodeGenerator::class, $this->getFactory()->makeTrackingCodeGenerator($entity));
    }

    public function testMakeHttpErrorDisplayer()
    {
        $this->assertInstanceOf(HttpErrorsDisplayer::class, $this->getFactory()->makeHttpErrorDisplayer());
    }

    protected function getFactory()
    {
        return oxNew(Factory::class);
    }
}
