<?php
declare(strict_types=1);
namespace JWeiland\Weather2\Domain\Repository;

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

use JWeiland\Weather2\Domain\Model\DwdWarnCell;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * DWD warn cell repository
 */
class DwdWarnCellRepository extends Repository
{
    /**
     * Finds objects by properties name and district
     *
     * @param string $name
     * @return DwdWarnCell[]
     */
    public function findByName(string $name): array
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                $query->like('name', '%' . trim($name) . '%'),
                $query->equals('warn_cell_id', $name)
            )
        );
        return $query->execute()->toArray();
    }
}
