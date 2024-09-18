<?php

return [
    'weather2_dwd_warn-cell-search' => [
        'path' => '/weather2/dwd/warn-cell-search',
        'target' => \JWeiland\Weather2\Ajax\DeutscherWetterdienstWarnCellSearch::class . '::renderWarnCells',
    ],
];
