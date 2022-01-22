<?php

declare(strict_types=1);

namespace MintoD\OhMyPMMP\commands;

use MintoD\OhMyPMMP\OhMyPMMP;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class RemovePluginCommand extends Command {
    private string $name = 'remove';
    private string $des = 'Remove a plugin';

    public function __construct() {
        parent::__construct($this->name, $this->des, null, []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($commandLabel == $this->name) {

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
}