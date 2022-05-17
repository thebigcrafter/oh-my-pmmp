<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\Installer;

class UpgradePluginCommand extends Command implements PluginOwned {

	private string $name = 'upgrade';
	private string $des = 'Upgrade a plugin';

	public function __construct() {
		parent::__construct($this->name, $this->des, null, []);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($commandLabel == $this->name) {

			if(!$sender->hasPermission('ohmypmmp.upgrade.cmd')) {
				$sender->sendMessage('§cYou do not have permission to use this command');
				return;
			}

			if(!isset($args[0])) {
				$sender->sendMessage("§cUsage: /upgrade <plugin>");
				return;
			}

			$plugin = $args[0];

			if(Installer::install($plugin)) {
				$sender->sendMessage("§aPlugin $plugin §aupgraded successfully");
			} else {
				$sender->sendMessage("§cPlugin $plugin §cnot found");
			}
		}
	}

	public function getOwningPlugin(): Plugin
	{
		return OhMyPMMP::getInstance();
	}
}