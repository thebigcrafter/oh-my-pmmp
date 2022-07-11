<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\InternetException;
use React\Promise\Promise;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;

use function json_decode;
use function str_replace;

class CachePoggitPlugins extends AsyncTask {
	public function onRun(): void
	{
		/** @var Promise $fetch */
		$fetch = Internet::fetch(Vars::POGGIT_REPO_URL);
		$fetch->done(function(string $raw) {
			$pluginsList = [];
			$json = (array) json_decode($raw, true);

			foreach ($json as $plugin) {
				$pluginsList[] = ["name" => $plugin["name"], "version" => $plugin["version"], "artifact_url" => $plugin["artifact_url"]]; /* @phpstan-ignore-line */
			}

			$this->setResult($pluginsList);
		}, function (InternetException $e) {
			OhMyPMMP::getInstance()->getLogger()->error(str_replace("{{message}}", $e->getMessage(), OhMyPMMP::getInstance()->getLanguage()->translateString("cache.failed")));
		});
	}

	function onCompletion(): void
	{
		$result = $this->getResult();

		/** @var array<string, array<string>> $result */
		OhMyPMMP::getInstance()->setPluginsList($result);

		OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning = false;
		OhMyPMMP::getInstance()->getLogger()->info(OhMyPMMP::getInstance()->getLanguage()->translateString("cache.successfully"));
	}
}
