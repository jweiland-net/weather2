<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

use Doctrine\DBAL\Exception;

interface WeatherAlertRepositoryInterface
{
    /**
     * @param array<string, mixed> $weatherAlertInfo
     */
    public function insertAlertRecord(array $weatherAlertInfo): int;

    public function getUidOfAlert(int $recordStoragePid, string $comparisonHash): int;

    /**
     * @return array<int, mixed>
     * @throws Exception
     */
    public function getDwdAlertsFindByName(string $alertName): array;

    /**
     * @param array<int, mixed> $keepRecords
     */
    public function removeOldAlertsFromDb(array $keepRecords): int;
}
