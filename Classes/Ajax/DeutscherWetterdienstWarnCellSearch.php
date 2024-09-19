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
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;

/**
 * Class DeutscherWetterdienstWarnCellSearch
 */
class DeutscherWetterdienstWarnCellSearch
{
    /**
     * @var DwdWarnCellRepository
     */
    public $dwdWarnCellRepository;

    public function __construct(DwdWarnCellRepository $dwdWarnCellRepository)
    {
        $this->dwdWarnCellRepository = $dwdWarnCellRepository;
    }

    public function renderWarnCells(ServerRequestInterface $request): Response
    {
        $dwdWarnCells = $this->dwdWarnCellRepository->findByName(
            htmlspecialchars(strip_tags($request->getQueryParams()['query'] ?? '')),
        );

        $suggestions = [];
        foreach ($dwdWarnCells as $dwdWarnCell) {
            $suggestions[] = [
                'data' => $dwdWarnCell->getWarnCellId(),
                'value' => sprintf(
                    '%s (%s)',
                    $dwdWarnCell->getName(),
                    $dwdWarnCell->getWarnCellId(),
                ),
            ];
        }

        return new JsonResponse(
            ['suggestions' => $suggestions],
        );
    }
}
