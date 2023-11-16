<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Domain\Repository;

use JWeiland\Weather2\Domain\Model\WeatherAlert;
use JWeiland\Weather2\Domain\Repository\WeatherAlertRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class WeatherAlertRepositoryTest extends FunctionalTestCase
{
    /**
     * @var WeatherAlertRepository
     */
    protected $subject;

    protected array $testExtensionsToLoad = [
        'jweiland/weather2'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_weather2_domain_model_weatheralert.csv');

        $this->subject = GeneralUtility::makeInstance(WeatherAlertRepository::class);
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
    public function findByUserSelectionWillReturnEmptyResult(): void
    {
        self::assertCount(
            0,
            $this->subject->findByUserSelection(
                '108416000',
                '1',
                '2',
                false
            )
        );
    }

    /**
     * @test
     */
    public function findByUserSelectionWillReturnWeatherAlert(): void
    {
        $weatherAlerts = $this->subject->findByUserSelection(
            '908236999,108111000',
            '1',
            '2',
            false
        );

        self::assertCount(
            1,
            $weatherAlerts
        );

        /** @var WeatherAlert $firstWeatherAlert */
        $firstWeatherAlert = $weatherAlerts->getFirst();
        self::assertSame(
            'Amtliche WARNUNG vor WINDBÖEN',
            $firstWeatherAlert->getTitle()
        );
        self::assertStringContainsString(
            'Geschwindigkeiten bis 55 km/h',
            $firstWeatherAlert->getDescription()
        );
        self::assertNull(
            $firstWeatherAlert->getEndDate()
        );
    }
}
