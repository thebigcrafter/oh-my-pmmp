<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function ceil;
use function count;
use function implode;
use function iterator_count;
use function iterator_to_array;
use function max;

class ListCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.list");

		$this->registerArgument(0, new IntegerArgument("page", true));
		$this->registerArgument(0, new TextArgument("--all", true));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

		$pluginsGenerator = PluginsPool::getNamePlugins();

		if(isset($args["--all"])) {
			$allPlugins = iterator_to_array($pluginsGenerator);
			$sender->sendMessage(Utils::translate("plugins.list.content", ["count" => count($allPlugins), "plugins" => implode(", ", $allPlugins)]));
			return;
		}

		$pluginsPerPage = 10;

		$page = 1;

		if (isset($args["page"])) {
			$page = $args["page"];
		}

		$currentPage = max(1, (int) $page);
		$startIndex = ($currentPage - 1) * $pluginsPerPage;
		$endIndex = $startIndex + $pluginsPerPage;

		$currentPlugins = [];
		$currentIndex = 0;
		foreach ($pluginsGenerator as $pluginName) {
			if ($currentIndex >= $startIndex && $currentIndex < $endIndex) {
				$currentPlugins[] = $pluginName;
			}
			$currentIndex++;
		}

		$totalPlugins = iterator_count(PluginsPool::getNamePlugins());
		$maxPage = ceil($totalPlugins / $pluginsPerPage);

		$sender->sendMessage(Utils::translate("plugins.list.title", ["page" => $currentPage, "maxpage" => $maxPage]));
		$sender->sendMessage(Utils::translate("plugins.list.content", ["count" => count($currentPlugins), "plugins" => implode(", ", $currentPlugins)]));
	}

}
