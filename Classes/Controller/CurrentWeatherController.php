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

    public function injectCurrentWeatherRepository(CurrentWeatherRepository $currentWeatherRepository): void
    {
        $this->currentWeatherRepository = $currentWeatherRepository;
    }

    /**
     * Action to display the newest CurrentWeather model
     */
    public function showAction(): void
    {
        $this->view->assign(
            'currentWeather',
            $this->currentWeatherRepository->findBySelection(
                $this->settings['selection'] ?? ''
            )
        );
    }
}
