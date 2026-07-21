<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Controller;

use JWeiland\Weather2\Tests\Functional\Traits\SetUpFrontendSiteTrait;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class WeatherAlertControllerTest extends FunctionalTestCase
{
    use SetUpFrontendSiteTrait;

    protected array $coreExtensionsToLoad = [
        'typo3/cms-dashboard',
        'typo3/cms-install',
        'typo3/cms-scheduler',
    ];

    protected array $testExtensionsToLoad = [
        'jweiland/weather2',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/pages-controller.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_weather2_domain_model_weatheralert.csv');

        $this->setUpFrontendSite(1);
        $this->setUpFrontendRootPage(1, ['EXT:weather2/Tests/Functional/Fixtures/TypoScript/setup.typoscript']);
    }

    #[Test]
    public function showActionWillRenderWeatherAlertMatchingUserSelection(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_content-weatheralert-matching.csv');

        $response = $this->executeFrontendSubRequest(
            (new InternalRequest())->withPageId(1),
        );

        self::assertSame(200, $response->getStatusCode());

        $content = (string)$response->getBody();

        self::assertStringContainsString(
            'Amtliche WARNUNG vor WINDBÖEN',
            $content,
        );
        self::assertStringContainsString(
            'Geschwindigkeiten bis 55 km/h',
            $content,
        );
    }

    #[Test]
    public function showActionWithUserSelectionNotMatchingAlertWillNotRenderIt(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_content-weatheralert-non-matching.csv');

        $response = $this->executeFrontendSubRequest(
            (new InternalRequest())->withPageId(1),
        );

        self::assertSame(200, $response->getStatusCode());

        $content = (string)$response->getBody();

        self::assertStringNotContainsString(
            'Amtliche WARNUNG vor WINDBÖEN',
            $content,
        );
    }
}
