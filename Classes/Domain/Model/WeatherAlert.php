<?php
declare(strict_types=1);
namespace JWeiland\Weather2\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * WeatherAlert
 */
class WeatherAlert extends AbstractEntity
{
    /**
     * @var \JWeiland\Weather2\Domain\Model\DwdWarnCell
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

    /**
     * @return DwdWarnCell
     */
    public function getDwdWarnCell(): DwdWarnCell
    {
        return $this->dwdWarnCell;
    }

    /**
     * @param DwdWarnCell $dwdWarnCell
     */
    public function setDwdWarnCell(DwdWarnCell $dwdWarnCell)
    {
        $this->dwdWarnCell = $dwdWarnCell;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level)
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns Instruction
     *
     * @return string
     */
    public function getInstruction(): string
    {
        return $this->instruction;
    }

    /**
     * @param string $instruction
     */
    public function setInstruction(string $instruction)
    {
        $this->instruction = $instruction;
    }

    /**
     * @return \DateTime|null
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * @param \DateTime $starttime
     */
    public function setStarttime(\DateTime $starttime)
    {
        $this->starttime = $starttime;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * @param \DateTime $endtime
     */
    public function setEndtime(\DateTime $endtime)
    {
        $this->endtime = $endtime;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getComparisonHash(): string
    {
        return $this->comparisonHash;
    }

    /**
     * @param string $comparisonHash
     */
    public function setComparisonHash(string $comparisonHash)
    {
        $this->comparisonHash = $comparisonHash;
    }

    /**
     * Fluid getter:
     * {weatherAlert.isPreliminaryInformation}
     *
     * @return bool
     */
    public function getIsPreliminaryInformation(): bool
    {
        return $this->isPreliminaryInformation();
    }

    /**
     * @return bool
     */
    public function isPreliminaryInformation(): bool
    {
        return $this->preliminaryInformation;
    }

    /**
     * @param bool $preliminaryInformation
     */
    public function setPreliminaryInformation(bool $preliminaryInformation)
    {
        $this->preliminaryInformation = $preliminaryInformation;
    }
}
