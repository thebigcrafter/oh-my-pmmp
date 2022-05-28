<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

require_once __DIR__ . '/../../vendor/autoload.php';

use pocketmine\utils\Internet;
use pocketmine\utils\InternetException;
use React\Promise\Deferred;
use React\Promise\Promise;

class AsyncTasks {
	public static function getURL(string $url): Promise {
		$deferred = new Deferred();

		try {
			$raw = Internet::simpleCurl($url, 5);

			$deferred->resolve($raw->getBody());
		} catch (InternetException $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	public static function writeFile(string $filename, string $content): Promise {
		$deferred = new Deferred();

		try {
			file_put_contents($filename, $content);

			$deferred->resolve();
		} catch (\Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	public static function deleteFile(string $filename): Promise {
		$deferred = new Deferred();

		try {
			unlink($filename);

			$deferred->resolve();
		} catch (\Throwable $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}
}