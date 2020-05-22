<?php
declare(strict_types=1);
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

use JWeiland\Weather2\Domain\Model\DwdWarnCell;
use JWeiland\Weather2\Domain\Model\WeatherAlert;
use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * DeutscherWetterdienstTask Class for Scheduler
 */
class DeutscherWetterdienstTask extends AbstractTask
{
    const API_URL = 'https://www.dwd.de/DWD/warnungen/warnapp/json/warnings.json';

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $dbExtTable = 'tx_weather2_domain_model_weatheralert';

    /**
     * @var string
     */
    protected $execTime = '';

    /**
     * JSON response from dwd api
     *
     * @var array
     */
    protected $decodedResponse = [];

    /**
     * Fetch only these warn cells
     *
     * @var array
     */
    public $selectedWarnCells = [];

    /**
     * @var int
     */
    public $recordStoragePage = 0;

    /**
     * @var array
     */
    protected $keepRecords = [];

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var array
     */
    protected $warnCellRecords = [];

    /**
     * @var DwdWarnCellRepository
     */
    protected $dwdWarnCellRepository;

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->dwdWarnCellRepository = $this->objectManager->get(DwdWarnCellRepository::class);
        $response = GeneralUtility::makeInstance(RequestFactory::class)->request(self::API_URL);
        if (!$this->checkResponse($response)) {
            return false;
        }
        try {
            $this->decodedResponse = $this->decodeResponse($response);
        } catch (\Exception $e) {
            $this->getLogger()->log(LogLevel::ERROR, $e->getMessage());
            return false;
        }
        $this->handleResponse();
        return true;
    }

    /**
     * Decodes the response string
     * You cannot use json_decode for that only, because dwd adds JavaScript code into
     * the json file...
     *
     * @param ResponseInterface $response
     * @return array
     * @throws \UnexpectedValueException
     */
    protected function decodeResponse(ResponseInterface $response): array
    {
        $pattern = '/^warnWetter\.loadWarnings\(|\);$/';
        $decodedResponse = json_decode(preg_replace($pattern, '', (string)$response->getBody()), true);
        if ($decodedResponse === null) {
            throw new \UnexpectedValueException(
                'Response can not be decoded because it is an invalid string',
                1485944083
            );
        }
        return $decodedResponse;
    }

    /**
     * Checks the responseClass for alerts in selected regions
     */
    protected function handleResponse()
    {
        $this->persistenceManager = $this->objectManager->get(PersistenceManager::class);
        if (array_key_exists('warnings', $this->decodedResponse)) {
            $this->processDwdItems($this->decodedResponse['warnings'], false);
        }
        if (array_key_exists('vorabInformation', $this->decodedResponse)) {
            $this->processDwdItems($this->decodedResponse['vorabInformation'], true);
        }
        $this->removeOldAlertsFromDb();
        $this->persistenceManager->persistAll();
    }

    /**
     * @param array $category
     * @param bool $isPreliminaryInformation
     */
    protected function processDwdItems(array $category, bool $isPreliminaryInformation)
    {
        foreach ($this->selectedWarnCells as $warnCellId) {
            if (array_key_exists($warnCellId, $category) && is_array($category[$warnCellId])) {
                foreach ($category[$warnCellId] as $alert) {
                    if ($alertUid = $this->getUidOfAlert($alert)) {
                        // alert does already exist as record
                        $this->keepRecords[] = $alertUid;
                    } else {
                        // create a new alert record
                        $this->persistenceManager->add($this->getWeatherAlertInstanceForAlert($alert, $warnCellId, $isPreliminaryInformation));
                    }
                }
            }
        }
    }

    /**
     * @param array $alert
     * @return string
     */
    protected function getComparisonHashForAlert(array $alert): string
    {
        return md5(serialize($alert));
    }

    /**
     * Either returns the uid of a record that equals $alert
     * OR returns zero if there is no record for that $alert
     *
     * @param array $alert
     * @return int uid or zero
     */
    protected function getUidOfAlert(array $alert): int
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->dbExtTable);
        $identicalAlert = $connection
            ->select(
                ['uid'],
                $this->dbExtTable,
                [
                    'comparison_hash' => $this->getComparisonHashForAlert($alert),
                    'pid' => $this->recordStoragePage
                ]
            )
            ->fetch();
        return $identicalAlert['uid'] ?? 0;
    }

    /**
     * @param ResponseInterface $response
     * @return bool Returns true if response is valid or false in case of an error
     */
    protected function checkResponse(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200 || (string)$response->getBody() === '') {
            $this->getLogger()->log(
                LogLevel::ERROR,
                WeatherUtility::translate('message.api_response_null', 'deutscherwetterdienst')
            );
            return false;
        }
        return true;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Returns filled WeatherAlert instance
     *
     * @param array $alert
     * @param string $warnCellId
     * @param bool $isPreliminaryInformation
     * @return WeatherAlert
     */
    protected function getWeatherAlertInstanceForAlert(array $alert, string $warnCellId, bool $isPreliminaryInformation): WeatherAlert
    {
        $weatherAlert = new WeatherAlert();
        $weatherAlert->setPid($this->recordStoragePage);
        $weatherAlert->setDwdWarnCell($this->getDwdWarnCell($warnCellId));
        $weatherAlert->setComparisonHash($this->getComparisonHashForAlert($alert));
        $weatherAlert->setPreliminaryInformation($isPreliminaryInformation);

        if (isset($alert['level'])) {
            $weatherAlert->setLevel($alert['level']);
        }
        if (isset($alert['type'])) {
            $weatherAlert->setType($alert['type']);
        }
        if (isset($alert['headline'])) {
            $weatherAlert->setTitle($alert['headline']);
        }
        if (isset($alert['description'])) {
            $weatherAlert->setDescription($alert['description']);
        }
        if (isset($alert['instruction'])) {
            $weatherAlert->setInstruction($alert['instruction']);
        }
        if (isset($alert['start'])) {
            $startTime = new \DateTime();
            $startTime->setTimestamp((int)substr((string)$alert['start'], 0, -3));
            $weatherAlert->setStartDate($startTime);
        }
        if (isset($alert['end'])) {
            $endTime = new \DateTime();
            $endTime->setTimestamp((int)substr((string)$alert['end'], 0, -3));
            $weatherAlert->setEndDate($endTime);
        }

        return $weatherAlert;
    }

    /**
     * @param string $warnCellId
     * @return DwdWarnCell
     */
    protected function getDwdWarnCell(string $warnCellId): DwdWarnCell
    {
        if (!array_key_exists($warnCellId, $this->warnCellRecords)) {
            $this->warnCellRecords[$warnCellId] = $this->dwdWarnCellRepository->findOneByWarnCellId($warnCellId);
        }
        return $this->warnCellRecords[$warnCellId];
    }

    protected function removeOldAlertsFromDb()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->dbExtTable);
        $queryBuilder
            ->delete($this->dbExtTable);
        if ($this->keepRecords) {
            $queryBuilder->where($queryBuilder->expr()->notIn('uid', $this->keepRecords));
        }
        $queryBuilder->execute();
    }
}
