<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeutscherWetterdienstWarnCellCommand extends WeatherAbstractCommand
{
    public const API_URL = 'https://www.dwd.de/DE/leistungen/opendata/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=3';

    protected function configure(): void
    {
        $this->setHelp('Calls the Deutscher Wetterdienst api and saves warn cells into database. Required before using DeutscherWetterdienstTask!');
    }
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->getRequestFactory()->request($this::API_URL);
        if (!$this->checkResponse($response)) {
            return Command::FAILURE;
        }
        $this->processResponse($response);
        return Command::SUCCESS;
    }

    protected function processResponse(ResponseInterface $response): void
    {
        $connection = $this->getConnectionPool()
            ->getConnectionForTable('tx_weather2_domain_model_dwdwarncell');

        $rawRows = explode(PHP_EOL, (string)$response->getBody());
        // remove header
        array_shift($rawRows);

        $data = [];
        $i = 0;
        foreach ($rawRows as $rawRow) {
            if ($rawRow === '') {
                continue;
            }

            [$warnCellId, $name, $nuts, $shortName, $sign] = str_getcsv($rawRow, ';');
            // check if a record for this id already exists
            if ($connection->count('uid', 'tx_weather2_domain_model_dwdwarncell', ['warn_cell_id' => $warnCellId]) === 0) {
                $data['tx_weather2_domain_model_dwdwarncell']['NEW' . $i++] = [
                    'pid' => 0,
                    'warn_cell_id' => $warnCellId,
                    'name' => $name,
                    'short_name' => $shortName,
                    'sign' => $sign,
                ];
            }
        }

        $dataHandler = $this->getDataHandler();
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
                WeatherUtility::translate('message.api_response_null', 'deutscherwetterdienst'),
            );

            return false;
        }

        return true;
    }
}
