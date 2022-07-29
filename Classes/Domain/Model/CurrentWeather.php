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

    public function getSerializedArray(): string
    {
        return $this->serializedArray;
    }

    public function setSerializedArray(string $serializedArray): void
    {
        $this->serializedArray = $serializedArray;
    }

    public function getTemperatureC(): float
    {
        return $this->temperatureC;
    }

    public function setTemperatureC(float $temperatureC): void
    {
        $this->temperatureC = $temperatureC;
    }

    public function getPressureHpa(): int
    {
        return $this->pressureHpa;
    }

    public function setPressureHpa(int $pressureHpa): void
    {
        $this->pressureHpa = $pressureHpa;
    }

    public function getHumidityPercentage(): int
    {
        return $this->humidityPercentage;
    }

    public function setHumidityPercentage(int $humidityPercentage): void
    {
        $this->humidityPercentage = $humidityPercentage;
    }

    public function getMinTempC(): float
    {
        return $this->minTempC;
    }

    public function setMinTempC(float $minTempC): void
    {
        $this->minTempC = $minTempC;
    }

    public function getMaxTempC(): float
    {
        return $this->maxTempC;
    }

    public function setMaxTempC(float $maxTempC): void
    {
        $this->maxTempC = $maxTempC;
    }

    public function getWindSpeedMPS(): float
    {
        return $this->windSpeedMPS;
    }

    public function setWindSpeedMPS(float $windSpeedMPS): void
    {
        $this->windSpeedMPS = $windSpeedMPS;
    }

    public function getWindDirectionDeg(): int
    {
        return $this->windDirectionDeg;
    }

    /**
     * Returns wind direction as section so that a label
     * can be assigned
     */
    public function getWindDirSection(): float
    {
        return floor(fmod((($this->windDirectionDeg + 22.5) / 45), 8));
    }

    public function setWindDirectionDeg(int $windDirectionDeg): void
    {
        $this->windDirectionDeg = $windDirectionDeg;
    }

    public function getPopPercentage(): int
    {
        return $this->popPercentage;
    }

    public function setPopPercentage(int $popPercentage): void
    {
        $this->popPercentage = $popPercentage;
    }

    public function getSnowVolume(): int
    {
        return $this->snowVolume;
    }

    public function setSnowVolume(int $snowVolume): void
    {
        $this->snowVolume = $snowVolume;
    }

    public function getRainVolume(): int
    {
        return $this->rainVolume;
    }

    public function setRainVolume(int $rainVolume): void
    {
        $this->rainVolume = $rainVolume;
    }

    public function getCloudsPercentage(): int
    {
        return $this->cloudsPercentage;
    }

    public function setCloudsPercentage(int $cloudsPercentage): void
    {
        $this->cloudsPercentage = $cloudsPercentage;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMeasureTimestamp(): ?\DateTime
    {
        return $this->measureTimestamp;
    }

    public function setMeasureTimestamp(\DateTime $measureTimestamp): void
    {
        $this->measureTimestamp = $measureTimestamp;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public function getConditionCode(): int
    {
        return $this->conditionCode;
    }

    public function setConditionCode(int $conditionCode): void
    {
        $this->conditionCode = $conditionCode;
    }
}
