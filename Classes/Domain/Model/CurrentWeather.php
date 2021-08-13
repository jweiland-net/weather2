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
     * @var int
     */
    protected $conditionCode = 0;

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
    public function setSerializedArray(string $serializedArray): void
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
    public function setTemperatureC(float $temperatureC): void
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
    public function setPressureHpa(int $pressureHpa): void
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
    public function setHumidityPercentage(int $humidityPercentage): void
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
     */
    public function setMinTempC(float $minTempC): void
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
    public function setMaxTempC(float $maxTempC): void
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
    public function setWindSpeedMPS(float $windSpeedMPS): void
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
     */
    public function setWindDirectionDeg(int $windDirectionDeg): void
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
    public function setPopPercentage(int $popPercentage): void
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
    public function setSnowVolume(int $snowVolume): void
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
    public function setRainVolume(int $rainVolume): void
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
    public function setCloudsPercentage(int $cloudsPercentage): void
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
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime|null measureTimestamp
     */
    public function getMeasureTimestamp(): ?\DateTime
    {
        return $this->measureTimestamp;
    }

    /**
     * @param \DateTime $measureTimestamp
     */
    public function setMeasureTimestamp(\DateTime $measureTimestamp): void
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
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return int
     */
    public function getConditionCode(): int
    {
        return $this->conditionCode;
    }

    /**
     * @param int $conditionCode
     */
    public function setConditionCode(int $conditionCode): void
    {
        $this->conditionCode = $conditionCode;
    }
}
