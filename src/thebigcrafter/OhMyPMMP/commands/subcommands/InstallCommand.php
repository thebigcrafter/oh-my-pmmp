<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <thebigcrafterteam@proton.me>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\InstallPluginTask;

class InstallCommand extends BaseSubCommand {
	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
			$sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("cache.running"));
			return;
		}

		$pluginName = $args["pluginName"];

		if (!isset($args["extract"])) {
			OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new InstallPluginTask($sender, $pluginName, $args["pluginVersion"]));
		} else {
			if ($args["extract"] == "true") {
				OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new InstallPluginTask($sender, $pluginName, $args["pluginVersion"], false, true));
			} else {
				OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new InstallPluginTask($sender, $pluginName, $args["pluginVersion"]));
			}
		}
	}

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.install");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
		$this->registerArgument(1, new RawStringArgument("pluginVersion"));

		if (OhMyPMMP::getInstance()->getConfig()->get("devMode") === true) {
			$this->registerArgument(2, new BooleanArgument("extract", true));
		}
	}
}
