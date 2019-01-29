<?php
namespace JWeiland\Weather2\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use JWeiland\Weather2\Domain\Repository\WeatherAlertRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * WeatherAlertController
 */
class WeatherAlertController extends ActionController
{
    /**
     * currentWeatherRepository
     *
     * @var \JWeiland\Weather2\Domain\Repository\WeatherAlertRepository
     */
    protected $weatherAlertRepository = null;

    /**
     * inject weatherAlertRepository
     *
     * @param WeatherAlertRepository $weatherAlertRepository
     * @return void
     */
    public function injectWeatherAlertRepository(WeatherAlertRepository $weatherAlertRepository)
    {
        $this->weatherAlertRepository = $weatherAlertRepository;
    }

    /**
     * action show displays the newest CurrentWeather model
     *
     * @return void
     */
    public function showAction()
    {
        $this->view->assign(
            'weatherAlerts',
            $this->weatherAlertRepository->findByRegions(
                $this->settings['regions'],
                $this->settings['warningTypes'],
                $this->settings['warningLevels']
            )
        );
    }
}
