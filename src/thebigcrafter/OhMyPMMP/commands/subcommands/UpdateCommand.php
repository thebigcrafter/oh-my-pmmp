<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <thebigcrafterteam@proton.me>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\CachePoggitPlugins;

class UpdateCommand extends BaseSubCommand
{
	/**
	 * @param array<string> $args
	 */
	public function onRun(
		CommandSender $sender,
		string $aliasUsed,
		array $args,
	) : void {
		if (OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
			$sender->sendMessage(
				OhMyPMMP::getInstance()
					->getLanguage()
					->translateString("cache.running"),
			);
			return;
		}

		OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning = true;
		OhMyPMMP::getInstance()
			->getServer()
			->getAsyncPool()
			->submitTask(new CachePoggitPlugins());
	}

	protected function prepare() : void
	{
		$this->setPermission("oh-my-pmmp.update");
	}
}
