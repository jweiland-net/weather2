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
 * Warn cell of DWD.de
 */
class DwdWarnCell extends AbstractEntity
{
    /**
     * @var string
     */
    protected $warnCellId = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $shortName = '';

    /**
     * @var string
     */
    protected $sign = '';

    /**
     * @return string
     */
    public function getWarnCellId(): string
    {
        return $this->warnCellId;
    }

    /**
     * @param string $warnCellId
     */
    public function setWarnCellId(string $warnCellId): void
    {
        $this->warnCellId = $warnCellId;
    }

    /**
     * @return string
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
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName(string $shortName): void
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * @param string $sign
     */
    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }
}
