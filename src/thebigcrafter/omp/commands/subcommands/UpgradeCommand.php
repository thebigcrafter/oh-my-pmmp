<?php

namespace thebigcrafter\omp\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Exception;
use Generator;
use PluginType;
use pocketmine\command\CommandSender;
use SOFe\AwaitGenerator\Await;
use thebigcrafter\omp\helpers\PluginHelper;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;

class UpgradeCommand extends BaseSubCommand
{
	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.upgrade");

		$this->registerArgument(0, new RawStringArgument("name", false));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		$name = $args["name"];

		Await::f2c(function () use ($name, $sender): Generator {
			try {
				yield from PluginHelper::remove($name, false);
			} catch (Exception $e) {
				$sender->sendMessage(Language::translate("commands.upgrade.failed", ["name" => $name]));
			}
		});
		OhMyPMMP::getInstance()->getServer()->dispatchCommand($sender, "omp i $name");
		return;
	}
}