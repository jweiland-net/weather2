<?php
declare(strict_types=1);
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

/**
 * TCA userFunc stuff
 */
class Tca
{
    /**
     * label_userFunc for tx_weather2_domain_model_dwdwarncell
     *
     * @param array $parameters
     */
    public function getDwdWarnCellTitle(array &$parameters)
    {
        $parameters['title'] = sprintf('%s (%s)', $parameters['row']['name'], $parameters['row']['warn_cell_id']);
    }
}
