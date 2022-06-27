<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\Installer;
use thebigcrafter\OhMyPMMP\tasks\InstallPluginTask;
use thebigcrafter\OhMyPMMP\tasks\RemovePluginTask;

class UpdateCommand extends BaseSubCommand
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
        if (OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
            $sender->sendMessage(TextFormat::RED . 'Cache Poggit Plugins task is running! Please wait until it is finished.');
            return;
        }

        $pluginName = $args["pluginName"];

        OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new RemovePluginTask($sender, $pluginName, true));
        OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new InstallPluginTask($sender, $pluginName, "latest", true));

        $sender->sendMessage(TextFormat::GREEN . "$pluginName has been updated!");
    }

    /**
     * @return void
     * @throws ArgumentOrderException
     *
     */
    protected function prepare(): void
    {
        $this->setPermission("oh-my-pmmp.update");

        $this->registerArgument(0, new RawStringArgument("pluginName"));
    }
}