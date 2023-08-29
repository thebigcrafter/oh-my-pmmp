<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use Exception;
use Phar;
use React\Promise\Deferred;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Throwable;
use function file_put_contents;
use function filetype;
use function is_dir;
use function reset;
use function rmdir;
use function scandir;
use function unlink;

class Filesystem {

	/**
	 * Write data to a file
	 *
	 * @param string $file
	 * @param string $data
	 * @return PromiseInterface|Promise
	 */
	public static function writeFile(string $file, string $data) : PromiseInterface|Promise {
		$deferred = new Deferred();

		try {
			file_put_contents($file, $data);

			$deferred->resolve(null);
		} catch (Throwable $e) {
			$deferred->reject($e);
		}
		return $deferred->promise();
	}

	/**
	 * Unlink Phar file
	 *
	 * @param string $file
	 * @return PromiseInterface|Promise
	 */
	public static function unlinkPhar(string $file) : PromiseInterface|Promise {
		$deferred = new Deferred();

		try {
			Phar::unlinkArchive($file);

			$deferred->resolve(null);
		} catch (Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 * Extract Phar file
	 *
	 * @param string $file
	 * @param string $to
	 * @return  PromiseInterface
	 */
	public static function extractPhar(string $file, string $to): PromiseInterface {
		$deferred = new Deferred();

		try {
			$phar = new Phar($file);
			$result = $phar->extractTo($to);

			if ($result) {
				$deferred->resolve(true);
			} else {
				$deferred->reject(new Exception("Extraction failed."));
			}
		} catch (Exception $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 * Delete folder
	 *
	 * @param string $folder
	 * @return void
	 */
	public static function deleteFolder(string $folder) : void {
		if (is_dir($folder)) {
			$objects = scandir($folder);
			if (!$objects) {
				return;
			}
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($folder . "/" . $object) == "dir") {
						self::deleteFolder($folder . "/" . $object);
					} else {
						unlink($folder . "/" . $object);
					}
				}
			}
			rmdir($folder);
		}
	}
}
