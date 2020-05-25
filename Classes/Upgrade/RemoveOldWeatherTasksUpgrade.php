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

use JWeiland\Weather2\Task\AbstractTask;
use JWeiland\Weather2\Task\DeutscherWetterdienstTask;
use JWeiland\Weather2\Task\DeutscherWetterdienstWarnCellTask;
use JWeiland\Weather2\Task\OpenWeatherMapTask;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Execution;

/**
 * With TYPO3 8.7.33 the logger-property in our Scheduler Task can not be unserialized anymore for security reasons.
 * Without unserialization it is not possible for us to update the tasks, so currenty the only option
 * is to delete our tasks.
 */
class RemoveOldWeatherTasksUpgrade
{
    /**
     * Return the identifier for this wizard
     * This should be the same string as used in the ext_localconf class registration
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'weather2RemoveTasks';
    }

    /**
     * Return the speaking name of this wizard
     *
     * @return string
     */
    public function getTitle(): string
    {
        return '[weather2] Remove weather2 scheduler tasks';
    }

    /**
     * Return the description for this wizard
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'For TYPO3 security reasons all tasks of weather2 have to be removed from DB. Please check, if ' .
            'you still have your API Key available and you know the previously configured StoragePage';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        return !empty($this->getWeather2SchedulerTasks());
    }

    /**
     * Performs the accordant updates.
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {
        $records = $this->getWeather2SchedulerTasks();
        foreach ($records as $record) {
            // Re-Build Task, but without logger-property
            $task = unserialize(
                $record['serialized_task_object'],
                [
                    'allowed_classes' => [
                        Execution::class,
                        DeutscherWetterdienstTask::class,
                        DeutscherWetterdienstWarnCellTask::class,
                        OpenWeatherMapTask::class
                    ]
                ]
            );
            if ($task instanceof AbstractTask) {
                $task->resetLogger();
                $connection = $this->getConnectionPool()->getConnectionForTable('tx_scheduler_task');
                $connection->update(
                    'tx_scheduler_task',
                    [
                        'serialized_task_object' => serialize($task)
                    ],
                    [
                        'uid' => (int)$record['uid']
                    ]
                );
            }
        }

        return true;
    }

    /**
     * Get all scheduler Tasks of weather2
     *
     * @return array
     */
    protected function getWeather2SchedulerTasks(): array
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tx_scheduler_task');
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder
            ->select('uid', 'serialized_task_object')
            ->from('tx_scheduler_task')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like(
                        'serialized_task_object',
                        $queryBuilder->createNamedParameter('%OpenWeatherMapTask%', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->like(
                        'serialized_task_object',
                        $queryBuilder->createNamedParameter('%DeutscherWetterdienstTask%', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->like(
                        'serialized_task_object',
                        $queryBuilder->createNamedParameter('%DeutscherWetterdienstWarnCellTask%', \PDO::PARAM_STR)
                    )
                ),
                $queryBuilder->expr()->like(
                    'serialized_task_object',
                    $queryBuilder->createNamedParameter('%logger"%', \PDO::PARAM_STR)
                )
            )
            ->execute();

        $tasks = [];
        while ($task = $statement->fetch()) {
            $tasks[] = $task;
        }

        return $tasks;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
