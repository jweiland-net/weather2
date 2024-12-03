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
use PHPUnit\Framework\MockObject\Exception;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class WeatherAlertRepositoryTest extends FunctionalTestCase
{
    protected WeatherAlertRepository $subject;

    protected array $testExtensionsToLoad = [
        'jweiland/weather2',
    ];

    protected ServerRequestInterface $request;

    /**
     * @var ConfigurationManager|MockObject
     */
    protected MockObject $configurationManagerMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = GeneralUtility::makeInstance(WeatherAlertRepository::class);
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/pages.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_weather2_domain_model_weatheralert.csv');

        $this->createFrontendControllerMock();
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
                false,
            ),
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
            false,
        );

        self::assertCount(
            1,
            $weatherAlerts,
        );

        /** @var WeatherAlert $firstWeatherAlert */
        $firstWeatherAlert = $weatherAlerts->getFirst();
        self::assertSame(
            'Amtliche WARNUNG vor WINDBÖEN',
            $firstWeatherAlert->getTitle(),
        );
        self::assertStringContainsString(
            'Geschwindigkeiten bis 55 km/h',
            $firstWeatherAlert->getDescription(),
        );
        self::assertNull(
            $firstWeatherAlert->getEndDate(),
        );
    }

    /**
     * Create a TypoScriptFrontendController mock instance.
     *
     * @throws Exception
     */
    protected function createFrontendControllerMock(array $config = []): void
    {
        $controllerMock = $this->createMock(TypoScriptFrontendController::class);
        $controllerMock->cObj = new ContentObjectRenderer($controllerMock);
        $controllerMock->cObj->data = [
            'uid' => 1,
            'pid' => 0,
            'title' => 'Startpage',
            'nav_title' => 'Car',
        ];

        // Set the configuration
        $configProperty = new \ReflectionProperty($controllerMock, 'config');
        $configProperty->setAccessible(true);
        ArrayUtility::mergeRecursiveWithOverrule($controllerMock->config, $config);

        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupArray([]);

        $controllerMock->config = $config;

        $this->request = (new ServerRequest())
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('frontend.controller', $controllerMock)
            ->withAttribute('frontend.typoscript', $frontendTypoScript);
    }
}
