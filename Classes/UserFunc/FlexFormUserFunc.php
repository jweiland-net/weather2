<?php
declare(strict_types = 1);
namespace JWeiland\Weather2\UserFunc;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FlexFormUserFunc
 */
class FlexFormUserFunc
{
    /**
     * Only display results if name equals in plugin specified name
     *
     * @param array $fConfig
     */
    public function getSelection(&$fConfig)
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(
            'tx_weather2_domain_model_currentweather'
        );
        $result = $connection->select(['name'], 'tx_weather2_domain_model_currentweather')->fetchAll();

        foreach ($result as $data) {
            array_unshift($fConfig['items'], [$data['name'], $data['name']]);
        }
    }
}
