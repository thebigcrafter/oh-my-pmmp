<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\InternetException;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\AsyncTasks;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;

class CachePoggitPlugins extends AsyncTask {
	public function onRun(): void
	{
		Internet::fetch(Vars::POGGIT_REPO_URL)->done(function(string $raw) {
			$pluginsList = [];
			$json = (array) json_decode($raw, true);

			foreach ($json as $plugin) {
				$pluginsList[] = ["name" => $plugin["name"], "version" => $plugin["version"], "artifact_url" => $plugin["artifact_url"]]; /* @phpstan-ignore-line */
			}

			$this->setResult($pluginsList);
		}, function (InternetException $e) {
			OhMyPMMP::getInstance()->getLogger()->error(TextFormat::RED . "Could not get Poggit plugins list: " . $e->getMessage());
		});
	}

	function onCompletion(): void
	{
		$result = $this->getResult();

		OhMyPMMP::getInstance()->setPluginsList($result); /* @phpstan-ignore-line */
		OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning = false;
		OhMyPMMP::getInstance()->getLogger()->info(TextFormat::GREEN . "Plugins list has been cached. You can install plugin now");
	}
}