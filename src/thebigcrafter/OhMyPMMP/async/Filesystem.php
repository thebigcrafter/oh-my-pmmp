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
use React\Promise\PromiseInterface;
use Throwable;
use function file_put_contents;
use function filetype;
use function is_dir;
use function rmdir;
use function scandir;
use function unlink;

class Filesystem {

	/**
	 * Write data to a file asynchronously.
	 *
	 * @param string $file The path to the file to write.
	 * @param string $data The data to write to the file.
	 * @return PromiseInterface<bool> A promise that resolves when the write operation is complete.
	 */
	public static function writeFile(string $file, string $data) : PromiseInterface
	{
		$deferred = new Deferred();

		try {
			$result = file_put_contents($file, $data);

			$deferred->resolve($result !== false);
		} catch (Throwable $e) {
			$deferred->reject($e);
		}
		return $deferred->promise();
	}

	/**
	 * Unlinks (deletes) a Phar archive asynchronously.
	 *
	 * @param string $file The path to the Phar archive to unlink.
	 * @return PromiseInterface<bool> A promise that resolves when the unlink operation is complete,
	 *                                and rejects with an exception if there's an error.
	 */
	public static function unlinkPhar(string $file) : PromiseInterface {
		$deferred = new Deferred();

		try {
			$result = Phar::unlinkArchive($file);
			$deferred->resolve($result);
		} catch (Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 * Extracts the contents of a Phar archive asynchronously.
	 *
	 * @param string $file The path to the Phar archive to extract.
	 * @param string $to   The directory where the contents will be extracted.
	 * @return PromiseInterface<bool> A promise that resolves to `true` if the extraction is successful,
	 *                               and rejects with an exception if there's an error.
	 */
	public static function extractPhar(string $file, string $to) : PromiseInterface {
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
	 * Recursively delete a folder and its contents.
	 *
	 * @param string $folder The path of the folder to delete.
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
