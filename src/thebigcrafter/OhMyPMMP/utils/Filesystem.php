<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

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
		$f = yield Await::RESOLVE;
		$r = yield Await::REJECT;

		try {
			$result = file_put_contents($file, $data);
			$f($result !== false);
		} catch (Throwable $e) {
			$r($e);
		}

		return yield Await::ONCE;
	}

	public static function awaitUnlinkPhar(string $file) : Generator {
		$f = yield Await::RESOLVE;
		$r = yield Await::REJECT;

		try {
			$result = Phar::unlinkArchive($file);
			$f($result);
		} catch (Throwable $e) {
			$r($e);
		}

		return yield Await::ONCE;
	}

	public static function awaitExtractPhar(string $file, string $to) : Generator {
		$f = yield Await::RESOLVE;
		$r = yield Await::REJECT;

		try {
			$phar = new Phar($file);
			$result = $phar->extractTo($to);
			$f($result);
		} catch (Throwable $e) {
			$r($e);
		}

		return yield Await::ONCE;
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
