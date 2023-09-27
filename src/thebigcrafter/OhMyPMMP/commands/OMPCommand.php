<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;
use thebigcrafter\OhMyPMMP\async\AsyncForm;
use thebigcrafter\OhMyPMMP\commands\subcommands\InstallCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\ListCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\RemoveCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\ShowCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\UpdateCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\UpgradeCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\VersionCommand;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class OMPCommand extends BaseCommand {

	private string $permission = "oh-my-pmmp.cmds";

	protected function prepare() : void {
		$this->setPermission($this->permission);

		$plugin = OhMyPMMP::getInstance();
		$subcommands = [
			new VersionCommand($plugin, "version", "Get plugin version", ["v", "-v", "--version"]),
			new InstallCommand($plugin, "install", "Install a plugin", ["i", "-i", "--install"]),
			new UpdateCommand($plugin, "update", "Update cached data", ["ud", "-ud", "--update"]),
			new RemoveCommand($plugin, "remove", "Remove a plugin", ["r", "-r", "--remove"]),
			new ListCommand($plugin, "list", "List all available plugins", ["l", "-l", "--list"]),
			new ShowCommand($plugin, "show", "Get details of a plugin", ["s", "-s", "--show"]),
			new UpgradeCommand($plugin, "upgrade", "Upgrade a plugin", ["u", "-u", "--upgrade"]),
		];

		foreach ($subcommands as $subcommand) {
			$this->registerSubcommand($subcommand);
		}
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if($sender instanceof Player) {
			Await::g2c(AsyncForm::groupsForm($sender));
			return;
		}
		$this->sendUsage();
	}

	public function getPermission() : string {
		return $this->permission;
	}
}
