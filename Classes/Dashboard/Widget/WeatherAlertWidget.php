<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Dashboard\Widget;

use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class WeatherAlertWidget implements WidgetInterface
{
    private WidgetConfigurationInterface $configuration;

    private StandaloneView $view;

    /**
     * @var array<string, mixed>
     */
    private array $options = [];

    /**
     * @param WidgetConfigurationInterface $configuration
     * @param StandaloneView $view
     * @param array<string, mixed> $options
     */
    public function __construct(
        WidgetConfigurationInterface $configuration,
        StandaloneView $view,
        array $options,
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
        $this->options = $options;
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/Connections');
        $this->view->assignMultiple([
            'items' => [],
            'configuration' => $this->configuration,
            'options' => $this->getOptions(),
        ]);

        return $this->view->render();
    }

    /**
     * @return mixed[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
