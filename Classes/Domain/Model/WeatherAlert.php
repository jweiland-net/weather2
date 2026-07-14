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
    protected ?DwdWarnCell $dwdWarnCell = null;

    /**
     * Level of alert
     * There is a detailed description in the documentation for this
     * e.g. yellow, red, ...
     */
    protected int $level = 0;

    /**
     * Type of alert
     * There is a detailed description in the documentation for this
     * e.g. frost, storm, ...
     */
    protected int $type = 0;

    /**
     * Alert title
     */
    protected string $title = '';

    /**
     * Alert description
     */
    protected string $description = '';

    /**
     * Alert Instruction
     * e.g. Stay at home, close your windows, etc.
     */
    protected string $instruction = '';

    /**
     * TYPO3 starttime
     */
    protected ?\DateTime $starttime = null;

    /**
     * TYPO3 endtime
     */
    protected ?\DateTime $endtime = null;

    /**
     * Start date of the weather alert!
     */
    protected ?\DateTime $startDate = null;

    /**
     * End date of the weather alert!
     */
    protected ?\DateTime $endDate = null;

    protected string $comparisonHash = '';

    protected bool $preliminaryInformation = false;

    public function getDwdWarnCell(): ?DwdWarnCell
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
