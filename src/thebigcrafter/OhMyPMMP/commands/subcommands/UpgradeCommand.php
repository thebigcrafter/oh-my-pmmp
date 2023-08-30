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
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\InstallPluginTask;
use thebigcrafter\OhMyPMMP\tasks\RemovePluginTask;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function str_replace;

class UpgradeCommand extends BaseSubCommand {

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
			$sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("cache.running"));
			return;
		}

		$pluginName = $args["pluginName"];

		if(!Utils::validatePluginName($pluginName)) {
			$sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.name.invalid"));
			return;
		}

		$removeTask = new RemovePluginTask($sender, $pluginName, true, function() use ($pluginName, $sender) {
			$installTask = new InstallPluginTask($sender, $pluginName, "latest", true, false,
				function() use ($sender, $pluginName) {
				$sender->sendMessage(str_replace("{{plugin}}", $pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.updated")));
			});
			OhMyPMMP::getInstance()->getScheduler()->scheduleTask($installTask);
		});

		OhMyPMMP::getInstance()->getScheduler()->scheduleTask($removeTask);
	}

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.upgrade");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
	}
}
