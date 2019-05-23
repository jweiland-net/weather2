<?php
declare(strict_types=1);
namespace JWeiland\Weather2\Domain\Model;

/*
 * This file is part of the  project.
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
    public function setWarnCellId(string $warnCellId)
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
    public function setName(string $name)
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
    public function setShortName(string $shortName)
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
    public function setSign(string $sign)
    {
        $this->sign = $sign;
    }
}
