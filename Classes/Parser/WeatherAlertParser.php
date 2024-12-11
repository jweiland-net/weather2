<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Parser;

class WeatherAlertParser implements WeatherAlertParserInterface
{
    public function parse(string $data): array
    {
        $pattern = '/^warnWetter\.loadWarnings\(|\);$/';
        $parseAlertData = json_decode(preg_replace($pattern, '', (string)$data), true);
        if ($parseAlertData === null) {
            throw new \UnexpectedValueException(
                'Response can not be parsed because it is an invalid string',
                1485944083,
            );
        }

        return $parseAlertData;
    }
}
