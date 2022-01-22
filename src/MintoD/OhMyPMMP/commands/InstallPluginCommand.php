<?php

declare(strict_types=1);

namespace MintoD\OhMyPMMP\commands;

use MintoD\OhMyPMMP\tasks\Installer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class InstallPluginCommand extends Command {

    private string $name = 'install';
    private string $des = 'Install a plugin';

    public function __construct() {
        parent::__construct($this->name, $this->des, null, []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($commandLabel == $this->name) {

            if(!$sender->hasPermission('install.cmd')) {
                $sender->sendMessage('§cYou do not have permission to use this command');
                return;
            }

            if(!isset($args[0])) {
                $sender->sendMessage("§cUsage: /install <plugin> <version>");
                return;
            }

            $plugin = $args[0];

            if(!isset($args[1])) {
                $version = "latest";
            } else {
                $version = $args[1];
            }

            if(Installer::install($plugin, $version)) {
                $sender->sendMessage("§aPlugin $plugin §ainstalled successfully");
            } else {
                $sender->sendMessage("§cPlugin $plugin §cnot found");
            }
        }
    }
}