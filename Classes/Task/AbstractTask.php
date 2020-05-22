<?php
declare(strict_types=1);
namespace JWeiland\Weather2\Task;

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

use Psr\Log\LoggerInterface;

/**
 * Abstract task class that adds TYPO3 8 compatibility
 */
abstract class AbstractTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * SF: getLogger was removed with TYPO3 9.5.
     * @ToDo: Please remove that method and use $this->logger instead when removing TYPO3 8.7 compatibility
     *
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        if (method_exists(\TYPO3\CMS\Scheduler\Task\AbstractTask::class, 'getLogger')) {
            // Fallback for TYPO3 8.7
            return parent::getLogger();
        }

        return $this->logger;
    }
}
