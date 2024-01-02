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
    /**
     * Remove files and folder
     */
    public static function remove(string $path) : Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($path) {
            $fs = new \Symfony\Component\Filesystem\Filesystem();
            try {
				$fs->remove($path);
                $resolve();
            } catch (IOException $e) {
                $reject($e);
            }
        });
    }

    /**
     * Rename files and directories, can move them also
     */
    public static function rename(string $origin, string $target) : Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($origin, $target) {
            $fs = new \Symfony\Component\Filesystem\Filesystem();
            try {
				$fs->rename($origin, $target);
                $resolve();
            } catch (IOException $e) {
                $reject($e);
            }
        });
    }

	/**
	 * Checks the existence of files or directories.
	 *
	 * @param iterable|string $files
	 *
	 * @return bool
	 */
    public static function exists(iterable|string $files) : bool
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        return $fs->exists($files);
    }
}
