<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Dashboard\Widget;

use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class WeatherAlertWidget implements WidgetInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(private readonly WidgetConfigurationInterface $configuration, private readonly ViewFactoryInterface $viewFactory, private readonly array $options) {}

    public function renderWidgetContent(): string
    {
        $view = $this->getView();
        $view->assignMultiple([
            'items' => [],
            'configuration' => $this->configuration,
            'options' => $this->getOptions(),
        ]);

        return $view->render();
    }

    private function getView(): ViewInterface
    {
        // $this->view->setTemplate('Widget/Connections');
        return $this->viewFactory->create(new ViewFactoryData(
            templateRootPaths: ['EXT:weather2/Resources/Private/Templates/Widget/'],
        ));
    }

    /**
     * @return mixed[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
