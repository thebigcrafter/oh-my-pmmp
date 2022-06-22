<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\AsyncTasks;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

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
            $sender->sendMessage(TextFormat::RED . 'This command can only be used in console');
            return;
        }

        $plugin = $args["pluginName"];

        AsyncTasks::deleteFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$plugin.phar")->then(function () use ($plugin, $sender) {
            $sender->sendMessage(TextFormat::GREEN . "Plugin $plugin " . TextFormat::GREEN . "has been removed");
        }, function () use ($plugin, $sender) {
            $sender->sendMessage(TextFormat::RED . "Plugin $plugin " . TextFormat::RED . "not found");
        });
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