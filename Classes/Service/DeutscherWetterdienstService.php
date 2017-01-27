<?php
namespace JWeiland\Weather2\Service;

/*
* This file is part of the TYPO3 CMS project.
*
* It is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License, either version 2
* of the License, or any later version.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*
* The TYPO3 project - inspiring people to share!
*/

use JWeiland\Weather2\Domain\Model\WeatherAlertRegion;
use JWeiland\Weather2\Domain\Repository\WeatherAlertRegionRepository;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class DeutscherWetterdienstService
 *
 * @package JWeiland\Weather2\Service
 */
class DeutscherWetterdienstService implements SingletonInterface
{
    /**
     * URL to api from Deutscher Wetterdienst
     */
    const REGIONS_API_URL = 'https://www.dwd.de/DWD/warnungen/warnapp_landkreise/viewer/gemeinden.js';
    
    /**
     * Identifier for regions
     */
    const REGIONS_CACHE_IDENTIFIER = 'weather2SavedRegionsForDwd';
    
    /**
     * Cache frontend
     *
     * @var FrontendInterface
     */
    protected $cacheFrontend;
        
    /**
     * Fetch and returns the regions from Deutscher Wetterdienst
     * Regions will be cached
     *
     * @return \stdClass|bool Returns a stdClass if response was valid otherwise false
     */
    public function getRegions()
    {
        /** @var CacheManager $cacheManager */
        $cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
        $this->cacheFrontend = $cacheManager->getCache('weather2_dwd_regions');
        if ($this->cacheFrontend->has($this::REGIONS_CACHE_IDENTIFIER)) {
            return json_decode($this->cacheFrontend->get($this::REGIONS_CACHE_IDENTIFIER));
        }
        
        $response = @file_get_contents($this::REGIONS_API_URL);
        if ($response === false) {
            $errorClass = new \stdClass();
            $errorClass->name = 'Could not fetch regions from DWD. Take a look into the Log module';
            return $errorClass;
        }
        
        // remove javascript part from response
        $pattern = '/^var gemeinden = /';
        $response = preg_replace($pattern, '', $response);
        
        $this->cacheFrontend->set($this::REGIONS_CACHE_IDENTIFIER, $response);
        return json_decode($response);
    }
    
    /**
     * Renders regions ....
     *
     * @param array $params
     * @param AjaxRequestHandler $ajaxObj
     * @return void
     */
    public function renderRegions($params = array(), AjaxRequestHandler &$ajaxObj = null)
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var WeatherAlertRegionRepository $repository */
        $repository = $objectManager->get('JWeiland\\Weather2\\Domain\\Repository\\WeatherAlertRegionRepository');
        /** @var string $term */
        $term = GeneralUtility::_GET('term');
        
        $regions = $repository->findByName($term);
        $results = array();

        /** @var WeatherAlertRegion $region */
        foreach ($regions as $region) {
            $label = $region->getName() . ($region->getDistrict() ? ' (' . $region->getDistrict() . ')' : '');
            $results[] = array(
                'value' => $region->getUid(),
                'label' => $label,
            );
        }
        
        $ajaxObj->setContent($results);
        $ajaxObj->setContentFormat('json');
    }
}