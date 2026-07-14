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

readonly class FlexFormUserFunc
{
    protected const TABLE = 'tx_weather2_domain_model_currentweather';

    public function __construct(
        private ConnectionPool $connectionPool,
    ) {}

    /**
     * Only display results if the name equals in the plugin-specified name
     *
     * @param array<string, mixed> $fConfig
     */
    public function getSelection(array &$fConfig): void
    {
        $connection = $this->connectionPool->getConnectionForTable(self::TABLE);

        $result = $connection
            ->select(
                ['name'],
                self::TABLE,
            )
            ->fetchAllAssociative();

        foreach ($result as $data) {
            array_unshift($fConfig['items'], [$data['name'], $data['name']]);
        }
    }
}
