<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/replacer.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Replacer\Tests\Functional\Fixtures;

class DummyUserFunc
{
    public function render($_, $conf): string
    {
        return '<p>Hello world</p>';
    }
}
