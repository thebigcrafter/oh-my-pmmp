<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class CachePoggitPlugins extends AsyncTask {

	public function onRun(): void
	{
		$pluginsList = [];

		$res = Internet::getURL("https://poggit.pmmp.io/releases.json")->getBody();

		$json = json_decode($res, true);

		foreach ($json as $plugin) {
			$pluginsList[] = ["name" => $plugin["name"], "version" => $plugin["version"], "download_url" => $plugin["artifact_url"]];
		}

		$this->setResult($pluginsList);
	}

	public function onCompletion(): void
	{
		OhMyPMMP::getInstance()->setPluginsList($this->getResult());
	}
}