<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\Installer;
use thebigcrafter\OhMyPMMP\tasks\InstallPluginTask;

class InstallCommand extends BaseSubCommand
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
        if($sender instanceof Player) {
            $sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("command.only.console"));
            return;
        }

        if(OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
            $sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("cache.running"));
            return;
        }

        $pluginName = $args["pluginName"];

        OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new InstallPluginTask($sender, $pluginName, $args["pluginVersion"]));
    }

    /**
     * @throws ArgumentOrderException
     *
     * @return void
     */
    protected function prepare(): void {
        $this->setPermission("oh-my-pmmp.install");

        $this->registerArgument(0, new RawStringArgument("pluginName"));
        $this->registerArgument(1, new RawStringArgument("pluginVersion"));
    }
}