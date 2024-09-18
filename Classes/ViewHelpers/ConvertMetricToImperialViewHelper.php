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
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ConvertMetricToImperialViewHelper
 */
class ConvertMetricToImperialViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'as',
            'string',
            'Holds converted Weather data',
            false,
            'convertedData',
        );
        $this->registerArgument(
            'weatherModel',
            CurrentWeather::class,
            'Current Weather Object',
            true,
        );
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext,
    ): string {
        /** @var CurrentWeather $currentWeather */
        $weatherModel = $arguments['weatherModel'];
        $convertedModel = clone $weatherModel;

        /** @var $converter WeatherConverterService */
        $converter = GeneralUtility::makeInstance(WeatherConverterService::class);
        $convertedModel->setTemperatureC((int)$converter->convertCelsiusToFahrenheit($weatherModel->getTemperatureC()));
        $convertedModel->setMinTempC((int)$converter->convertCelsiusToFahrenheit($weatherModel->getMinTempC()));
        $convertedModel->setMaxTempC((int)$converter->convertCelsiusToFahrenheit($weatherModel->getMaxTempC()));
        $convertedModel->setWindSpeedMPS((int)$converter->convertMetersToMiles($weatherModel->getWindSpeedMPS()));

        $renderingContext->getVariableProvider()->add($arguments['as'], $convertedModel);
        $content = $renderChildrenClosure();
        $renderingContext->getVariableProvider()->remove($arguments['as']);

        return $content;
    }
}
