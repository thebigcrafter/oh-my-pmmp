<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function count;
use function implode;
use function in_array;

class ListCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.list");

		$this->registerArgument(0, new RawStringArgument("installedPlugins", true));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (empty($args["installedPlugins"])) {

			$plugins = [];

			foreach(PluginsPool::getNamePlugins() as $pluginName) {
				$plugins[] = $pluginName;
			}

			$sender->sendMessage(Utils::translate("plugins.list", [
				"count" => count($plugins),
				"plugins" => implode(", ", $plugins)
			]));

		//Why not use `/plugins` instead? @NhanAZ
		} elseif (in_array($args["installedPlugins"], ["i", "-installed", "--installed"], true)) {
			foreach (OhMyPMMP::getInstance()->getServer()->getPluginManager()->getPlugins() as $plugin) {
				$sender->sendMessage($plugin->getName());
			}
		} else {
			$sender->sendMessage(TextFormat::RED . "Invalid argument");
		}
	}
}
