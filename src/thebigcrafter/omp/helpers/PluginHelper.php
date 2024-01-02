<?php

/*
 * This file is part of oh-my-pmmp.
 *
 * (c) thebigcrafter <hello@thebigcrafter.team>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\omp\helpers;

use Closure;
use Exception;
use Generator;
use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\types\PluginType;
use thebigcrafter\omp\utils\Filesystem;
use thebigcrafter\omp\utils\Utils;

class PluginHelper
{
	/**
	 * Remove a plugin by it name and remove it data if it is required. Return true if succeed
	 * This function will check if the plugin Phar file exist or not, if it does, remove it.
	 * Else if the plugin folder exists, remove it
	 * If plugin not found, throw an Exception
	 */
	public static function remove(string $name, bool $wipeData): Generator
	{
		return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($name, $wipeData) {
			$pluginFilePath = Utils::generatePluginFilePathWithName($name);
			$pluginFolderPath = Utils::generatePluginFolderPathWithName($name);

			if (Filesystem::exists($pluginFilePath)) {
				Await::g2c(Filesystem::remove($pluginFilePath));
			} elseif (Filesystem::exists($pluginFolderPath)) {
				Await::g2c(Filesystem::remove($pluginFolderPath));
			} else {
				$reject(new Exception("Plugin not found"));
			}
			if ($wipeData) {
				$pluginDataFolder = Path::join(OhMyPMMP::getInstance()->getDataFolder(), "..", $name);
				Await::g2c(Filesystem::remove($pluginDataFolder));
			}
			$resolve(true);
		});
	}

	/**
	 * Return true if plugin exists, false on failure
	 * This function can check plugin Phar file and plugin folder
	 */
	public static function exists(string $name, PluginType $type = PluginType::FILE_TYPE): bool
	{
		if ($type === PluginType::FILE_TYPE) {
			return Filesystem::exists(Utils::generatePluginFilePathWithName($name));
		}

		return Filesystem::exists(Utils::generatePluginFolderPathWithName($name));
	}
}
