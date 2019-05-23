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

    public function __construct()
    {
        parent::__construct();
        $this->initializeLogger();
    }

    public function __wakeup()
    {
        if (method_exists(\TYPO3\CMS\Scheduler\Task\AbstractTask::class, '__wakeup')) {
            parent::__wakeup();
        }
        $this->initializeLogger();
    }

    protected function initializeLogger()
    {
        // $this->logger has been added with TYPO3 9
        if ($this->logger === null) {
            $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
        }
    }

    /**
     * @return bool
     */
    abstract public function execute(): bool;
}
