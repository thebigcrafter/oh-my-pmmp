<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\utils\InternetException;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\AsyncTasks;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\async\Internet;
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
			$version = "0.0.0";

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

		Internet::fetch($downloadURL . "/$name.phar")->then(function($raw) use ($name) {
			Filesystem::writeFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $name . ".phar", $raw)->done(function() {
				return true;
			}, function(\Throwable $e) {
                OhMyPMMP::getInstance()->getLogger()->error(TextFormat::RED . $e->getMessage());
            });
		}, function (InternetException $error) {
			OhMyPMMP::getInstance()->getLogger()->error(TextFormat::RED . "Could not download plugin: " . $error->getMessage());
		});

		return true;
	}
}