<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\AsyncTasks;
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

			if($sender instanceof Player) {
				$sender->sendMessage(TextFormat::RED . 'This command can only be used in console');
				return;
			}

			if(!isset($args[0])) {
				$sender->sendMessage(TextFormat::RED . "Usage: /remove <plugin>");
				return;
			}

			$plugin = $args[0];

			AsyncTasks::deleteFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$plugin.phar")->then(function() use ($plugin, $sender) {
				$sender->sendMessage(TextFormat::GREEN . "Plugin $plugin " . TextFormat::GREEN . "has been removed");
			}, function() use ($plugin, $sender) {
				$sender->sendMessage(TextFormat::RED . "Plugin $plugin " . TextFormat::RED . "not found");
			});
		}
	}

	public function getOwningPlugin(): Plugin
	{
		return OhMyPMMP::getInstance();
	}
}