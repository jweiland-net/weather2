<?php
declare(strict_types=1);
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
    public function showAction()
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
