<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

use Closure;
use Generator;
use Phar;
use SOFe\AwaitGenerator\Await;
use Throwable;
use function file_put_contents;
use function is_dir;
use function rmdir;
use function scandir;
use function unlink;
use const DIRECTORY_SEPARATOR;

class Filesystem {

	public static function awaitWrite(string $file, string $data) : Generator {
		return yield from Await::promise(function(Closure $resolve, Closure $reject) use ($data, $file) {
			try {
				$result = file_put_contents($file, $data);
				$resolve($result !== false);
			} catch (Throwable $e) {
				$reject($e);
			}
		});
	}

	public static function awaitUnlinkPhar(string $file) : Generator {
		return yield from Await::promise(function(Closure $resolve,  Closure $reject) use ($file) {
			try {
				$result = Phar::unlinkArchive($file);
				$resolve($result);
			} catch (Throwable $e) {
				$reject($e);
			}
		});
	}

	public static function awaitExtractPhar(string $file, string $to) : Generator {
		return yield from Await::promise(function(Closure $resolve,  Closure $reject) use ($to, $file) {
			try {
				$phar = new Phar($file);
				$result = $phar->extractTo($to);
				$resolve($result);
			} catch (Throwable $e) {
				$reject($e);
			}
		});
	}

	/**
	 * Recursively delete a folder and its contents.
	 *
	 * @param string $folder The path of the folder to delete.
	 */
	public static function deleteFolder(string $folder) : bool {
		if (!is_dir($folder)) {
			return false;
		}

		$objects = scandir($folder);
		if ($objects === false) {
			return false;
		}

		foreach ($objects as $object) {
			if ($object !== "." && $object !== "..") {
				$path = $folder . DIRECTORY_SEPARATOR . $object;
				if (is_dir($path)) {
					self::deleteFolder($path);
				} else {
					unlink($path);
				}
			}
		}

		return rmdir($folder);
	}

}
