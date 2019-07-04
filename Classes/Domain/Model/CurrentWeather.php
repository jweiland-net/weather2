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
 * CurrentWeather
 */
class CurrentWeather extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var \DateTime
     */
    protected $measureTimestamp;

    /**
     * @var float
     */
    protected $temperatureC = 0.0;

    /**
     * @var int
     */
    protected $pressureHpa = 0;

    /**
     * @var int
     */
    protected $humidityPercentage = 0;

    /**
     * @var float
     */
    protected $minTempC = 0.0;

    /**
     * @var float
     */
    protected $maxTempC = 0.0;

    /**
     * @var float
     */
    protected $windSpeedMPS = 0.0;

    /**
     * @var int
     */
    protected $windDirectionDeg = 0;

    /**
     * @var int
     */
    protected $popPercentage = 0;

    /**
     * @var int
     */
    protected $snowVolume = 0;

    /**
     * @var int
     */
    protected $rainVolume = 0;

    /**
     * @var int
     */
    protected $cloudsPercentage = 0;

    /**
     * @var string
     */
    protected $serializedArray = '';

    /**
     * @var string
     */
    protected $icon = '';

    /**
     * @return string $serializedArray
     */
    public function getSerializedArray(): string
    {
        return $this->serializedArray;
    }

    /**
     * @param string $serializedArray
     */
    public function setSerializedArray(string $serializedArray)
    {
        $this->serializedArray = $serializedArray;
    }

    /**
     * @return float temperatureC
     */
    public function getTemperatureC(): float
    {
        return $this->temperatureC;
    }

    /**
     * @param float $temperatureC
     */
    public function setTemperatureC(float $temperatureC)
    {
        $this->temperatureC = $temperatureC;
    }

    /**
     * @return int pressureHpa
     */
    public function getPressureHpa(): int
    {
        return $this->pressureHpa;
    }

    /**
     * Sets the pressureHpa
     *
     * @param int $pressureHpa
     */
    public function setPressureHpa(int $pressureHpa)
    {
        $this->pressureHpa = $pressureHpa;
    }

    /**
     * @return int humidityPercentage
     */
    public function getHumidityPercentage(): int
    {
        return $this->humidityPercentage;
    }

    /**
     * @param int $humidityPercentage
     */
    public function setHumidityPercentage(int $humidityPercentage)
    {
        $this->humidityPercentage = $humidityPercentage;
    }

    /**
     * @return float minTempC
     */
    public function getMinTempC(): float
    {
        return $this->minTempC;
    }

    /**
     * @param float $minTempC
     * @return void
     */
    public function setMinTempC(float $minTempC)
    {
        $this->minTempC = $minTempC;
    }

    /**
     * @return float maxTempC
     */
    public function getMaxTempC(): float
    {
        return $this->maxTempC;
    }

    /**
     * @param float $maxTempC
     */
    public function setMaxTempC(float $maxTempC)
    {
        $this->maxTempC = $maxTempC;
    }

    /**
     * @return float windSpeedMPS
     */
    public function getWindSpeedMPS(): float
    {
        return $this->windSpeedMPS;
    }

    /**
     * @param float $windSpeedMPS
     */
    public function setWindSpeedMPS(float $windSpeedMPS)
    {
        $this->windSpeedMPS = $windSpeedMPS;
    }

    /**
     * @return int windDirectionDeg
     */
    public function getWindDirectionDeg(): int
    {
        return $this->windDirectionDeg;
    }

    /**
     * Returns wind direction as section so that a label
     * can be assigned
     *
     * @return float
     */
    public function getWindDirSection(): float
    {
        return floor(fmod((($this->windDirectionDeg + 22.5) / 45), 8));
    }

    /**
     * @param int $windDirectionDeg
     * @return void
     */
    public function setWindDirectionDeg(int $windDirectionDeg)
    {
        $this->windDirectionDeg = $windDirectionDeg;
    }

    /**
     * @return int popPercentage
     */
    public function getPopPercentage(): int
    {
        return $this->popPercentage;
    }

    /**
     * @param int $popPercentage
     */
    public function setPopPercentage(int $popPercentage)
    {
        $this->popPercentage = $popPercentage;
    }

    /**
     * @return int snowVolume
     */
    public function getSnowVolume(): int
    {
        return $this->snowVolume;
    }

    /**
     * @param int $snowVolume
     */
    public function setSnowVolume(int $snowVolume)
    {
        $this->snowVolume = $snowVolume;
    }

    /**
     * @return int rainVolume
     */
    public function getRainVolume(): int
    {
        return $this->rainVolume;
    }

    /**
     * @param int $rainVolume
     */
    public function setRainVolume(int $rainVolume)
    {
        $this->rainVolume = $rainVolume;
    }

    /**
     * @return int cloudsPercentage
     */
    public function getCloudsPercentage(): int
    {
        return $this->cloudsPercentage;
    }

    /**
     * @param int $cloudsPercentage
     */
    public function setCloudsPercentage(int $cloudsPercentage)
    {
        $this->cloudsPercentage = $cloudsPercentage;
    }

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime measureTimestamp
     */
    public function getMeasureTimestamp(): \DateTime
    {
        return $this->measureTimestamp;
    }

    /**
     * @param \DateTime $measureTimestamp
     */
    public function setMeasureTimestamp(\DateTime $measureTimestamp)
    {
        $this->measureTimestamp = $measureTimestamp;
    }

    /**
     * @return string icon
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
    }
}
