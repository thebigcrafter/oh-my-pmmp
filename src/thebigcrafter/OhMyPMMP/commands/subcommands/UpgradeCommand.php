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
use thebigcrafter\OhMyPMMP\async\CachePlugins;
use thebigcrafter\OhMyPMMP\async\InstallPlugin;
use thebigcrafter\OhMyPMMP\async\RemovePlugin;
use thebigcrafter\OhMyPMMP\utils\Utils;

class UpgradeCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.upgrade");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!CachePlugins::hasCached()) {
			$sender->sendMessage(Utils::translate("cache.running"));
			return;
		}

		$pluginName = $args["pluginName"];

		if(!Utils::validatePluginName($pluginName)) {
			$sender->sendMessage(Utils::translate("plugin.name.invalid"));
			return;
		}

		$updateCallback = function() use ($pluginName, $sender) {
			$sender->sendMessage(Utils::translate("plugin.updated", ["plugin" => $pluginName]));
		};

		$removeAction = new RemovePlugin($sender, $pluginName, true, function() use ($pluginName, $sender, $updateCallback) {
			$installAction = new InstallPlugin($sender, $pluginName, "latest", true, false, $updateCallback);
			$installAction->execute();
		});

		$removeAction->execute();
	}
}
