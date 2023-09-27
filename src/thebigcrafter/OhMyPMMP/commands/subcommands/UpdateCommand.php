<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\async\CachePlugins;
use thebigcrafter\OhMyPMMP\utils\Utils;

class UpdateCommand extends BaseSubCommand {

	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.update");
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!CachePlugins::hasCached()) {
			$sender->sendMessage(Utils::translate("cache.running"));
			return;
		}

		CachePlugins::setHasCached(false);
		CachePlugins::cachePlugins();
	}
}
