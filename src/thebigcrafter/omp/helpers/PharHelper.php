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
use Phar;
use SOFe\AwaitGenerator\Await;
use Throwable;
use function file_put_contents;

class PharHelper
{
    /**
     * Create a Phar file
     * Returns the number of bytes that were written to the file, or throw an Exception on failure.
     */
    public static function create(string $path, string $content) : Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($path, $content) {
            $exec = file_put_contents($path, $content);

            if ($exec === false) {
                $reject(new Exception("Cannot create Phar file"));
                return;
            }

            $resolve($exec);
        });
    }

    public static function extract(string $filePath, string $to) : Generator
    {
        return Await::promise(function (Closure $resolve, Closure $reject) use ($filePath, $to) {
            $phar = new Phar($filePath);
            try {
                $phar->extractTo($to);
                $resolve(true);
            } catch (Throwable $e) {
                $reject($e);
            }
        });
    }
}
