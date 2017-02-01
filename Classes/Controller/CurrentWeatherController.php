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

use JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * CurrentWeatherController
 */
class CurrentWeatherController extends ActionController
{
    /**
     * currentWeatherRepository
     *
     * @var \JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository
     */
    protected $currentWeatherRepository;
    
    /**
     * inject currentWeatherRepository
     *
     * @param CurrentWeatherRepository $currentWeatherRepository
     * @return void
     */
    public function injectCurrentWeatherRepository(CurrentWeatherRepository $currentWeatherRepository)
    {
        $this->currentWeatherRepository = $currentWeatherRepository;
    }

    /**
     * action show displays the newest CurrentWeather model
     *
     * @return void
     */
    public function showAction()
    {
        $currentWeather = $this->currentWeatherRepository->findBySelection($this->settings['selection']);
        $this->view->assign('currentWeather', $currentWeather);
    }
}