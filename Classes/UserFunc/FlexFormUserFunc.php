<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\UserFunc;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FlexFormUserFunc
 */
class FlexFormUserFunc
{
    /**
     * Only display results if name equals in plugin specified name
     */
    public function getSelection(array &$fConfig): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(
            'tx_weather2_domain_model_currentweather',
        );
        $result = $connection->select(['name'], 'tx_weather2_domain_model_currentweather')->fetchAll();

        foreach ($result as $data) {
            array_unshift($fConfig['items'], [$data['name'], $data['name']]);
        }
    }
}
