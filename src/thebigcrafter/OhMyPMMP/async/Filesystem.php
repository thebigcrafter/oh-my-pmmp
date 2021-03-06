<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use Phar;
use React\Promise\Deferred;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Throwable;
use function file_put_contents;

class Filesystem
{
	/**
	 * Write a data to a file
	 *
	 * @retrun PromiseInterface|Promise
	 */
	public static function writeFile(
		string $file,
		string $data,
	): PromiseInterface|Promise {
		$deferred = new Deferred();

		try {
			file_put_contents($file, $data);

			$deferred->resolve();
		} catch (Throwable $e) {
			$deferred->reject($e);
		}
		return $deferred->promise();
	}

	/**
	 * Unlink Phar file
	 *
	 * @retrun PromiseInterface|Promise
	 */
	public static function unlinkPhar(string $file): PromiseInterface|Promise
	{
		$deferred = new Deferred();

		try {
			Phar::unlinkArchive($file);

			$deferred->resolve();
		} catch (Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 * Extract Phar file
	 *
	 * @retrun PromiseInterface|Promise
	 */
	public static function extractPhar(
		string $file,
		string $to,
	): PromiseInterface|Promise {
		$deferred = new Deferred();

		$phar = new Phar($file);
		$result = $phar->extractTo($to);

		if ($result) {
			$deferred->resolve($result);
		} else {
			$deferred->reject($result);
		}

		return $deferred->promise();
	}
}
