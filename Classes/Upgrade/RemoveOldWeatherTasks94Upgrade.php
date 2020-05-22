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
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * With TYPO3 8.7.33 the logger-property in our Scheduler Task can not be unserialized anymore for security reasons.
 * Without unserialization it is not possible for us to update the tasks, so currenty the only option
 * is to delete our tasks.
 */

class RemoveOldWeatherTasks94Upgrade implements UpgradeWizardInterface
{
    /**
     * @var RemoveOldWeatherTasksUpgrade
     */
    protected $flexFormUpdate;

    public function __construct(RemoveOldWeatherTasksUpgrade $flexFormUpdate = null)
    {
        if ($flexFormUpdate === null) {
            $flexFormUpdate = GeneralUtility::makeInstance(RemoveOldWeatherTasksUpgrade::class);
        }
        $this->flexFormUpdate = $flexFormUpdate;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->flexFormUpdate->getIdentifier();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->flexFormUpdate->getTitle();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->flexFormUpdate->getDescription();
    }

    /**
     * @return bool
     */
    public function updateNecessary(): bool
    {
        return $this->flexFormUpdate->updateNecessary();
    }

    /**
     * @return bool
     */
    public function executeUpdate(): bool
    {
        return $this->flexFormUpdate->executeUpdate();
    }

    /**
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }
}
