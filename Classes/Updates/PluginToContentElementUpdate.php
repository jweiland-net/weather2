<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Updates;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;

/**
 * With TYPO3 13 all plugins have to be declared as content elements (CType) insteadof "list_type"
 */
#[UpgradeWizard(
    identifier: 'weather2_migratePluginsToContentElementsUpdate',
)]
class PluginToContentElementUpdate extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return '[weather2] Migrate plugins to Content Elements';
    }

    public function getDescription(): string
    {
        return 'The modern way to register plugins for TYPO3 is to register them as content element types. '
            . 'Running this wizard will migrate all weather2 plugins to content element (CType)';
    }

    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'weather2_currentweather' => 'weather2_currentweather',
            'weather2_weatheralert' => 'weather2_weatheralert',
        ];
    }
}
