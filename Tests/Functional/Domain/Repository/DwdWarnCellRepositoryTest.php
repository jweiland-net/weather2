<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Domain\Repository;

use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case.
 */
class DwdWarnCellRepositoryTest extends FunctionalTestCase
{
    /**
     * @var DwdWarnCellRepository
     */
    protected $subject;

    protected array $testExtensionsToLoad = ['typo3conf/ext/weather2'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_weather2_domain_model_dwdwarncell.csv');

        $this->subject = GeneralUtility::makeInstance(DwdWarnCellRepository::class);
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject,
        );

        parent::tearDown();
    }

    /**
     * @test
     */
    public function findBySelectionWillReturnEmptyArray(): void
    {
        self::assertCount(
            0,
            $this->subject->findByName('Lindlar')
        );
    }

    /**
     * @test
     */
    public function findByNameWillReturnDwdWarnCellsByFullName(): void
    {
        $dwdWarnCells = $this->subject->findByName('Kreis Tübingen');

        self::assertCount(
            1,
            $dwdWarnCells
        );

        self::assertSame(
            'BW',
            $dwdWarnCells[0]->getSign()
        );

        self::assertSame(
            '108416000',
            $dwdWarnCells[0]->getWarnCellId()
        );
    }

    /**
     * @test
     */
    public function findByNameWillReturnDwdWarnCellsByPartName(): void
    {
        $dwdWarnCells = $this->subject->findByName('Stadt');

        self::assertCount(
            2,
            $dwdWarnCells
        );

        self::assertSame(
            '908236999',
            $dwdWarnCells[0]->getWarnCellId()
        );

        self::assertSame(
            '108111000',
            $dwdWarnCells[1]->getWarnCellId()
        );
    }

    /**
     * @test
     */
    public function findByNameWillReturnDwdWarnCellsByWarnCellId(): void
    {
        $dwdWarnCells = $this->subject->findByName('108416000');

        self::assertCount(
            1,
            $dwdWarnCells
        );

        self::assertSame(
            'Kreis Tübingen',
            $dwdWarnCells[0]->getName()
        );
    }
}
