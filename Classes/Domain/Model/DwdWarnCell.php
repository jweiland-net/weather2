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
    protected string $warnCellId = '';

    protected string $name = '';

    protected string $shortName = '';

    protected string $sign = '';

    public function getWarnCellId(): string
    {
        return $this->warnCellId;
    }

    public function setWarnCellId(string $warnCellId): void
    {
        $this->warnCellId = $warnCellId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): void
    {
        $this->shortName = $shortName;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }
}
