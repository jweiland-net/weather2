<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

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
