<?php

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
	): void {
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

	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.update");
	}
}
