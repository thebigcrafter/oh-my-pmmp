<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginDescription;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;

class VersionCommand extends BaseSubCommand
{
    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param string[] $args
     *
     * @return void
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $phpVersion = phpversion();
        $pluginVersion = OhMyPMMP::getInstance()->getDescription()->getVersion();

        $sender->sendMessage("PHP version $phpVersion");
        $sender->sendMessage("OhMyPMMP version $pluginVersion");
    }

    /**
     * @return void
     */
    protected function prepare(): void
    {
        $this->setPermission("oh-my-pmmp.version");
    }
}