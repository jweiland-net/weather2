<?php
declare(strict_types = 1);
namespace JWeiland\Weather2\Upgrade;

/*
 * This file is part of the weather2 project.
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Controller\Action\Tool\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * With TYPO3 8.7.33 the logger-property in our Scheduler Task can not be unserialized anymore for security reasons.
 * This UpgradeWizard fetches all weather2 tasks, unserializes the Task without the Logger, set it to NULL and
 * serializes it back to DB.
 */
class RemoveLoggerFromSerializedTask87Upgrade extends AbstractUpdate
{
    /**
     * @var RemoveLoggerFromSerializedTaskUpgrade
     */
    protected $removeTasksUpgrade;

    public function __construct(
        string $identifier,
        int $versionAsInt,
        string $userInput = null,
        UpgradeWizard $parentObject = null,
        RemoveLoggerFromSerializedTaskUpgrade $removeTasksUpgrade = null
    ) {
        if ($removeTasksUpgrade === null) {
            $removeTasksUpgrade = GeneralUtility::makeInstance(RemoveLoggerFromSerializedTaskUpgrade::class);
        }
        $this->removeTasksUpgrade = $removeTasksUpgrade;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->removeTasksUpgrade->getIdentifier();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->removeTasksUpgrade->getTitle();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->removeTasksUpgrade->getDescription();
    }

    /**
     * Checks whether updates are required.
     *
     * @param string &$description The description for the update
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function checkForUpdate(&$description): bool
    {
        $description = $this->getDescription();
        return $this->removeTasksUpgrade->updateNecessary();
    }

    /**
     * Performs the accordant updates.
     *
     * @param array &$dbQueries Queries done in this update
     * @param string &$customMessage Custom message
     * @return bool Whether everything went smoothly or not
     */
    public function performUpdate(array &$dbQueries, &$customMessage): bool
    {
        return $this->removeTasksUpgrade->executeUpdate();
    }
}
