<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Command;

use JWeiland\Weather2\Command\OpenWeatherMapCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Extbase\Service\CacheService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class OpenWeatherMapCommandTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/weather2'];

    private LoggerInterface $logger;
    private RequestFactory $requestFactory;
    private CacheService $cacheService;
    private ConnectionPool $connectionPool;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks for dependencies
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->requestFactory = $this->createMock(RequestFactory::class);
        $this->cacheService = $this->createMock(CacheService::class);
        $this->connectionPool = $this->createMock(ConnectionPool::class);

        // Instantiate the command
        $command = new OpenWeatherMapCommand(
            $this->logger,
            $this->requestFactory,
            $this->cacheService,
            $this->connectionPool,
        );

        // Set up the CommandTester for the command
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up resources (if needed)
        unset(
            $this->logger,
            $this->requestFactory,
            $this->cacheService,
            $this->connectionPool,
            $this->commandTester,
        );
    }

    public function testCommandExecutesSuccessfully(): void
    {
        // Mock the response
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn(json_encode([
            'main' => [
                'temp' => 22.5,
                'pressure' => 1013,
                'humidity' => 75,
            ],
            'weather' => [
                ['id' => 800, 'icon' => '01d'],
            ],
            'wind' => ['speed' => 3.5],
            'clouds' => ['all' => 10],
            'dt' => 1632421600,
        ]));
        $this->requestFactory->method('request')->willReturn($mockResponse);

        // Execute the command
        $this->commandTester->execute([
            'name' => 'TestWeatherStation',
            'city' => 'Munich',
            'country' => 'DE',
            'apiKey' => 'fakeapikey',
        ]);

        // Assert output and status
        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('Open Weather Map data successfully updated!', $output);
        self::assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testCommandFailsOnApiError(): void
    {
        // Mock an unauthorized response
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(401);
        $this->requestFactory->method('request')->willReturn($mockResponse);

        // Execute the command
        $this->commandTester->execute([
            'name' => 'TestWeatherStation',
            'city' => 'Munich',
            'country' => 'DE',
            'apiKey' => 'fakeapikey',
        ]);

        // Assert output and status
        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('Exception while fetching data from API', $output);
        self::assertEquals(1, $this->commandTester->getStatusCode());
    }
}
