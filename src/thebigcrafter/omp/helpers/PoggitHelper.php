<?php

namespace thebigcrafter\omp\helpers;

use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\types\Plugin;

class PoggitHelper
{
	/**
	 * Check if the plugin exists
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function pluginExist(string $name): bool {
		if(PoggitPluginsPool::getItem($name) === null) {
			return false;
		}
		return true;
	}

	/**
	 * Check if the plugin of the plugin exists
	 *
	 * @param string $name
	 * @param string $version
	 * @return bool
	 */
	public static function versionExist(string $name, string $version): bool {
		$plugin = PoggitPluginsPool::getItem($name);

		if($plugin === null) {
			return false;
		}

		if($plugin->getVersion($version)["plugin"] === null) return false;

		return true;
	}
}