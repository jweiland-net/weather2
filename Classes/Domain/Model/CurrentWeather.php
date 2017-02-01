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
 * CurrentWeather
 */
class CurrentWeather extends AbstractEntity
{
    /**
     * name
     *
     * @var string
     */
    protected $name = '';
    
    /**
     * measureTimestamp
     *
     * @var \DateTime
     */
    protected $measureTimestamp;
    
    /**
     * temperatureC
     *
     * @var int
     */
    protected $temperatureC = 0;
    
    /**
     * pressureHpa
     *
     * @var int
     */
    protected $pressureHpa = 0;
    
    /**
     * humidityPercentage
     *
     * @var int
     */
    protected $humidityPercentage = 0;
    
    /**
     * minTempC
     *
     * @var int
     */
    protected $minTempC = 0;
    
    /**
     * maxTempC
     *
     * @var int
     */
    protected $maxTempC = 0;
    
    /**
     * windSpeedMPS
     *
     * @var int
     */
    protected $windSpeedMPS = 0;
    
    /**
     * windDirectionDeg
     *
     * @var int
     */
    protected $windDirectionDeg = 0;
    
    /**
     * popPercentage
     *
     * @var int
     */
    protected $popPercentage = 0;
    
    /**
     * snowVolume
     *
     * @var int
     */
    protected $snowVolume = 0;

    /**
     * rainVolume
     *
     * @var int
     */
    protected $rainVolume = 0;

    /**
     * cloudsPercentage
     *
     * @var int
     */
    protected $cloudsPercentage = 0;
    
    /**
     * serializedArray
     *
     * @var string
     */
    protected $serializedArray = '';

    /**
     * icon
     *
     * @var string
     */
    protected $icon = '';

    /**
     * Returns the serializedArray
     *
     * @return string $serializedArray
     */
    public function getSerializedArray()
    {
        return $this->serializedArray;
    }
    
    /**
     * Sets the serializedArray
     *
     * @param string $serializedArray
     * @return void
     */
    public function setSerializedArray($serializedArray)
    {
        $this->serializedArray = (string)$serializedArray;
    }
    
    /**
     * Returns the temperatureC
     *
     * @return int temperatureC
     */
    public function getTemperatureC()
    {
        return $this->temperatureC;
    }
    
    /**
     * Sets the temperatureC
     *
     * @param int $temperatureC
     * @return void
     */
    public function setTemperatureC($temperatureC)
    {
        $this->temperatureC = (int)$temperatureC;
    }
    
    /**
     * Returns the pressureHpa
     *
     * @return int pressureHpa
     */
    public function getPressureHpa()
    {
        return $this->pressureHpa;
    }
    
    /**
     * Sets the pressureHpa
     *
     * @param int $pressureHpa
     * @return void
     */
    public function setPressureHpa($pressureHpa)
    {
        $this->pressureHpa = (int)$pressureHpa;
    }
    
    /**
     * Returns the humidityPercentage
     *
     * @return int humidityPercentage
     */
    public function getHumidityPercentage()
    {
        return $this->humidityPercentage;
    }
    
    /**
     * Sets the humidityPercentage
     *
     * @param int $humidityPercentage
     * @return void
     */
    public function setHumidityPercentage($humidityPercentage)
    {
        $this->humidityPercentage = (int)$humidityPercentage;
    }
    
    /**
     * Returns the minTempC
     *
     * @return int minTempC
     */
    public function getMinTempC()
    {
        return $this->minTempC;
    }
    
    /**
     * Sets the minTempC
     *
     * @param int $minTempC
     * @return void
     */
    public function setMinTempC($minTempC)
    {
        $this->minTempC = (int)$minTempC;
    }
    
    /**
     * Returns the maxTempC
     *
     * @return int maxTempC
     */
    public function getMaxTempC()
    {
        return $this->maxTempC;
    }
    
    /**
     * Sets the maxTempC
     *
     * @param int $maxTempC
     * @return void
     */
    public function setMaxTempC($maxTempC)
    {
        $this->maxTempC = (int)$maxTempC;
    }
    
    /**
     * Returns the windSpeedMPS
     *
     * @return int windSpeedMPS
     */
    public function getWindSpeedMPS()
    {
        return $this->windSpeedMPS;
    }
    
    /**
     * Sets the windSpeedMPS
     *
     * @param int $windSpeedMPS
     * @return void
     */
    public function setWindSpeedMPS($windSpeedMPS)
    {
        $this->windSpeedMPS = (int)$windSpeedMPS;
    }
    
    /**
     * Returns the windDirectionDeg
     *
     * @return int windDirectionDeg
     */
    public function getWindDirectionDeg()
    {
        return $this->windDirectionDeg;
    }

    /**
     * Returns wind direction as section so that a label
     * can be assigned
     *
     * @return int
     */
    public function getWindDirSection()
    {
        return floor(fmod((($this->windDirectionDeg + 22.5) / 45), 8));
    }
    
    /**
     * Sets the windDirectionDeg
     *
     * @param int $windDirectionDeg
     * @return void
     */
    public function setWindDirectionDeg($windDirectionDeg)
    {
        $this->windDirectionDeg = (int)$windDirectionDeg;
    }
    
    /**
     * Returns the popPercentage
     *
     * @return int popPercentage
     */
    public function getPopPercentage()
    {
        return $this->popPercentage;
    }
    
    /**
     * Sets the popPercentage
     *
     * @param int $popPercentage
     * @return void
     */
    public function setPopPercentage($popPercentage)
    {
        $this->popPercentage = (int)$popPercentage;
    }
    
    /**
     * Returns the snowVolume
     *
     * @return int snowVolume
     */
    public function getSnowVolume()
    {
        return $this->snowVolume;
    }
    
    /**
     * Sets the snowVolume
     *
     * @param int $snowVolume
     * @return void
     */
    public function setSnowVolume($snowVolume)
    {
        $this->snowVolume = (int)$snowVolume;
    }

    /**
     * Returns the rainVolume
     *
     * @return int rainVolume
     */
    public function getRainVolume()
    {
        return $this->rainVolume;
    }

    /**
     * Sets the rainVolume
     *
     * @param int $rainVolume
     * @return void
     */
    public function setRainVolume($rainVolume)
    {
        $this->rainVolume = (int)$rainVolume;
    }
    
    /**
     * Returns the cloudsPercentage
     *
     * @return int cloudsPercentage
     */
    public function getCloudsPercentage()
    {
        return $this->cloudsPercentage;
    }
    
    /**
     * Sets the cloudsPercentage
     *
     * @param int $cloudsPercentage
     * @return void
     */
    public function setCloudsPercentage($cloudsPercentage)
    {
        $this->cloudsPercentage = (int)$cloudsPercentage;
    }
    
    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }
    
    /**
     * Returns the measureTimestamp
     *
     * @return \DateTime measureTimestamp
     */
    public function getMeasureTimestamp()
    {
        return $this->measureTimestamp;
    }

    /**
     * Sets the measureTimestamp
     *
     * @param \DateTime $measureTimestamp
     * @return void
     */
    public function setMeasureTimestamp(\DateTime $measureTimestamp)
    {
        $this->measureTimestamp = $measureTimestamp;
    }

    /**
     * Returns the icon
     *
     * @return string icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the icon
     *
     * @param string $icon
     * @return void
     */
    public function setIcon($icon)
    {
        $this->icon = (string)$icon;
    }
}