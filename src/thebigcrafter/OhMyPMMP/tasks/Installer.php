<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\utils\InternetException;
use thebigcrafter\OhMyPMMP\async\AsyncTasks;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class Installer {

	public static function install(string $name, string $version): bool {
		$pluginsList = [];
		$downloadURL = "";

		foreach (OhMyPMMP::getInstance()->getPluginsList() as $plugin) {
			if($plugin["name"] == $name) {
				$pluginsList[] = $plugin;
			}
		}

		if($version != "latest") {
			foreach ($pluginsList as $plugin) {
				if($plugin["version"] == $version) {
					$downloadURL = $plugin["artifact_url"];
				}
			}
		} else{
			$version = "0.0.1";

			foreach ($pluginsList as $plugin) {
				if(version_compare($plugin["version"], $version, ">")) {
					$version = $plugin["version"];
					$downloadURL = $plugin["artifact_url"];
				}
			}
		}

		if(empty($downloadURL)) {
			return false;
		}

		AsyncTasks::fetch($downloadURL . "/$name.phar")->then(function($raw) use ($name) {
			AsyncTasks::writeFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $name . ".phar", $raw)->then(function() {
				return true;
			});
		}, function (InternetException $error) {
			OhMyPMMP::getInstance()->getLogger()->error("Could not download plugin: " . $error->getMessage());
		});

		return true;
	}
}