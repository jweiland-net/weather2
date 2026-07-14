<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Backend\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Add plugin preview for EXT:weather2
 */
class Weather2PluginPreview extends StandardContentPreviewRenderer
{
    private const PREVIEW_TEMPLATE = 'EXT:weather2/Resources/Private/Templates/PluginPreview/Weather2.html';

    private const ALLOWED_PLUGINS = [
        'weather2_currentweather',
        'weather2_weatheralert',
    ];

    public function __construct(
        protected FlexFormTools $flexFormTools,
        protected ViewFactoryInterface $viewFactory,
    ) {
        parent::__construct();
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $ttContentRecord = $item->getRecord();
        if (!$this->isValidPlugin($ttContentRecord)) {
            return '';
        }

        $view = $this->viewFactory->create(
            new ViewFactoryData(
                templatePathAndFilename: self::PREVIEW_TEMPLATE,
            ),
        );
        $view->assignMultiple($ttContentRecord->toArray());

        $this->addPluginName($view, $ttContentRecord);

        // Add data from column pi_flexform
        $piFlexformData = $this->getPiFlexFormData($ttContentRecord);
        if ($piFlexformData !== []) {
            $view->assign('pi_flexform_transformed', $piFlexformData);
        }

        return $view->render();
    }

    protected function isValidPlugin(RecordInterface $ttContentRecord): bool
    {
        if (!$ttContentRecord->has('CType')) {
            return false;
        }
        return in_array($ttContentRecord->get('CType'), self::ALLOWED_PLUGINS, true);
    }

    protected function addPluginName(ViewInterface $view, RecordInterface $ttContentRecord): void
    {
        $langKey = sprintf(
            'plugin.%s.title',
            str_replace('weather2_', '', $ttContentRecord->get('CType')),
        );

        $view->assign(
            'pluginName',
            LocalizationUtility::translate('LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:' . $langKey),
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getPiFlexFormData(RecordInterface $ttContentRecord): array
    {
        if ($ttContentRecord->has('pi_flexform') && $ttContentRecord->get('pi_flexform') !== '') {
            return $this->flexFormTools->convertFlexFormContentToArray($ttContentRecord->get('pi_flexform'));
        }
        return [];
    }
}
