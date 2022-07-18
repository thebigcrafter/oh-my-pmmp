<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

use function in_array;
use function array_unique;

class ListCommand extends BaseSubCommand {
	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.list");

		$this->registerArgument(0, new RawStringArgument("installedPlugins", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(empty($args["installedPlugins"])) {
			$pluginsName = [];

			foreach (OhMyPMMP::getInstance()->getPluginsList() as $plugin) {
				$pluginsName[] = $plugin["name"];
			}

			foreach(array_unique($pluginsName) as $name) {
				$sender->sendMessage($name);
			}
		} elseif(in_array($args["installedPlugins"], ["i", "-installed", "--installed"], true)) {
			foreach (OhMyPMMP::getInstance()->getServer()->getPluginManager()->getPlugins() as $plugin) {
				$sender->sendMessage($plugin->getName());
			}
		} else {
			$sender->sendMessage(TextFormat::RED . "Invalid argument");
		}
	}
}