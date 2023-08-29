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
use thebigcrafter\OhMyPMMP\tasks\RemovePluginTask;

class RemoveCommand extends BaseSubCommand {

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender,string $aliasUsed,array $args) : void {
		$plugin = $args["pluginName"];

		OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new RemovePluginTask($sender, $plugin));
	}

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.remove");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
	}
}
