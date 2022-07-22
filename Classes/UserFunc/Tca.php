<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\UserFunc;

/**
 * TCA userFunc stuff
 */
class Tca
{
    /**
     * label_userFunc for tx_weather2_domain_model_dwdwarncell
     */
    public function getDwdWarnCellTitle(array &$parameters): void
    {
        $parameters['title'] = sprintf(
            '%s (%s)',
            $parameters['row']['name'] ?? '',
            $parameters['row']['warn_cell_id'] ?? ''
        );
    }
}
