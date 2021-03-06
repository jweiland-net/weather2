<?php

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Controller;

use JWeiland\Weather2\Controller\CurrentWeatherController;
use JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Test case for class JWeiland\Weather2\Controller\CurrentWeatherController.
 *
 * @author Markus Kugler <projects@jweiland.net>
 * @author Pascal Rinker <projects@jweiland.net>
 */
class CurrentWeatherControllerTest extends UnitTestCase
{
    /**
     * @var \JWeiland\Weather2\Controller\CurrentWeatherController
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            CurrentWeatherController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
    }

    public function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function showActionCallsRepositoryFindBySelectionWithSettingAsArgument()
    {
        $currentWeather = new \JWeiland\Weather2\Domain\Model\CurrentWeather();

        $currentWeatherRepository = $this->getAccessibleMock(
            CurrentWeatherRepository::class,
            ['findBySelection'],
            [],
            '',
            false
        );
        $this->inject($this->subject, 'currentWeatherRepository', $currentWeatherRepository);

        $view = $this->getAccessibleMock(
            TemplateView::class,
            ['assign'],
            [],
            '',
            false
        );
        $this->inject($this->subject, 'view', $view);

        $this->subject->_set('settings', ['selection' => 'testSelection']);
        $currentWeatherRepository->expects(self::once())->method('findBySelection')->with('testSelection');
        $this->subject->showAction();
    }
}
