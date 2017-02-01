<?php
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
     * Regions affected to this alert
     * e.g. 0 (single)
     * e.g. 0,2,7,3 (multiple)
     *
     * @var string
     */
    protected $regions = '';
    
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
     * Start time of the alert
     *
     * @var \DateTime
     */
    protected $starttime;
    
    /**
     * End time of the alert
     *
     * @var \DateTime
     */
    protected $endtime;
    
    /**
     * Returns Regions
     *
     * @return string
     */
    public function getRegions()
    {
        return $this->regions;
    }
    
    /**
     * Sets Regions
     *
     * @param string $regions
     */
    public function setRegions($regions)
    {
        $this->regions = (string)$regions;
    }
    
    /**
     * Returns Level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * Sets Level
     *
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = (int)$level;
    }
    
    /**
     * Returns Type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Sets Type
     *
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = (int)$type;
    }
    
    /**
     * Returns Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets Title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }
    
    /**
     * Returns Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets Description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }
    
    /**
     * Returns Instruction
     *
     * @return string
     */
    public function getInstruction()
    {
        return $this->instruction;
    }
    
    /**
     * Sets Instruction
     *
     * @param string $instruction
     */
    public function setInstruction($instruction)
    {
        $this->instruction = (string)$instruction;
    }
    
    /**
     * Returns Starttime
     *
     * @return \DateTime
     */
    public function getStarttime()
    {
        return $this->starttime;
    }
    
    /**
     * Sets Starttime
     *
     * @param \DateTime $starttime
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
    }
    
    /**
     * Returns Endtime
     *
     * @return \DateTime
     */
    public function getEndtime()
    {
        return $this->endtime;
    }
    
    /**
     * Sets Endtime
     *
     * @param \DateTime $endtime
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;
    }
}