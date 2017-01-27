<?php
namespace JWeiland\Weather2\Task;

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
use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * DeutscherWetterdienstTask Class for Scheduler
 */
class DeutscherWetterdienstTask extends AbstractTask
{
    /**
     * Source for alerts
     */
    const API_URL = 'http://www.dwd.de/DWD/warnungen/warnapp/json/warnings.json';
    
    /**
     * Weather alert repository
     *
     * @var WeatherAlertRegionRepository
     */
    protected $weatherAlertRepository;
    
    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $objectManager;
    
    /**
     * The TYPO3 database connection
     *
     * @var DatabaseConnection
     */
    protected $dbConnection = null;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $dbExtTable = 'tx_weather2_domain_model_weatheralert';
    
    /**
     * Execution time
     *
     * @var string
     */
    protected $execTime = '';
    
    /**
     * JSON response from dwd api
     *
     * @var \stdClass
     */
    protected $responseClass = null;
    
    /**
     * Timestamp from dwd response
     *
     * @var int
     */
    protected $responseTimestamp = 0;
    
    /**
     * Regions to be saved
     *
     * @var array
     */
    public $selectedRegions = array();
    
    /**
     * Record storage page
     *
     * @var int
     */
    public $recordStoragePage = 0;
    
    /**
     * If true old alerts will be removed after $removeOldItemsAfterHours
     *
     * @var bool
     */
    public $removeOldAlerts = false;
    
    /**
     * If $removeOldItems is true alerts will be removed after its value
     *
     * @var int
     */
    public $removeOldAlertsHours = 0;
    
    /**
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     *
     * @return bool
     */
    public function execute()
    {
        /** @var ObjectManager $objectManager */
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var WeatherAlertRegionRepository $repository */
        $this->weatherAlertRepository = $this->objectManager->get('JWeiland\\Weather2\\Domain\\Repository\\WeatherAlertRegionRepository');
        $this->writeToLog('Executed with this settings: ' . json_encode($this), 0);
        $this->dbConnection = $this->getDatabaseConnection();
        $response = @file_get_contents($this::API_URL);
        if (!($this->checkResponse($response))) {
            return false;
        }
        $this->responseClass = $this->decodeResponse($response);
        if ($this->removeOldAlerts) {
            $this->removeOldAlertsFromDb();
        }
        $this->handleResponse();
        return true;
    }
    
    /**
     * Decodes the response string
     * You cannot use json_decode for that only, because dwd adds JavaScript code into
     * the json file...
     *
     * @param string $response
     * @return string
     */
    protected function decodeResponse($response)
    {
        $pattern = '/^warnWetter.loadWarnings\(|\)\;$/';
        return json_decode(preg_replace($pattern, '', $response));
    }
    
    /**
     * Checks the responseClass for alerts in selected regions
     *
     * @return mixed
     */
    protected function handleResponse()
    {
        $this->responseTimestamp = (int)$this->responseClass->time;
        if (is_object($this->responseClass->warnings)) {
            foreach ($this->responseClass->warnings as $alertArray) {
                if (is_array($alertArray)) {
                    /** @var \stdClass $alertClass */
                    foreach ($alertArray as $alertClass) {
                        if ($regionUid = $this->uidOfRegionName($alertClass->regionName) !== false) {
                            if (!$this->isIdenticalAlertExisting(
                                $alertClass->start,
                                $alertClass->end,
                                $regionUid,
                                $alertClass->level,
                                $alertClass->type
                            )
                            ) {
                                $this->dbConnection->exec_INSERTquery(
                                    $this->dbExtTable,
                                    $this->mapArrayForDatabase($alertClass, $regionUid)
                                );
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Returns true if identical alert already exists
     * otherwise false
     *
     * @param int $start
     * @param int $end
     * @param int $regionUid
     * @param int $level
     * @param int $type
     * @return bool
     */
    protected function isIdenticalAlertExisting($start, $end, $regionUid, $level, $type)
    {
        $identicalAlerts = $this->dbConnection->exec_SELECTgetSingleRow(
            'uid',
            $this->dbExtTable,
            'starttime = ' . (int)$start / 1000 . ' AND endtime = "' .
            (int)$end / 1000 . '" AND regions = "' . (int)$regionUid .
            '" AND level = "' . (int)$level . '" AND type = "' . (int)$type . '"'
        );
        if ($identicalAlerts !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Returns the uid of the region if $regionName is
     * in $this->regionSelection otherwise returns false
     *
     * @param string $regionName
     * @return int|bool
     */
    protected function uidOfRegionName($regionName)
    {
        foreach ($this->selectedRegions as $regionUid) {
            /** @var WeatherAlertRegion $region */
            $region = $this->weatherAlertRepository->findByUid($regionUid);
            if ($region instanceof WeatherAlertRegion) {
                if (strpos(trim($regionName), $region->getName()) !== false) {
                    return $region->getUid();
                }
            }
        }
        return false;
    }
    
    /**
     * Returns the TYPO3 database connection from globals
     *
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
    
    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string
     */
    public function getAdditionalInformation()
    {
        return parent::getAdditionalInformation();
    }
    
    /**
     * Checks the JSON response
     *
     * @param string $response
     * @return bool Returns true if response is valid or false in case of an error
     */
    private function checkResponse($response)
    {
        if ($response === false) {
            $this->writeToLog(WeatherUtility::translate('message.api_response_null', 'deutscherwetterdienst'), 2);
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Writes a string into the TYPO3 syslog
     *
     * @param string $message Message that will be written into the log
     * @param int $errorLevel error level of log entry (look into BackendUserAuthentication > simplelog comment
     * to get more details about errorLevels)
     * @return void
     */
    protected function writeToLog($message, $errorLevel = 0)
    {
        $this->getBackendUserAuthentication()->simplelog(trim($message), 'weather2', (int)$errorLevel);
    }
    
    /**
     * Returns the BackendUserAuthentication
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication()
    {
        return $GLOBALS['BE_USER'];
    }
    
    /**
     * Returns mapped array
     * This function must be adjusted for different APIs
     * $mappingArray = array(
     *     'pid' => $this->recordStoragePage,
     *     'regions' => 0,
     *     'level' => 0,
     *     'type' => 0,
     *     'title' => '',
     *     'description' => '',
     *     'instruction' => '',
     *     'response_timestamp' => $this->responseTimestamp,
     *     'starttime' => 0,
     *     'endtime' => 0,
     *  );
     *
     * @param \stdClass $alertClass
     * @param int $regionUid
     * @return array mapped array
     */
    private function mapArrayForDatabase($alertClass, $regionUid)
    {
        // initialize all items with a default value
        $mappingArray = array(
            'pid' => $this->recordStoragePage,
            'regions' => 0,
            'level' => 0,
            'type' => 0,
            'title' => '',
            'description' => '',
            'instruction' => '',
            'response_timestamp' => $this->responseTimestamp,
            'starttime' => 0,
            'endtime' => 0,
        );
        
        if (isset($regionUid)) {
            $mappingArray['regions'] = (int)$regionUid;
        }
        
        if (isset($alertClass->level)) {
            $mappingArray['level'] = (int)$alertClass->level;
        }
        
        if (isset($alertClass->type)) {
            $mappingArray['type'] = (int)$alertClass->type;
        }
        
        if (isset($alertClass->headline)) {
            $mappingArray['title'] = trim($alertClass->headline);
        }
        
        if (isset($alertClass->description)) {
            $mappingArray['description'] = trim($alertClass->description);
        }
        
        if (isset($alertClass->instruction)) {
            $mappingArray['instruction'] = trim($alertClass->instruction);
        }
        
        if (isset($alertClass->start)) {
            $mappingArray['starttime'] = (int)$alertClass->start / 1000;
        }
        
        if (isset($alertClass->end)) {
            $mappingArray['endtime'] = (int)$alertClass->end / 1000;
        }
        
        return $mappingArray;
    }
    
    /**
     * Removes old alerts from db and uses $this->removeOldAlertsHours as time indicator
     *
     * @return void
     */
    protected function removeOldAlertsFromDb()
    {
        $minimalTimestamp = time() + ((int)$this->removeOldAlertsHours * 3600);
        $this->dbConnection->exec_DELETEquery($this->dbExtTable, 'endtime <= ' . $minimalTimestamp);
    }
}