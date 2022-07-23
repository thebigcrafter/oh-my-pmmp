<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use React\Promise\Promise;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

use function str_replace;

class ShowCommand extends BaseSubCommand
{
	// Usage /omp show XPShop 1.0.0

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare(): void
	{
		$this->setPermission("oh-my-pmmp.show");

		$this->registerArgument(0, new RawStringArgument("pluginName", false));
		$this->registerArgument(
			1,
			new RawStringArgument("pluginVersion", false),
		);
	}
	/**
	 * @param array<string> $args
	 */
	public function onRun(
		CommandSender $sender,
		string $aliasUsed,
		array $args,
	): void {
		if (OhMyPMMP::getInstance()->isCachePoggitPluginsTaskRunning) {
			$sender->sendMessage(
				OhMyPMMP::getInstance()
					->getLanguage()
					->translateString("cache.running"),
			);
			return;
		}

		$pluginInfo = "";

		foreach (OhMyPMMP::getInstance()->getPluginsList() as $plugin) {
			if (
				$args["pluginName"] == $plugin["name"] &&
				$args["pluginVersion"] == $plugin["version"]
			) {
				$pluginInfo = $plugin;
			}
		}

		if (empty($pluginInfo)) {
			$sender->sendMessage(
				str_replace(
					"{{plugin}}",
					$args["pluginName"],
					OhMyPMMP::getInstance()
						->getLanguage()
						->translateString("plugin.not.found"),
				),
			);
			return;
		}

		$pluginName = $args["pluginName"];
		$pluginVersion = $args["pluginVersion"];
		$pluginHomepage = $pluginInfo["homepage"];
		$pluginDownloads = $pluginInfo["downloads"];
		$pluginScore = $pluginInfo["score"];
		$pluginLicense = $pluginInfo["license"];
		$pluginAPI = $pluginInfo["api"];
		$pluginDeps = (array)$pluginInfo["deps"];

		$deps = array_map(function ($item) {
			return $item["name"] . " v" . $item["version"];
		}, $pluginDeps);
		$deps = implode(", ", $deps ?? []);
		$deps = ($deps == "") ? "[]" : $deps;
		/** @var Promise $RemoteFilesize */
		$RemoteFilesize = Internet::getRemoteFilesize($pluginInfo["artifact_url"]);
		$RemoteFilesize->done(
			function (string $size) use (
				$pluginScore,
				$pluginDownloads,
				$pluginHomepage,
				$pluginLicense,
				$sender,
				$pluginName,
				$pluginVersion,
				$deps,
				$pluginAPI,
			) {
				$sender->sendMessage(
					"Name: $pluginName\nVersion: $pluginVersion\nHomepage: $pluginHomepage\nLicense: $pluginLicense\nDownloads: $pluginDownloads\nScore: $pluginScore\nAPI: " .
						$pluginAPI[0]["from"] .
						" <= PocketMine-MP <= " .
						$pluginAPI[0]["to"] .
						"\nDepends: $deps\nDownload Size: $size",
				);
			},
			function () use ($sender) {
				$sender->sendMessage(
					OhMyPMMP::getInstance()
						->getLanguage()
						->translateString("poggit.api.error"),
				);
			},
		);
	}
}
