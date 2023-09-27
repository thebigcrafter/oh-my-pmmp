<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use thebigcrafter\OhMyPMMP\cache\PluginCache;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use function is_null;
use function preg_match;
use function strtoupper;
use function substr;
use function version_compare;
use const DIRECTORY_SEPARATOR;

class Utils {

	public static function validatePluginName(string $pluginName) : bool {
		if(preg_match('#\.\./#', $pluginName)) {
			return false;
		}
		return true;
	}

	public static function getPluginsFolder() : string {
		return OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins" . DIRECTORY_SEPARATOR;
	}

	/**
	 * @param (float|int|string|Translatable)[] $params
	 */
	public static function translate(string $key, array $params = []) : string {
		$language = OhMyPMMP::getInstance()->getLanguage();
		return $language->translateString($key, $params);
	}

	public static function getPlugin(string $pluginName) : ?Plugin {
		$pluginManager = OhMyPMMP::getInstance()->getServer()->getPluginManager();
		return $pluginManager->getPlugin($pluginName);
	}

	/**
	 * @return array<string, string[]>
	 */
	public static function groupByFirstLetter() : array {
		$groups = [];
		foreach (PluginsPool::getNamePlugins() as $pluginName) {
			/** @phpstan-var string $pluginName */
			$firstChar = strtoupper(substr($pluginName, 0, 1));
			$groups[strtoupper($firstChar)][] = $pluginName;
		}
		return $groups;
	}

	public static function compareVersion(PluginCache $plugin, string $version) : bool {
		$serverAPI = Server::getInstance()->getApiVersion();
		/** @var null|array{from: string, to: string} $versionAPI */
		$versionAPI = $plugin->getVersion($version)?->getAPI();
		if(is_null($versionAPI)) {
			return false;
		}
		return version_compare($versionAPI["from"], $serverAPI, ">=");
	}
}
