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
class CurrentWeatherControllerTest extends FunctionalTestCase
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
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_weather2_domain_model_currentweather-controller.csv');

        $this->setUpFrontendSite(1);
        $this->setUpFrontendRootPage(1, ['EXT:weather2/Tests/Functional/Fixtures/TypoScript/setup.typoscript']);
    }

    #[Test]
    public function showActionWillRenderCurrentWeatherOfSelectedCity(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_content-currentweather-stuttgart.csv');

        $response = $this->executeFrontendSubRequest(
            (new InternalRequest())->withPageId(1),
        );

        self::assertSame(200, $response->getStatusCode());

        $content = (string)$response->getBody();

        self::assertStringContainsString(
            'Rain Volume',
            $content,
        );
        self::assertStringContainsString(
            '1.2 mm',
            $content,
        );
    }

    #[Test]
    public function showActionWithSelectionWithoutMatchingRecordWillNotRenderWeatherData(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_content-currentweather-unknown-selection.csv');

        $response = $this->executeFrontendSubRequest(
            (new InternalRequest())->withPageId(1),
        );

        self::assertSame(200, $response->getStatusCode());

        $content = (string)$response->getBody();

        self::assertStringNotContainsString(
            'weather2-report',
            $content,
        );
    }
}
