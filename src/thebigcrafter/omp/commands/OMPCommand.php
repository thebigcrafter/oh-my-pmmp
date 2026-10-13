<?php

/*
 * This file is part of oh-my-pmmp.
 *
 * (c) thebigcrafter <hello@thebigcrafter.team>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\omp\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\omp\commands\subcommands\DisableCommand;
use thebigcrafter\omp\commands\subcommands\EnableCommand;
use thebigcrafter\omp\commands\subcommands\ExtractCommand;
use thebigcrafter\omp\commands\subcommands\InstallCommand;
use thebigcrafter\omp\commands\subcommands\ListCommand;
use thebigcrafter\omp\commands\subcommands\RemoveCommand;
use thebigcrafter\omp\commands\subcommands\ShowCommand;
use thebigcrafter\omp\commands\subcommands\UpdateCommand;
use thebigcrafter\omp\commands\subcommands\UpgradeCommand;
use thebigcrafter\omp\commands\subcommands\VersionCommand;

class OMPCommand extends BaseCommand
{
    protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.cmds");

        $subcommands = [
            new VersionCommand("version", "Print oh-my-pmmp and PHP version", ["v", "-v", "--version"]),
            new RemoveCommand("remove", "Remove a plugin", ["r", "-r", "--remove"]),
            new ListCommand("list", "List available plugins", ["l", "-l", "--list"]),
            new ShowCommand("show", "Get details about a plugin", ["s", "-s", "--show"]),
            new InstallCommand("install", "Install a plugin", ["i", "-i", "--install"]),
            new ExtractCommand("extract", "Extract a plugin", ["e", "-e", "--extract"]),
            new UpdateCommand("update", "Update fetched data", ["ud", "-ud", "--update"]),
            new EnableCommand("enable", "Enable plugin"),
            new DisableCommand("disable", "Disable plugin"),
            new UpgradeCommand("upgrade", "Upgrade plugin", ["u", "-u", "--upgrade"])
        ];

        foreach ($subcommands as $subcommand) {
            $this->registerSubcommand($subcommand);
        }
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $this->sendUsage();
    }
}
