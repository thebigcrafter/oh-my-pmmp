<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use SOFe\AwaitGenerator\Await;
use thebigcrafter\OhMyPMMP\cache\PluginCache;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\cache\Version;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\utils\Internet;
use thebigcrafter\OhMyPMMP\utils\Utils;
use thebigcrafter\OhMyPMMP\utils\Vars;
use function array_merge;
use function json_decode;
use function sort;

class CachePlugins {

	private static bool $hasCached = false;

	public static function hasCached() : bool {
		return self::$hasCached;
	}

	public static function setHasCached(bool $status) : void {
		self::$hasCached = $status;
	}

	public static function Cache() : void {

		$cacheTask = new class extends AsyncTask {
			public function onRun() : void {
				Await::f2c(function () {
					$response = yield from Internet::awaitFetch(Vars::POGGIT_REPO_URL);
					$plugins = json_decode($response, true);
					$this->setResult($plugins);
				});
			}

			public function onCompletion() : void {
				/** @var array<string, array<string>> $result */
				$result = $this->getResult();
				sort($result);
				$pluginCaches = [];
				foreach ($result as $plugin) {
					$name = $plugin["name"];
					$license = $plugin["license"] ?? "None";
					$downloads = (int) $plugin["downloads"];
					$artifactUrl = $plugin["artifact_url"];
					/** @phpstan-var array<array{from: string, to: string}> $api */
					$api = (array) $plugin["api"];
					/** @phpstan-var array{from: string, to: string} $apiShift */
					$apiShift = array_merge(...$api);
					/** @phpstan-var array<array{name: string, version: string, depRelId: int, isHard: bool}> $deps */
					$deps = (array) $plugin["deps"];
					$score = (int) $plugin["score"];

					if (!isset($pluginCaches[$name])) {
						$pluginCaches[$name] = new PluginCache($name, $license, $downloads, [], $score);
					}
					$description_url = $plugin["description_url"];
					$version = new Version($plugin["version"], $artifactUrl, $description_url, $apiShift, $deps);
					$pluginCache = $pluginCaches[$name];
					$pluginCache->pushVersion($version);
				}
				CachePlugins::setHasCached(true);
				PluginsPool::addMultiple($pluginCaches);
				OhMyPMMP::getInstance()->getLogger()->info(Utils::translate("cache.successfully"));
			}
		};

		Server::getInstance()->getAsyncPool()->submitTask($cacheTask);
	}
}
