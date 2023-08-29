<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\InternetException;
use React\Promise\Promise;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;
use Throwable;
use function json_decode;
use function sort;
use function str_replace;

class CachePoggitPlugins extends AsyncTask {

	public function onRun() : void {
		$fetch = Internet::fetch(Vars::POGGIT_REPO_URL);
		$fetch->then(
			function (string $raw) {
				$pluginsList = [];
				$json = (array) json_decode($raw, true);
				/** @var array<string> $plugin */
				foreach ($json as $plugin) {
					$pluginsList[] = [
						"name" => $plugin["name"],
						"version" => $plugin["version"],
						"artifact_url" => $plugin["artifact_url"],
						"homepage" => $plugin["html_url"],
						"license" => $plugin["license"],
						"downloads" => $plugin["downloads"],
						"score" => $plugin["score"],
						"api" => $plugin["api"],
						"deps" => $plugin["deps"],
					];
				}

				$this->setResult($pluginsList);
			},
			function (Throwable $e) {
				OhMyPMMP::getInstance()->getLogger()->error(str_replace("{{message}}", $e->getMessage(), OhMyPMMP::getInstance()->getLanguage()->translateString("cache.failed")));
			},
		);
	}

	function onCompletion() : void {

		/** @var array<string, array<string>> $result */
		$result = $this->getResult();
		sort($result);

		OhMyPMMP::getInstance()->setPluginsList($result);
		OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning = false;
		OhMyPMMP::getInstance()->getLogger()->info(OhMyPMMP::getInstance()->getLanguage()->translateString("cache.successfully"));
	}
}
