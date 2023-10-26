<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * WeatherAlert
 */
class WeatherAlert extends AbstractEntity
{
    /**
     * @var DwdWarnCell
     */
    protected $dwdWarnCell;

    /**
     * Level of alert
     * There is a detailed description in the documentation for this
     * e.g. yellow, red, ...
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Type of alert
     * There is a detailed description in the documentation for this
     * e.g. frost, storm, ...
     *
     * @var int
     */
    protected $type = 0;

    /**
     * Alert title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Alert description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Alert Instruction
     * e.g. Stay at home, close your windows, etc.
     *
     * @var string
     */
    protected $instruction = '';

    /**
     * TYPO3 starttime
     *
     * @var \DateTime
     */
    protected $starttime;

    /**
     * TYPO3 endtime
     *
     * @var \DateTime
     */
    protected $endtime;

    /**
     * Start date of the weather alert!
     *
     * @var \DateTime
     */
    protected $startDate;

    /**
     * End date of the weather alert!
     *
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @var string
     */
    protected $comparisonHash = '';

    /**
     * @var bool
     */
    protected $preliminaryInformation = false;

    public function getDwdWarnCell(): DwdWarnCell
    {
        return $this->dwdWarnCell;
    }

    public function setDwdWarnCell(DwdWarnCell $dwdWarnCell): void
    {
        $this->dwdWarnCell = $dwdWarnCell;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getInstruction(): string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): void
    {
        $this->instruction = $instruction;
    }

    public function getStarttime(): ?\DateTime
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTime $starttime): void
    {
        $this->starttime = $starttime;
    }

    public function getEndtime(): ?\DateTime
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTime $endtime): void
    {
        $this->endtime = $endtime;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getComparisonHash(): string
    {
        return $this->comparisonHash;
    }

    public function setComparisonHash(string $comparisonHash): void
    {
        $this->comparisonHash = $comparisonHash;
    }

    /**
     * Fluid getter:
     * {weatherAlert.isPreliminaryInformation}
     */
    public function getIsPreliminaryInformation(): bool
    {
        return $this->isPreliminaryInformation();
    }

    public function isPreliminaryInformation(): bool
    {
        return $this->preliminaryInformation;
    }

    public function setPreliminaryInformation(bool $preliminaryInformation): void
    {
        $this->preliminaryInformation = $preliminaryInformation;
    }
}
