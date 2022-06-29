<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\commands\subcommands\HelpCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\InstallCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\RemoveCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\UpdateCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\VersionCommand;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class OMPCommand extends BaseCommand
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

        $this->sendUsage();
    }

    /**
     * @return void
     */
    protected function prepare(): void
    {
        $this->setPermission("oh-my-pmmp.cmds");

        $subcommands = [
            new HelpCommand("help", "List available subcommands", ["h", "-h", "--help"]),
            new VersionCommand("version", "Get plugin version", ["v", "-v", "--version"]),
            new InstallCommand("install", "Install a plugin", ["i", "-i", "--install"]),
            new UpdateCommand("update", "Update a plugin", ["u", "-u", "--update"]),
            new RemoveCommand("remove", "Remove a plugin", ["r", "-r", "--remove"]),
        ];

        foreach ($subcommands as $subcommand) {
            $this->registerSubcommand($subcommand);
        }
    }
}