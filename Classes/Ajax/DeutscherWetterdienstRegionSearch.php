<?php
namespace JWeiland\Weather2\Ajax;

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
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class DeutscherWetterdienstRegionSearch
 *
 * @package JWeiland\Weather2\Ajax
 */
class DeutscherWetterdienstRegionSearch
{
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
        $term = GeneralUtility::_GET('query');
        
        $regions = $repository->findByName($term);
        $suggestions = array();
        
        /** @var WeatherAlertRegion $region */
        foreach ($regions as $region) {
            $district = $region->getDistrict() ? ' (' . $region->getDistrict() . ')' : '';
            $label = $region->getName() . $district;
            $suggestions[] = array(
                'data' => $region->getUid(),
                'value' => $label,
            );
        }
        
        $ajaxObj->addContent('suggestions', $suggestions);
        $ajaxObj->setContentFormat('json');
    }
}