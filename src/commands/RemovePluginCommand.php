<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class RemovePluginCommand extends Command implements PluginOwned {
	private string $name = 'remove';
	private string $des = 'Remove a plugin';

	public function __construct() {
		parent::__construct($this->name, $this->des, null, []);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($commandLabel == $this->name) {

			if(!$sender->hasPermission('ohmypmmp.remove.cmd')) {
				$sender->sendMessage('§cYou do not have permission to use this command');
				return;
			}

			if(!isset($args[0])) {
				$sender->sendMessage("§cUsage: /remove <plugin>");
				return;
			}

			$plugin = $args[0];

			if(is_file(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$plugin.phar")) {
				unlink(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$plugin.phar");
				$sender->sendMessage("§aPlugin $plugin has been removed");
			} else {
				$sender->sendMessage("§cPlugin $plugin not found");
			}
		}
	}

	public function getOwningPlugin(): Plugin
	{
		return OhMyPMMP::getInstance();
	}
}