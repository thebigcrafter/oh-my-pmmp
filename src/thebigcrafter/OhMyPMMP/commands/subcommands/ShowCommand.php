<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use Exception;
use pocketmine\command\CommandSender;
use thebigcrafter\OhMyPMMP\async\CachePlugins;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\utils\Internet;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function array_map;
use function implode;

class ShowCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->setPermission("oh-my-pmmp.show");

		$this->registerArgument(0, new RawStringArgument("pluginName"));
		$this->registerArgument(1, new RawStringArgument("pluginVersion", true));
	}

	/**
	 * @param array<string> $args
	 * @throws Exception
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!CachePlugins::hasCached()) {
			$sender->sendMessage(Utils::translate("cache.running"));
			return;
		}

		$pluginName = $args["pluginName"];
		$pluginVersion = $args["pluginVersion"] ?? "latest";

		$plugin = PluginsPool::getPluginCacheByName($pluginName);

		if(!$plugin) {
			$sender->sendMessage(Utils::translate("plugin.not.found", ["plugin" => $pluginName]));
			return;
		}

		$version = $plugin->getVersion($pluginVersion);
		if (!$version) {
			$sender->sendMessage(Utils::translate("plugin.version.not.found", [
				"plugin" => $pluginName,
				"version" => $pluginVersion
			]));
			return;
		}

		$pluginName = $plugin->getName();
		$pluginVersion = $version->getVersion();
		$pluginHomepage = $plugin->getHomePageByVersion($pluginVersion);
		$pluginLicense = $plugin->getLicense();
		$pluginDownloads = $plugin->getDownloads();
		$pluginScore = $plugin->getScore();
		$deps = array_map(function ($item) {
			/** @var array<string> $item */
			return $item["name"] . " v" . $item["version"];
		}, $version->getDepends());
		$deps = implode(", ", $deps);
		$size = Internet::fetchRemoteFileSize($version->getArtifactUrl());
		$pluginAPI = $version->getAPI();
		$sender->sendMessage("Name: $pluginName\nVersion: $pluginVersion\nHomepage: $pluginHomepage\nLicense: $pluginLicense\nDownloads: $pluginDownloads\nScore: $pluginScore\nAPI: " . $pluginAPI["from"] . " <= PocketMine-MP <= " . $pluginAPI["to"] . "\nDepends: $deps\nDownload Size: $size");
	}
}
