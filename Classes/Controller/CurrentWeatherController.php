<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Controller;

use JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * CurrentWeatherController
 */
class CurrentWeatherController extends ActionController
{
    /**
     * @var CurrentWeatherRepository
     */
    protected $currentWeatherRepository;

    public function __construct(CurrentWeatherRepository $currentWeatherRepository)
    {
        if (is_callable('parent::__construct')) {
            parent::__construct();
        }
        $this->currentWeatherRepository = $currentWeatherRepository;
    }

    /**
     * action show displays the newest CurrentWeather model
     */
    public function showAction(): void
    {
        $currentWeather = $this->currentWeatherRepository->findBySelection($this->settings['selection']);
        $this->view->assign('currentWeather', $currentWeather);
    }
}
