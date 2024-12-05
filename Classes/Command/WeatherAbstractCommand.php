<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Service\CacheService;

/**
 * WeatherAbstractCommand Class for Command
 */
abstract class WeatherAbstractCommand extends Command
{
    public function getDwdWarnCellRepository(): DwdWarnCellRepository
    {
        return GeneralUtility::makeInstance(DwdWarnCellRepository::class);
    }

    public function getPersistenceManager(): PersistenceManager
    {
        return GeneralUtility::makeInstance(PersistenceManager::class);
    }

    public function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function getRequestFactory(): RequestFactory
    {
        return GeneralUtility::makeInstance(RequestFactory::class);
    }

    public function getCacheService(): CacheService
    {
        return GeneralUtility::makeInstance(CacheService::class);
    }

    public function getDataHandler(): DataHandler
    {
        return GeneralUtility::makeInstance(DataHandler::class);
    }

    public function getContextHandler(): Context
    {
        return GeneralUtility::makeInstance(Context::class);
    }

    public function getMailMessageHandler(): MailMessage
    {
        return GeneralUtility::makeInstance(MailMessage::class);
    }
}
