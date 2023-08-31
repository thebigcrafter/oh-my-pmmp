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
use pocketmine\utils\InternetException;
use pocketmine\utils\InternetRequestResult;
use SOFe\AwaitGenerator\Await;
use function get_headers;
use function is_numeric;
use function round;

class Internet {

	/**
	 * Fetch a resource from the specified URL asynchronously.
	 *
	 * @param string $url The URL from which to fetch the resource.
	 */

	public static function awaitFetch(string $url) : Generator {
		$f = yield Await::RESOLVE;
		$r = yield Await::REJECT;
		try {
			$res = \pocketmine\utils\Internet::getURL($url);
			if($res instanceof InternetRequestResult) {
				$f($res->getBody());
			}
		} catch (InternetException $e) {
			$r($e);
		}
		return yield Await::ONCE;
	}

	public static function fetchRemoteFileSize(string $url) : string {
		$headers = get_headers($url, true);

		if (isset($headers['Content-Length']) && is_numeric($headers['Content-Length'])) {
			$bytes = (int) $headers['Content-Length'];
			return self::formatFileSize($bytes);
		}

		return 'Unknown'; // Default value if Content-Length header is not present or not numeric
	}

	private static function formatFileSize(int $bytes) : string {
		if ($bytes < 1024) {
			return $bytes . ' B';
		} elseif ($bytes < 1048576) {
			return round($bytes / 1024, 2) . ' KB';
		} else {
			return round($bytes / 1024 / 1024, 2) . ' MB';
		}
	}
}
