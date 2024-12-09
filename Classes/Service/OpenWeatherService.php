<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Service;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

final class OpenWeatherService implements WeatherServiceInterface
{
    private const API_URL = 'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s';

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly LoggerInterface $logger
    ) {}

    public function fetchWeatherData(string $city, string $country, string $apiKey): ResponseInterface
    {
        $url = sprintf(self::API_URL, urlencode($city), urlencode($country), 'metric', $apiKey);

        $this->logger->info('Requesting data from OpenWeatherMap API', ['url' => $url]);

        return $this->requestFactory->request($url);
    }
}
