<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\UserFunc;

use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * TCA userFunc stuff
 */
class Tca
{
    /**
     * label_userFunc for tx_weather2_domain_model_dwdwarncell
     *
     * @param array<string, mixed> $parameters
     */
    public function getDwdWarnCellTitle(array &$parameters): void
    {
        // Extract variables for better readability
        $tableName = $parameters['table'];
        $recordUid = $parameters['row']['uid'] ?? null;

        // Handle missing uid or record gracefully
        if (!$recordUid) {
            $parameters['title'] = '(No UID provided)';
            return;
        }

        // Fetch the record
        $record = BackendUtility::getRecord($tableName, $recordUid);

        // Generate title with fallback values
        $parameters['title'] = sprintf(
            '%s (%s)',
            $parameters['row']['name'] ?? '(No Name)',
            $record['warn_cell_id'] ?? '(No Warn Cell ID)',
        );
    }
}
