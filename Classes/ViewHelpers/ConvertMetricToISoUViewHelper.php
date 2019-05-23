<?php
namespace JWeiland\Weather2\ViewHelpers;

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
    public function initializeArguments()
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
