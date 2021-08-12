<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Task;

use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Fetch warn cells
 */
class DeutscherWetterdienstWarnCellTask extends AbstractTask
{
    const API_URL = 'https://www.dwd.de/DE/leistungen/opendata/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=3';

    /**
     * @var string
     */
    protected $dbExtTable = 'tx_weather2_domain_model_weatheralertregion';

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $response = GeneralUtility::makeInstance(RequestFactory::class)->request($this::API_URL);
        if (!$this->checkResponse($response)) {
            return false;
        }
        $this->processResponse($response);
        return true;
    }

    protected function processResponse(ResponseInterface $response): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_weather2_domain_model_dwdwarncell');
        $rawRows = explode(PHP_EOL, (string)$response->getBody());
        // remove header
        array_shift($rawRows);

        $data = [];
        $i = 0;
        foreach ($rawRows as $rawRow) {
            [$warnCellId, $name, $shortName, $sign] = str_getcsv($rawRow, ';');
            // check if a record for this id already exists
            if ($connection->count('uid', 'tx_weather2_domain_model_dwdwarncell', ['warn_cell_id' => $warnCellId]) === 0) {
                $data['tx_weather2_domain_model_dwdwarncell']['NEW' . $i++] = [
                    'pid' => 0,
                    'warn_cell_id' => $warnCellId,
                    'name' => $name,
                    'short_name' => $shortName,
                    'sign' => $sign
                ];
            }
        }

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($data, []);
        $dataHandler->process_datamap();
    }

    /**
     * @param ResponseInterface $response
     * @return bool Returns true if response is valid or false in case of an error
     */
    private function checkResponse(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200 || (string)$response->getBody() === '') {
            $this->logger->log(
                LogLevel::ERROR,
                WeatherUtility::translate('message.api_response_null', 'deutscherwetterdienst')
            );
            return false;
        }
        return true;
    }
}
