<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Service;

use JWeiland\Weather2\Domain\Repository\WeatherAlertRepositoryInterface;
use JWeiland\Weather2\Fetcher\WeatherAlertFetcherInterface;
use JWeiland\Weather2\Parser\WeatherAlertParserInterface;
use JWeiland\Weather2\Service\DeutscherWetterdienstAlertService;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Extbase\Service\CacheService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[\AllowDynamicProperties] class DeutscherWetterdienstAlertServiceTest extends UnitTestCase
{
    private DeutscherWetterdienstAlertService $service;
    private MockObject $input;
    private MockObject $output;

    protected function setUp(): void
    {
        parent::setUp();

        // Mocking external dependencies
        $fetcher = $this->createMock(WeatherAlertFetcherInterface::class);
        $parser = $this->createMock(WeatherAlertParserInterface::class);
        $repository = $this->createMock(WeatherAlertRepositoryInterface::class);
        $cacheService = $this->createMock(CacheService::class);
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);

        // Create instance of the service to be tested
        $this->service = new DeutscherWetterdienstAlertService(
            $fetcher,
            $parser,
            $repository,
            $cacheService,
        );
    }

    public function testFetchAndStoreAlerts(): void
    {
        $inputMock = $this->createMock(InputInterface::class);
        $outputMock = $this->createMock(OutputInterface::class);

        $inputMock->expects(self::exactly(3))
            ->method('getArgument')
            ->willReturnOnConsecutiveCalls(
                '1,2,3',
                'warnCellId1, warnCellId2',
                '42',
            );

        // Mock the fetcher and parser services
        $this->fetcherMock = $this->createMock(WeatherAlertFetcherInterface::class);
        $this->fetcherMock->expects(self::once())
            ->method('fetchData')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->parserMock = $this->createMock(WeatherAlertParserInterface::class);
        $this->parserMock->expects(self::once())
            ->method('parse')
            ->willReturn(['warnings' => []]);

        // Mock the repository and cache service
        $this->cacheServiceMock = $this->createMock(CacheService::class);
        $this->cacheServiceMock->expects(self::once())
            ->method('clearPageCache')
            ->with([42]);

        $this->repositoryMock = $this->createMock(WeatherAlertRepositoryInterface::class);

        // Instantiate the service
        $service = new DeutscherWetterdienstAlertService(
            $this->fetcherMock,
            $this->parserMock,
            $this->repositoryMock,
            $this->cacheServiceMock,
        );

        // Call the method you're testing
        $service->fetchAndStoreAlerts($inputMock, $outputMock);
    }
}
