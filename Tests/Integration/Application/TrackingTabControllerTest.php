<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaAnalyticsModule\Tests\Integration\Application;

use FileUpload\FileUpload;
use OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\Tab\TrackingTabController;
use OxidEsales\EcondaAnalyticsModule\Application\Factory;
use OxidEsales\EcondaTrackingComponent\File\FileSystem;
use stdClass;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;

class TrackingTabControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetTrackingScriptMessageIfEnabledWhenFileIsPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(true);

        $controller = $this->getController($jsFileLocatorStub);
        $this->assertNotEmpty($controller->getTrackingScriptMessageIfEnabled());
    }

    public function testGetTrackingScriptMessageIfEnabledWhenFileIsNotPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(false);

        $controller = $this->getController($jsFileLocatorStub);
        $this->assertEmpty($controller->getTrackingScriptMessageIfEnabled());
    }

    public function testGetTrackingScriptMessageIfDisabledWhenFileIsPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(true);

        $controller = $this->getController($jsFileLocatorStub);
        $this->assertEmpty($controller->getTrackingScriptMessageIfDisabled());
    }

    public function testGetTrackingScriptMessageIfDisabledWhenFileIsNotPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(false);

        $controller = $this->getController($jsFileLocatorStub);
        $this->assertNotEmpty($controller->getTrackingScriptMessageIfDisabled());
    }

    public function testUploadFailureWhenCreatingDirectory()
    {
        $controller = oxNew(TrackingTabController::class, $this->getFactoryStubWhenNotPossibleToCreateDirectory());
        $controller->upload();
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        $this->assertNotNull($errors, 'Error must be set when unable to create directory.');
    }

    public function testUploadFailureWhenUploadingFile()
    {
        $controller = oxNew(TrackingTabController::class, $this->getFactoryStubWhenUploadingFileFailure());
        $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNotNull($errors, 'Error must be set when unable to upload file.');
    }

    public function testUploadSuccess()
    {
        $controller = oxNew(TrackingTabController::class, $this->getFactoryStubWhenUploadingFileSucceeds());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNull($errors, 'Some error appeared during file upload.');
    }

    protected function getFactoryStubWhenNotPossibleToCreateDirectory()
    {
        // Mock SymfonyFileSystem instead of FileSystem,
        // because it uses return type hints,
        // which are not compatible with phpinit 4.8.26 (this version is used in b-6.1.x shop)
        $mockedFileSystem = $this
            ->getMockBuilder(SymfonyFileSystem::class)
            ->setMethods(['mkdir'])
            ->getMock();
        $mockedFileSystem
            ->method('mkdir')
            ->willThrowException(new IOException(''));
        $fileSystem = new FileSystem($mockedFileSystem);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);

        return $factory;
    }

    protected function getFactoryStubWhenUploadingFileSucceeds()
    {
        // Mock SymfonyFileSystem instead of FileSystem,
        // because it uses return type hints,
        // which are not compatible with phpinit 4.8.26 (this version is used in b-6.1.x shop)
        $mockedFileSystem = $this
            ->getMockBuilder(SymfonyFileSystem::class)
            ->setMethods(['mkdir'])
            ->getMock();
        $fileSystem = new FileSystem($mockedFileSystem);

        $fileUploader = $this->getMockBuilder(FileUpload::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileUploader->method('processAll')->willReturn([[]]);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeEmosJsFileUploader', 'makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $factory->method('makeEmosJsFileUploader')->willReturn($fileUploader);

        return $factory;
    }

    protected function getFactoryStubWhenUploadingFileFailure()
    {
        // Mock SymfonyFileSystem instead of FileSystem,
        // because it uses return type hints,
        // which are not compatible with phpinit 4.8.26 (this version is used in b-6.1.x shop)
        $mockedFileSystem = $this
            ->getMockBuilder(SymfonyFileSystem::class)
            ->setMethods(['mkdir'])
            ->getMock();
        $fileSystem = new FileSystem($mockedFileSystem);

        $fileUploader = $this->getMockBuilder(FileUpload::class)
            ->disableOriginalConstructor()
            ->getMock();
        $errorObject = new StdClass();
        $errorObject->error = 'some error';
        $fileUploader->method('processAll')->willReturn([[$errorObject]]);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeEmosJsFileUploader', 'makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $factory->method('makeEmosJsFileUploader')->willReturn($fileUploader);

        return $factory;
    }

    /**
     * @param FileSystem $fileSystem
     * @return TrackingTabController
     */
    protected function getController($fileSystem)
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $controller = oxNew(TrackingTabController::class, $factory);

        return $controller;
    }

    /**
     * @param bool $isFilePresent
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FileSystem
     */
    protected function makeFileSystemStub($isFilePresent)
    {
        // Mock SymfonyFileSystem instead of FileSystem,
        // because it uses return type hints,
        // which are not compatible with phpinit 4.8.26 (this version is used in b-6.1.x shop)
        $mockedFileSystem = $this
            ->getMockBuilder(SymfonyFileSystem::class)
            ->setMethods(['exists'])
            ->getMock();
        $mockedFileSystem
            ->method('exists')
            ->willReturn($isFilePresent);
        $fileSystemStub = new FileSystem($mockedFileSystem);

        return $fileSystemStub;
    }
}
