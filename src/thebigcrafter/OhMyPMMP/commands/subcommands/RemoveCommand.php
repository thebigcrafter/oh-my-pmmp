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
use thebigcrafter\OhMyPMMP\tasks\RemovePluginTask;

class RemoveCommand extends BaseSubCommand
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
            $sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("command.only.console"));
            return;
        }

        $plugin = $args["pluginName"];

        OhMyPMMP::getInstance()->getScheduler()->scheduleTask(new RemovePluginTask($sender, $plugin));
    }

    /**
     * @return void
     * @throws ArgumentOrderException
     *
     */
    protected function prepare(): void
    {
        $this->setPermission("oh-my-pmmp.remove");

        $this->registerArgument(0, new RawStringArgument("pluginName"));
    }
}