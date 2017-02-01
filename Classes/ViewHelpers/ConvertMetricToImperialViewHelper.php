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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ConvertMetricToImperialViewHelper
 */
class ConvertMetricToImperialViewHelper extends AbstractViewHelper
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
     * @param \JWeiland\Weather2\Domain\Model\CurrentWeather $weatherModel
     * @return string
     */
    public function render(CurrentWeather $weatherModel)
    {
        $convertedModel = clone $weatherModel;
        /** @var $converter WeatherConverterService */
        $converter = GeneralUtility::makeInstance('JWeiland\\Weather2\\Service\\WeatherConverterService');
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        // Without this making changes in partials get annoying
        // TODO: Now you are left with two arrays of witch one is redundant

        // Set Values Here
        $convertedModel->setTemperatureC($converter->convertCelsiusToFahrenheit($weatherModel->getTemperatureC()));
        $convertedModel->setMinTempC($converter->convertCelsiusToFahrenheit($weatherModel->getMinTempC()));
        $convertedModel->setMaxTempC($converter->convertCelsiusToFahrenheit($weatherModel->getMaxTempC()));
        $convertedModel->setWindSpeedMPS($converter->convertMetersToMiles($weatherModel->getWindSpeedMPS()));

        $templateVariableContainer->add($this->arguments['as'], $convertedModel);
        $content = $this->renderChildren();
        $templateVariableContainer->remove($this->arguments['as']);

        return $content;
    }
}