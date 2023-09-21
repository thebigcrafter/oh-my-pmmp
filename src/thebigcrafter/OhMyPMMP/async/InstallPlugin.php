<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use Closure;
use Generator;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use SOFe\AwaitGenerator\Await;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\utils\Filesystem;
use thebigcrafter\OhMyPMMP\utils\Internet;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function is_null;
use const DIRECTORY_SEPARATOR;

class InstallPlugin extends PluginAction {

	private string $pluginVersion;
	private bool $extract;

	public function __construct(CommandSender $sender, string $pluginName, string $pluginVersion, bool $silent = false, bool $extract = false, ?Closure $onSuccess = null, ?Closure $onFail = null) {
		parent::__construct($sender, $pluginName, $silent, $onSuccess, $onFail);
		$this->pluginVersion = $pluginVersion;
		$this->extract = $extract;
	}

	public function getPluginVersion() : string {
		return $this->pluginVersion;
	}

	public function isExtract() : bool {
		return $this->extract;
	}

	public function execute() : void {
		$pluginName = $this->getPluginName();
		$pluginVersion = $this->getPluginVersion();
		$plugin = PluginsPool::getPluginCacheByName($pluginName);

		if(!$plugin) {
			$this->getCommandSender()->sendMessage(Utils::translate("plugin.not.found", ["plugin" => $pluginName]));
			return;
		}

		$version = $plugin->getVersion($pluginVersion);

		if (!$version) {
			$this->getCommandSender()->sendMessage(Utils::translate("plugin.version.not.found", [
				"plugin" => $pluginName,
				"version" => $pluginVersion
			]));
			return;
		}

		if(!Utils::compareVersion($plugin, $pluginVersion)) {
			$serverAPI = Server::getInstance()->getApiVersion();
			/** @var null|array{from: string, to: string} $versionAPI */
			$versionAPI = $plugin->getVersion($pluginVersion)?->getAPI();

			if(is_null($versionAPI)) {
				return;
			}

			$pluginAPI = "{$versionAPI["from"]} -> {$versionAPI["to"]}";
			$warningMessage = Utils::translate("version.not.compare.content", ["plugin" => $pluginName, "serverAPI" => $serverAPI, "pluginAPI" => $pluginAPI]);
			if(!$this->isSilent()) {
				Server::getInstance()->getLogger()->warning($warningMessage);
			}
		}

		Await::g2c($this->installPlugin($version->getArtifactUrl(), $this->getPluginName()));
	}

	private function installPlugin(string $downloadURL, string $pluginName) : Generator {
		$url = "$downloadURL/$pluginName.phar";

		$response = yield from Internet::awaitFetch($url);

		$pharPath = Utils::getPluginsFolder() . DIRECTORY_SEPARATOR . "$pluginName.phar";

		$writeable = yield from Filesystem::awaitWrite($pharPath, $response);

		if ($writeable) {
			if (!$this->isSilent()) {
				$this->getCommandSender()->sendMessage(Utils::translate("plugin.installed", ["plugin" => $pluginName]));
			}

			if ($this->isExtract()) {
				yield $this->extractPlugin($pharPath, $pluginName);
			} else {
				$this->onSuccess();
			}
		}
	}

	private function extractPlugin(string $pharPath, string $pluginName) : Generator {
		$this->getCommandSender()->sendMessage(Utils::translate("plugin.extracting"));

		$extracted = yield from Filesystem::awaitExtractPhar(
			$pharPath,
			Utils::getPluginsFolder() . DIRECTORY_SEPARATOR . $pluginName
		);

		if ($extracted && yield from Filesystem::awaitUnlinkPhar($pharPath)) {
			$this->getCommandSender()->sendMessage(Utils::translate("plugin.extracted"));
		}
		$this->onSuccess();
	}
}
