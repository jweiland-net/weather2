<?php
namespace JWeiland\Weather2\Ajax;

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
