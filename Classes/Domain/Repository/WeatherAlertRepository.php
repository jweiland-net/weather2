<?php
namespace JWeiland\Weather2\Domain\Repository;

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

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for WeatherAlertRegion
 */
class WeatherAlertRepository extends Repository
{
    /**
     * Contains the settings of the current extension
     *
     * @var array
     */
    protected $settings;
    
    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;
    
    /**
     * Injects a ConfigurationManager
     *
     * @param ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
    }
    
    /**
     * Returns current alerts filtered by user selection
     *
     * @return QueryResultInterface|array
     */
    public function findCurrentSelection()
    {
        $regions = explode(',', $this->settings['regions']);
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->contains('regions', $regions),
                $query->lessThan('starttime', $GLOBALS['EXEC_TIME']),
                $query->greaterThanOrEqual('endtime', $GLOBALS['EXEC_TIME'])
            )
        );
        return $query->execute();
    }
}