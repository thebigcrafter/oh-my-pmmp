<?php

declare(strict_types=1);

namespace thebigcrafter\omp\utils;

use Closure;
use Generator;
use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Exception\IOException;

class Filesystem
{
	public static function remove(string $path): Generator
	{
		return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($path) {
			$fs = new \Symfony\Component\Filesystem\Filesystem();
			try {
				$resolve($fs->remove($path));
			} catch (IOException $e) {
				$reject($e);
			}
		});
	}

	public static function exists(\Traversable|array|string $files): bool
	{
		$fs = new \Symfony\Component\Filesystem\Filesystem();
		return $fs->exists($files);
	}
}