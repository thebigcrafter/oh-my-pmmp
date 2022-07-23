<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\commands\subcommands\InstallCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\ListCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\RemoveCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\ShowCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\UpdateCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\UpgradeCommand;
use thebigcrafter\OhMyPMMP\commands\subcommands\VersionCommand;

class OMPCommand extends BaseCommand
{
	/**
	 * @param array<string> $args
	 */
	public function onRun(
		CommandSender $sender,
		string $aliasUsed,
		array $args,
	): void {
		$this->sendUsage();
	}

	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.cmds");

		$subcommands = [
			new VersionCommand("version", "Get plugin version", [
				"v",
				"-v",
				"--version",
			]),
			new InstallCommand("install", "Install a plugin", [
				"i",
				"-i",
				"--install",
			]),
			new UpdateCommand("update", "Update cached data", [
				"ud",
				"-ud",
				"--update",
			]),
			new RemoveCommand("remove", "Remove a plugin", [
				"r",
				"-r",
				"--remove",
			]),
			new ListCommand("list", "List all available plugins", [
				"l",
				"-l",
				"--list",
			]),
			new ShowCommand("show", "Get details of a plugin", [
				"s",
				"-s",
				"--show",
			]),
			new UpgradeCommand("upgrade", "Upgrade a plugin", [
				"u",
				"-u",
				"--upgrade",
			]),
		];

		foreach ($subcommands as $subcommand) {
			$this->registerSubcommand($subcommand);
		}
	}
}
