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

namespace thebigcrafter\omp\utils;

use Closure;
use Generator;
use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Exception\IOException;

class Filesystem
{
    public static function remove(string $path) : Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($path) {
            $fs = new \Symfony\Component\Filesystem\Filesystem();
            try {
                // @phpstan-ignore-next-line
                $resolve($fs->remove($path));
            } catch (IOException $e) {
                $reject($e);
            }
        });
    }

    // @phpstan-ignore-next-line
    public static function exists(iterable|string $files) : bool
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        return $fs->exists($files);
    }
}
