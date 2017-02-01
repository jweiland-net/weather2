<?php
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

use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * FlexFormUserFunc
 */
class FlexFormUserFunc
{
    /**
     * Only display results if name equals in plugin specified name
     *
     * @param array $fConfig
     * @return void
     */
    public function getSelection(&$fConfig) {
        $dbConnection = $this->getDatabaseConnection();

        $result = $dbConnection->exec_SELECTgetRows(
            'name',
            'tx_weather2_domain_model_currentweather',
            '1',
            'name',
            'name'
        );

        // add empty to enable using latest entry in db
        array_push($result, array('name' => ''));

        foreach ($result as $data) {
            array_unshift($fConfig['items'], array($data['name'], $data['name']));
        }
    }

    /**
     * Get the DatabaseConnection from globals
     *
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}