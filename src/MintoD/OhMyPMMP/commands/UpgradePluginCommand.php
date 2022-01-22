<?php

declare(strict_types=1);

namespace MintoD\OhMyPMMP\commands;

use MintoD\OhMyPMMP\tasks\Installer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UpgradePluginCommand extends Command {

    private string $name = 'upgrade';
    private string $des = 'Upgrade a plugin';

    public function __construct() {
        parent::__construct($this->name, $this->des, null, []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($commandLabel == $this->name) {

            if(!$sender->hasPermission('upgrade.cmd')) {
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
}