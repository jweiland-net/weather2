<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Domain\Repository;

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case.
 */
class CurrentWeatherRepositoryTest extends FunctionalTestCase
{
    /**
     * @var CurrentWeatherRepository
     */
    protected $subject;

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

        $this->importDataSet(__DIR__ . '/../../Fixtures/tx_weather2_domain_model_currentweather.xml');

        $this->subject = GeneralUtility::makeInstance(CurrentWeatherRepository::class);
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject,
        );

        parent::tearDown();
    }

    /**
     * @test
     */
    public function findBySelectionWillReturnNull(): void
    {
        self::assertNull(
            $this->subject->findBySelection('Lindlar')
        );
    }

    /**
     * @test
     */
    public function findBySelectionWillReturnCurrentWeatherObject(): void
    {
        $currentWeatherObject = $this->subject->findBySelection('Stuttgart');

        self::assertInstanceOf(
            CurrentWeather::class,
            $currentWeatherObject
        );

        self::assertSame(
            24.32,
            $currentWeatherObject->getTemperatureC()
        );

        self::assertSame(
            1.2,
            $currentWeatherObject->getRainVolume()
        );
    }
}
