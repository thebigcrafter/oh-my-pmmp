<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

use function phpversion;
use function str_replace;

class VersionCommand extends BaseSubCommand
{
	/**
	 * @param array<string> $args
	 */
	public function onRun(
		CommandSender $sender,
		string $aliasUsed,
		array $args,
	): void {
		$phpVersion = phpversion();
		$pluginVersion = OhMyPMMP::getInstance()
			->getDescription()
			->getVersion();

		$sender->sendMessage(
			str_replace(
				"{{version}}",
				$phpVersion,
				OhMyPMMP::getInstance()
					->getLanguage()
					->translateString("version.php"),
			),
		);
		$sender->sendMessage(
			str_replace(
				"{{version}}",
				$pluginVersion,
				OhMyPMMP::getInstance()
					->getLanguage()
					->translateString("version.ohmypmmp"),
			),
		);
	}

	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.version");
	}
}
