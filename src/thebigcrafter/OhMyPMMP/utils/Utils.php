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
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use function preg_match;
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
}
