<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

require_once __DIR__ . '/../../vendor/autoload.php';

use Phar;
use pocketmine\utils\Internet;
use pocketmine\utils\InternetException;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Throwable;

class AsyncTasks
{
	/**
	 * Get a resource from the URL
	 */
	public static function fetch(string $url): PromiseInterface
	{
		$deferred = new Deferred();

		try {
			$raw = Internet::simpleCurl($url, 5);

			$deferred->resolve($raw->getBody());
		} catch (InternetException $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 * Write a content to a file
	 */
	public static function writeFile(string $filename, string $content): PromiseInterface
	{
		$deferred = new Deferred();

		try {
			file_put_contents($filename, $content);

			$deferred->resolve();
		} catch (Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 * Delete a file
	 */
	public static function deleteFile(string $filename): PromiseInterface
	{
		$deferred = new Deferred();

		try {
			Phar::unlinkArchive($filename);

			$deferred->resolve();
		} catch (Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}
}