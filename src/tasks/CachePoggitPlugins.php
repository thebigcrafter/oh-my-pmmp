<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;

class CachePoggitPlugins extends AsyncTask {
	public function onRun(): void
	{
		$pluginsList = [];
		$req = Internet::getURL(Vars::POGGIT_REPO_URL);

		if($req === null) {
			$this->setResult(false);
			return;
		}

		$json = json_decode($req->getBody(), true);

		foreach ($json as $plugin) {
			$pluginsList[] = ["name" => $plugin["name"], "version" => $plugin["version"], "artifact_url" => $plugin["artifact_url"]];
		}

		$this->setResult($pluginsList);
	}

	function onCompletion(): void
	{
		$result = $this->getResult();

		if($result === false) {
			throw new \RuntimeException("Could not retrieve plugins list from Poggit");
		}

		OhMyPMMP::getInstance()->setPluginsList($result);
		OhMyPMMP::getInstance()->getLogger()->info(TextFormat::GREEN . "Plugins list has been cached. You can install plugin now");
	}
}