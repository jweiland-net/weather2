<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Task;

use GuzzleHttp\Psr7\Response;
use JWeiland\Weather2\Task\OpenWeatherMapTask;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class OpenWeatherMapTaskTest extends FunctionalTestCase
{
    /**
     * @var Stream
     */
    protected $stream;

    /**
     * @var ResponseInterface|MockObject
     */
    protected $responseMock;

    /**
     * @var RequestFactory|MockObject
     */
    protected $requestFactoryMock;

    /**
     * @var OpenWeatherMapTask
     */
    protected $subject;

    protected array $coreExtensionsToLoad = [
        'scheduler',
    ];

    protected array $testExtensionsToLoad = [
        'jweiland/weather2',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->stream = new Stream('php://temp', 'rw');

        $this->responseMock = $this->createMock(Response::class);
        $this->responseMock
            ->expects(self::atLeastOnce())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->requestFactoryMock = $this->createMock(RequestFactory::class);
        $this->requestFactoryMock
            ->expects(self::once())
            ->method('request')
            ->with(self::isType('string'))
            ->willReturn($this->responseMock);

        GeneralUtility::addInstance(RequestFactory::class, $this->requestFactoryMock);

        // We have to use GM:makeInstance because of LoggerAwareInterface
        $this->subject = $this->getAccessibleMock(OpenWeatherMapTask::class, null, [], '', false);
        $this->subject->city = 'Filderstadt';
        $this->subject->apiKey = 'IHaveForgottenToAddOne';
        $this->subject->clearCache = '';
        $this->subject->country = 'Germany';
        $this->subject->recordStoragePage = 1;
        $this->subject->name = 'Filderstadt';

        $loggerMock = $this->createMock(Logger::class);
        $this->subject->setLogger($loggerMock);
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject,
            $this->requestFactoryMock,
            $this->responseMock,
            $this->stream,
        );

        parent::tearDown();
    }

    /**
     * @test
     * @throws \JsonException
     */
    public function execute(): void
    {
        $time = time();
        $this->stream->write(
            json_encode([
                'cod' => true,
                'dt' => $time,
                'main' => [
                    'temp' => 14.6,
                    'pressure' => 8.2,
                    'humidity' => 12,
                    'temp_min' => 13.2,
                    'temp_max' => 16.4,
                ],
                'wind' => [
                    'speed' => 3.7,
                    'deg' => 25,
                ],
                'snow' => [
                    '1h' => 4.0,
                    '3h' => 11.0,
                ],
                'rain' => [
                    '1h' => 6.0,
                    '3h' => 15.0,
                ],
                'clouds' => [
                    'all' => 11,
                ],
                'weather' => [
                    0 => [
                        'id' => 1256,
                        'main' => 'rain',
                        'icon' => '[ICON]',
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
        );

        $this->responseMock
            ->expects(self::atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(200);

        self::assertTrue(
            $this->subject->execute(),
        );

        $row = $this->getConnectionPool()
            ->getConnectionForTable('tx_weather2_domain_model_currentweather')
            ->select(['humidity_percentage', 'measure_timestamp'], 'tx_weather2_domain_model_currentweather')
            ->fetchAssociative();

        $expected = [
            'humidity_percentage' => 12,
            'measure_timestamp' => $time,
        ];

        self::assertSame($expected, $row);
    }
}
