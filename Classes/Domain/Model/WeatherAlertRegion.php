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
 * WeatherAlertRegion
 */
class WeatherAlertRegion extends AbstractEntity
{
    /**
     * Region name
     * e.g. Stuttgart
     *
     * @var string
     */
    protected $name = '';
    
    /**
     * District of this region
     * e.g. Kreis Uckermark if city is Pinnow
     *
     * @var string
     */
    protected $district = '';
    
    /**
     * Returns Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets Name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }
    
    /**
     * Returns District
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }
    
    /**
     * Sets District
     *
     * @param string $district
     */
    public function setDistrict($district)
    {
        $this->district = (string)$district;
    }
}