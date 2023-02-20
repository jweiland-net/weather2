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
use JWeiland\Weather2\Domain\Model\CurrentWeather;
use JWeiland\Weather2\Task\OpenWeatherMapTask;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

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
     * @var ResponseInterface|ObjectProphecy
     */
    protected $responseProphecy;

    /**
     * @var RequestFactory|ObjectProphecy
     */
    protected $requestFactoryProphecy;

    /**
     * @var PersistenceManagerInterface|ObjectProphecy
     */
    protected $persistenceManagerProphecy;

    /**
     * @var OpenWeatherMapTask
     */
    protected $subject;

    /**
     * @var string[]
     */
    protected $coreExtensionsToLoad = [
        'scheduler',
    ];

    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/weather2',
        'typo3conf/ext/static_info_tables',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);

        $this->stream = new Stream('php://temp', 'rw');

        $this->responseProphecy = $this->prophesize(Response::class);
        $this->responseProphecy
            ->getBody()
            ->shouldBeCalled()
            ->willReturn($this->stream);

        $this->requestFactoryProphecy = $this->prophesize(RequestFactory::class);
        $this->requestFactoryProphecy
            ->request(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($this->responseProphecy->reveal());

        GeneralUtility::addInstance(RequestFactory::class, $this->requestFactoryProphecy->reveal());

        $this->persistenceManagerProphecy = $this->prophesize(PersistenceManager::class);
        $this->persistenceManagerProphecy
            ->persistAll()
            ->shouldBeCalled();

        /** @var ObjectManagerInterface|ObjectProphecy $objectManagerProphecy */
        $objectManagerProphecy = $this->prophesize(ObjectManager::class);
        $objectManagerProphecy
            ->get(PersistenceManager::class)
            ->willReturn($this->persistenceManagerProphecy->reveal());

        GeneralUtility::setSingletonInstance(ObjectManager::class, $objectManagerProphecy->reveal());

        // We have to use GM:makeInstance because of LoggerAwareInterface
        $this->subject = GeneralUtility::makeInstance(OpenWeatherMapTask::class);
        $this->subject->city = 'Filderstadt';
        $this->subject->apiKey = 'IHaveForgottenToAddOne';
        $this->subject->clearCache = '';
        $this->subject->country = 'Germany';
        $this->subject->recordStoragePage = 1;
        $this->subject->name = 'Filderstadt';
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject,
            $this->persistenceManagerProphecy,
            $this->requestFactoryProphecy,
            $this->responseProphecy,
            $this->stream
        );

        parent::tearDown();
    }

    /**
     * @test
     */
    public function execute(): void
    {
        $this->stream->write(json_encode([
            'cod' => true,
            'dt' => time(),
            'main' => [
                'temp' => 14.6,
                'pressure' => 8,
                'humidity' => 12,
                'temp_min' => 13.2,
                'temp_max' => 16.4,
            ],
            'wind' => [
                'speed' => 3.7,
                'deg' => 25,
            ],
            'snow' => [
                '1h' => 4,
                '3h' => 11,
            ],
            'rain' => [
                '1h' => 6,
                '3h' => 15,
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
        ]));

        $this->responseProphecy
            ->getStatusCode()
            ->shouldBeCalled()
            ->willReturn(200);

        $this->persistenceManagerProphecy
            ->add(Argument::that(static function (CurrentWeather $currentWeather) {
                return $currentWeather->getName() === 'Filderstadt'
                    && $currentWeather->getMeasureTimestamp() instanceof \DateTime
                    && $currentWeather->getTemperatureC() === 14.6
                    && $currentWeather->getPressureHpa() === 8
                    && $currentWeather->getHumidityPercentage() === 12
                    && $currentWeather->getMinTempC() === 13.2
                    && $currentWeather->getMaxTempC() === 16.4
                    && $currentWeather->getWindSpeedMPS() === 3.7
                    && $currentWeather->getWindDirectionDeg() === 25
                    && $currentWeather->getSnowVolume() === 4
                    && $currentWeather->getRainVolume() === 6
                    && $currentWeather->getCloudsPercentage() === 11
                    && $currentWeather->getIcon() === '[ICON]'
                    && $currentWeather->getConditionCode() === 1256;
            }))
            ->shouldBeCalled();

        self::assertTrue(
            $this->subject->execute()
        );
    }
}
