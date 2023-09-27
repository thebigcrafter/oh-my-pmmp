<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\async\CachePlugins;
use thebigcrafter\OhMyPMMP\async\InstallPlugin;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\utils\Utils;

class InstallCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.install");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
		$this->registerArgument(1, new RawStringArgument("pluginVersion", true));

		if (OhMyPMMP::getInstance()->getConfig()->get("devMode") === true) {
			$this->registerArgument(2, new BooleanArgument("extract", true));
		}
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!CachePlugins::hasCached()) {
			$sender->sendMessage(Utils::translate("cache.running"));
			return;
		}

		$pluginName = $args["pluginName"];
		$pluginVersion = $args["pluginVersion"] ?? "latest";
		$extract = isset($args["extract"]) && $args["extract"] === "true";

		$installAction = new InstallPlugin($sender, $pluginName, $pluginVersion, false, $extract);
		$installAction->execute();
	}

}
