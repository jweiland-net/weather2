<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Ajax;

use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class DeutscherWetterdienstWarnCellSearch
 */
class DeutscherWetterdienstWarnCellSearch
{
    /**
     * @return Response
     */
    public function renderWarnCells(): Response
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $repository = $objectManager->get(DwdWarnCellRepository::class);
        $term = GeneralUtility::_GET('query');
        $dwdWarnCells = $repository->findByName($term);
        $suggestions = [];
        foreach ($dwdWarnCells as $dwdWarnCell) {
            $label = sprintf('%s (%s)', $dwdWarnCell->getName(), $dwdWarnCell->getWarnCellId());
            $suggestions[] = [
                'data' => $dwdWarnCell->getWarnCellId(),
                'value' => $label,
            ];
        }
        $response = new Response('php://temp', 200, ['Content-Type' => 'application/json; charset=utf-8']);
        $response->getBody()->write(json_encode(['suggestions' => $suggestions]));
        return $response;
    }
}
