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

use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * Returns a string with options for all regions from dwd api
     * in HTML format like on https://www.w3.org/wiki/HTML/Elements/option#Example_A
     *
     * @return string
     */
    public function getOptionsForRegions()
    {
//        /** @var CacheManager $cacheManager */
//        $cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
//        $this->cacheFrontend = $cacheManager->getCache('weather2_dwd_regions');
        if ($this->cacheFrontend->has($this::REGIONS_CACHE_IDENTIFIER)) {
            return $this->cacheFrontend->get($this::REGIONS_CACHE_IDENTIFIER);
        }
        
        $html = '';
        /** @var \stdClass $regions */
        $regions = $this->getRegions();
        /** @var \stdClass $region */
        foreach ($regions as $region) {
            $value = WeatherUtility::convertRegionObjectToValueString($region);
            $displayText = WeatherUtility::convertValueStringToHumanReadableString($value);
            $html .= '<option value="' . $value . '">' . $displayText . '</option>';
        }
        
//        $this->cacheFrontend->set($this::REGIONS_CACHE_IDENTIFIER, $html);
        return $html;
    }
    
    /**
     * Checks the JSON response
     *
     * @return \stdClass|bool Returns a stdClass if response was valid otherwise false
     */
    protected function getRegions()
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
        /** @var string $term */
        $term = GeneralUtility::_GET('term');

        $results = array();
        foreach ($this->getRegions() as $region) {
            $value = WeatherUtility::convertRegionObjectToValueString($region);
            $label = WeatherUtility::convertValueStringToHumanReadableString($value);
            if (stripos($label, $term) !== false) {
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'id' => md5($value)
                );
            }
        }
        $ajaxObj->setContent($results);
        $ajaxObj->setContentFormat('json');
    }
}