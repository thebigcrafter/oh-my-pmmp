<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\InstallPluginTask;
use thebigcrafter\OhMyPMMP\tasks\RemovePluginTask;

class UpgradeCommand extends BaseSubCommand
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

		$pluginName = $args["pluginName"];

		OhMyPMMP::getInstance()
			->getScheduler()
			->scheduleTask(new RemovePluginTask($sender, $pluginName, true));
		OhMyPMMP::getInstance()
			->getScheduler()
			->scheduleTask(
				new InstallPluginTask($sender, $pluginName, "latest", true),
			);

		$sender->sendMessage(
			str_replace(
				"{{plugin}}",
				$pluginName,
				OhMyPMMP::getInstance()
					->getLanguage()
					->translateString("plugin.updated"),
			),
		);
	}

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.upgrade");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
	}
}
