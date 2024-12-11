<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Fetcher;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

class WarnCellFetcher implements WarnCellFetcherInterface
{
    private const API_URL = 'https://www.dwd.de/DE/leistungen/opendata/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=3';

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly LoggerInterface $logger,
    ) {}

    public function fetchData(): ResponseInterface
    {
        $response = $this->requestFactory->request(self::API_URL);

        if ($response->getStatusCode() !== 200 || (string)$response->getBody() === '') {
            $this->logger->error('Invalid response from API.');
            throw new \RuntimeException('Invalid response from API.');
        }

        return $response;
    }
}
