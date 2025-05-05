<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Controller;

use JWeiland\Weather2\Controller\CurrentWeatherController;
use JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case.
 */
class CurrentWeatherControllerTest extends UnitTestCase
{
    #[Test]
    public function showActionCallsRepositoryFindBySelectionWithSettingAsArgument(): void
    {
        $subject = $this->getAccessibleMock(CurrentWeatherController::class);
        $currentWeatherRepository = $this->getMockBuilder(CurrentWeatherRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $subject->_set('currentWeatherRepository', $currentWeatherRepository);
        $subject->_set('settings', ['selection' => 'testSelection']);
        $subject->showAction();
    }
}
