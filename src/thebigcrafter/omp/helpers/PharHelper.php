<?php

/*
 * This file is part of oh-my-pmmp.
 *
 * (c) thebigcrafter <hello@thebigcrafter.team>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\omp\helpers;

use Closure;
use Exception;
use Generator;
use SOFe\AwaitGenerator\Await;
use function file_put_contents;

class PharHelper
{
    public static function writePhar(string $path, string $content) : Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($path, $content) {
            $exec = file_put_contents($path, $content);

            if ($exec === false) {
                $reject(new Exception("Cannot create Phar file"));
            }

            $resolve($exec);
        });
    }
}
