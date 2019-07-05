<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information.
 */
$aModule = [
    'id'           => 'oeecondaanalytics',
    'title'        => 'OXID analytics module powered by Econda',
    'description'  => [
        'de' => 'Modul fügt Analytik-Funktionalität hinzu',
        'en' => 'Module adds analytics functionality.',
    ],
    'thumbnail' => '/out/pictures/logo.png',
    'version' => '1.2.0',
    'author' => 'OXID eSales AG',
    'url' => 'https://www.oxid-esales.com',
    'email' => 'info@oxid-esales.com',
    'extend' => [
        \OxidEsales\Eshop\Core\ViewConfig::class => \OxidEsales\EcondaAnalyticsModule\Application\Core\ViewConfig::class,
        \OxidEsales\Eshop\Application\Component\Widget\CookieNote::class => \OxidEsales\EcondaAnalyticsModule\Application\Component\Widget\CookieNote::class,
        \OxidEsales\Eshop\Application\Model\Article::class => \OxidEsales\EcondaAnalyticsModule\Application\Model\Product::class,
    ],
    'controllers' => [
        'oeecondaanalyticsadmin' => \OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\Tab\Container\TabsContainerController::class,
        'oeecondaanalyticstracking' => \OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\Tab\TrackingTabController::class,
        'oeecondaanalyticsgeneral' => \OxidEsales\EcondaAnalyticsModule\Application\Controller\Admin\Tab\Container\TabsListController::class,
    ],
    'events' => [
        'onActivate'   => \OxidEsales\EcondaAnalyticsModule\Application\Core\Events::class . '::onActivate',
    ],
    'templates' => [
        'oeecondaanalytics_frameset.tpl' => 'oe/econdaanalytics/Application/views/admin/tpl/container/oeecondaanalytics_frameset.tpl',
        'oeecondaanalytics_general.tpl' => 'oe/econdaanalytics/Application/views/admin/tpl/container/oeecondaanalytics_general.tpl',
        'oeecondaanalytics_tracking_tab.tpl' => 'oe/econdaanalytics/Application/views/admin/tpl/oeecondaanalytics_tracking_tab.tpl',
        'oeecondaanalyticscookienote.tpl' => 'oe/econdaanalytics/Application/views/widget/header/cookienote.tpl',
    ],
    'blocks' => [
        [
            'template' => 'layout/base.tpl',
            'block'=>'base_style',
            'file'=>'Application/views/blocks/scripts.tpl'
        ],
    ],
    'smartyPluginDirectories' => [
        'Application/Core/Smarty/Plugin'
    ],
];
