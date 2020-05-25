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
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract task class that adds TYPO3 8 compatibility
 */
abstract class AbstractTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Will be called from UpgradeWizard to set Logger back to NULL before serializing it into DB
     */
    public function resetLogger()
    {
        $this->logger = null;
    }

    /**
     * @ToDo: SF: Remove this method when TYPO3 8.7 compatibility was thrown away
     *
     * Sets the internal reference to the singleton instance of the Scheduler
     */
    public function setScheduler()
    {
        $this->scheduler = GeneralUtility::makeInstance(\TYPO3\CMS\Scheduler\Scheduler::class);
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }

    /**
     * @ToDo: SF: Remove this method when TYPO3 8.7 compatibility was thrown away
     *
     * Unsets the internal reference to the singleton instance of the Scheduler
     * This is done before a task is serialized, so that the scheduler instance
     * is not saved to the database too
     */
    public function unsetScheduler()
    {
        $this->scheduler = null;
        $this->logger = null;
    }
}
