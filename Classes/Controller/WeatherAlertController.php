<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Controller;

use JWeiland\Weather2\Domain\Repository\WeatherAlertRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * WeatherAlertController
 */
class WeatherAlertController extends ActionController
{
    /**
     * @var WeatherAlertRepository
     */
    protected $weatherAlertRepository;

    /**
     * @param WeatherAlertRepository $weatherAlertRepository
     */
    public function injectWeatherAlertRepository(WeatherAlertRepository $weatherAlertRepository)
    {
        $this->weatherAlertRepository = $weatherAlertRepository;
    }

    /**
     * action show displays the newest CurrentWeather model
     */
    public function showAction(): void
    {
        $this->view->assign(
            'weatherAlerts',
            $this->weatherAlertRepository->findByUserSelection(
                (string)$this->settings['warnCells'],
                (string)$this->settings['warningTypes'],
                (string)$this->settings['warningLevels'],
                (bool)$this->settings['showPreliminaryInformation']
            )
        );
    }
}
