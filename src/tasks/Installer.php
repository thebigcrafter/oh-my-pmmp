<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\utils\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class Installer {

	public static function install(string $name, string $version = "latest"): bool {
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
					$downloadURL = $plugin["download_url"];
				}
			}
		} else {
			$version = "0.0.1";

			foreach ($pluginsList as $plugin) {
				if(version_compare($plugin["version"], $version, ">")) {
					$version = $plugin["version"];
					$downloadURL = $plugin["download_url"];
				}
			}
		}

		if(empty($downloadURL)) {
			return false;
		}

		$raw = Internet::getURL($downloadURL . "/$name.phar")->getBody();
		file_put_contents(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $name . ".phar", $raw);

		return true;
	}
}