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

use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * DeutscherWetterdienstRegionsTask Class for Scheduler
 */
class DeutscherWetterdienstRegionsTask extends AbstractTask
{
    /**
     * Source for alerts
     */
    const API_URL = 'https://www.dwd.de/DWD/warnungen/warnapp_landkreise/viewer/gemeinden.js';
    
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
    protected $dbExtTable = 'tx_weather2_domain_model_weatheralertregion';
    
    /**
     * JSON response from dwd api
     *
     * @var \stdClass
     */
    protected $responseClass = null;
    
    /**
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     *
     * @return bool
     */
    public function execute()
    {
        $this->writeToLog('Executed with this settings: ' . json_encode($this), 0);
        $this->dbConnection = $this->getDatabaseConnection();
        $response = @file_get_contents($this::API_URL);
        if (!($this->checkResponse($response))) {
            return false;
        }
        $this->responseClass = $this->decodeResponse($response);
        $this->handleResponse();
        return true;
    }
    
    /**
     * Decodes the response string
     * You cannot use json_decode for that only
     *
     * @param string $response
     * @return string
     */
    protected function decodeResponse($response)
    {
        $pattern = '/^var gemeinden = /';
        return json_decode(preg_replace($pattern, '', $response));
    }
    
    /**
     * Checks the responseClass for alerts in selected regions
     *
     * @return mixed
     */
    protected function handleResponse()
    {
        if (is_array($this->responseClass)) {
            /** @var \stdClass $region */
            foreach ($this->responseClass as $region) {
                $name = trim($region->name);
                if (isset($region->lk)) {
                    $district = trim($region->lk);
                } else {
                    $district = '';
                }
                // check if this region already exists
                $result = $this->dbConnection->exec_SELECTgetSingleRow(
                    'uid',
                    $this->dbExtTable,
                    'name = "' . $name . '" AND district = "' . $district . '"'
                );
                if ($result === false) {
                    $this->dbConnection->exec_INSERTquery(
                        $this->dbExtTable,
                        array('name' => $name, 'district' => $district)
                    );
                }
            }
        }
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
        $GLOBALS['BE_USER']->simplelog(trim($message), 'weather2', (int)$errorLevel);
    }
}