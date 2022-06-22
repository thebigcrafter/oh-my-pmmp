<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\tasks\Installer;

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
        if ($sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command is only available in console.");
            return;
        }

        if (OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
            $sender->sendMessage(TextFormat::RED . 'Cache Poggit Plugins task is running! Please wait until it is finished.');
            return;
        }

        $pluginName = $args["pluginName"];

        if (Installer::install($pluginName, "latest")) {
            $sender->sendMessage(TextFormat::GREEN . "Plugin $pluginName " . TextFormat::GREEN . " was updated successfully");
        } else {
            $sender->sendMessage(TextFormat::RED . "Plugin $pluginName " . TextFormat::RED . "not found");
        }
    }

    /**
     * @return void
     * @throws ArgumentOrderException
     *
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("pluginName"));
    }
}