<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\Installer;

class UpgradePluginCommand extends Command implements PluginOwned {

	private string $name = "upgrade";
	private string $des = "Upgrade a plugin";

	public function __construct() {
		parent::__construct($this->name, $this->des, null, []);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($commandLabel == $this->name) {

			if(OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
				$sender->sendMessage(TextFormat::RED . 'Cache Poggit Plugins task is running! Please wait until it is finished.');
				return;
			}

			if($sender instanceof Player) {
				$sender->sendMessage(TextFormat::RED . 'This command can only be used in console');
				return;
			}

			if(!isset($args[0])) {
				$sender->sendMessage(TextFormat::RED . "Usage: /upgrade <plugin>");
				return;
			}

			$plugin = $args[0];

			if(Installer::install($plugin)) {
				$sender->sendMessage(TextFormat::GREEN . "Plugin $plugin " . TextFormat::GREEN . "upgraded successfully");
			} else {
				$sender->sendMessage(TextFormat::RED . "Plugin $plugin " . TextFormat::RED . "not found");
			}
		}
	}

	public function getOwningPlugin(): Plugin
	{
		return OhMyPMMP::getInstance();
	}
}