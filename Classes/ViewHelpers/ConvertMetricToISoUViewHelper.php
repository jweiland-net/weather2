<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\ViewHelpers;

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use JWeiland\Weather2\Service\WeatherConverterService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ConvertMetricToISoUViewHelper
 */
class ConvertMetricToISoUViewHelper extends AbstractViewHelper
{
    /**
     * Initialize Arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('as', 'string', 'Holds converted Weather data', true);
    }

    /**
     * Returns converted WeatherModel
     *
     * @param CurrentWeather $weatherModel
     * @return string
     */
    public function render(CurrentWeather $weatherModel): string
    {
        $convertedModel = clone $weatherModel;
        /** @var $converter WeatherConverterService */
        $converter = GeneralUtility::makeInstance(WeatherConverterService::class);

        // Set Values Here
        $convertedModel->setTemperatureC((int)$converter->convertCelsiusToKelvin($weatherModel->getTemperatureC()));
        $convertedModel->setMinTempC((int)$converter->convertCelsiusToKelvin($weatherModel->getMinTempC()));
        $convertedModel->setMaxTempC((int)$converter->convertCelsiusToKelvin($weatherModel->getMaxTempC()));

        $this->templateVariableContainer->add($this->arguments['as'], $convertedModel);
        $content = $this->renderChildren();
        $this->templateVariableContainer->remove($this->arguments['as']);

        return $content;
    }
}
