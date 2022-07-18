<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use pocketmine\utils\InternetException;
use pocketmine\utils\InternetRequestResult;
use React\Promise\Deferred;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class Internet
{
	/**
	 * Get resource from the URL
	 *
	 * @retrun PromiseInterface|Promise
	 */
	public static function fetch(string $url): Promise|PromiseInterface
	{
		$deferred = new Deferred();

		try {
			$res = \pocketmine\utils\Internet::getURL($url);
			if ($res instanceof InternetRequestResult) {
				$deferred->resolve($res->getBody());
			}
		} catch (InternetException $e) {
			$deferred->reject($e);
		}

		return $deferred->promise();
	}

	/**
	 *  Get the file size of any remote resource
	 *
	 * @param string $url
	 * @param boolean $formatSize
	 * @param boolean $useHead
	 * @return Promise|PromiseInterface
	 *
	 * @author  Stephan Schmitz <eyecatchup@gmail.com>
	 * @license MIT <http://eyecatchup.mit-license.org/>
	 * @url     <https://gist.github.com/eyecatchup/f26300ffd7e50a92bc4d>
	 */
	function getRemoteFilesize(string $url, bool $formatSize = true, bool $useHead = true): Promise|PromiseInterface
	{
		$deferred = new Deferred();
		$ch = curl_init($url);

		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_NOBODY => 1,
		]);

		if (false !== $useHead) {
			curl_setopt($ch, CURLOPT_NOBODY, 1);
		}

		curl_exec($ch);
		$clen = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($ch);

		// cannot retrieve file size, return "-1"
		if (!$clen) {
			$deferred->reject();
		} else {
			$deferred->resolve();
		}

		return $deferred->promise();
	}
}
