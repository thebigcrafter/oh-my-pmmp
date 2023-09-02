<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

use DOMDocument;
use Generator;
use pocketmine\utils\InternetException;
use pocketmine\utils\InternetRequestResult;
use SOFe\AwaitGenerator\Await;
use function file_get_contents;
use function get_headers;
use function is_bool;
use function is_numeric;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function round;
use function strip_tags;
use function trim;

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

	/**
	 * @return null|array<string, string>
	 */
	public static function fetchDescription(string $url) : ?array {
		$raw_text = file_get_contents($url);
		$sections = [];
		if ($raw_text !== false) {
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			$doc->loadHTML($raw_text);
			libxml_clear_errors();
			foreach ($doc->getElementsByTagName('*') as $element) {
				if ($element->tagName == 'h1') {
					$current_h1 = trim($element->textContent);
				} elseif (!empty($current_h1)) {
					$node = $doc->saveHTML($element);
					$content = (is_bool($node)) ? "" : $node;
					$section_content = strip_tags($content);
					if (!isset($sections[$current_h1])) {
						$sections[$current_h1] = '';
					}
					$sections[$current_h1] .= $section_content;
				}
			}
			return $sections;
		}
		return null;
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
