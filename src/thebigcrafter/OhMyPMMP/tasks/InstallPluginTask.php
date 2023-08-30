<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use Closure;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use Throwable;
use function count;
use function str_replace;
use function version_compare;

class InstallPluginTask extends Task {

	private CommandSender $sender;

	private string $pluginName;

	private string $pluginVersion;

	private bool $silent;

	private bool $extract;

	private ?Closure $onSuccess;

	public function __construct(CommandSender $sender, string $pluginName, string $pluginVersion, bool $silent = false, bool $extract = false, ?Closure $onSuccess = null) {
		$this->sender = $sender;
		$this->pluginName = $pluginName;
		$this->pluginVersion = $pluginVersion;
		$this->silent = $silent;
		$this->extract = $extract;
		$this->onSuccess = $onSuccess;
	}

	public function onRun() : void {
		$pluginsList = [];
		$downloadURL = "";
		$version = "0.0.0";

		foreach (OhMyPMMP::getInstance()->getPluginsList() as $plugin) {
			if ($plugin["name"] == $this->pluginName) {
				$pluginsList[] = $plugin;
			}
		}

		if(count($pluginsList) == 0) {
			$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.not.found")));
			return;
		}

		foreach ($pluginsList as $plugin) {
			if($this->pluginVersion === "latest" || $plugin["version"] == $this->pluginVersion) {
				if(version_compare($plugin["version"], $version, ">")) {
					$version = $plugin["version"];
					$downloadURL = $plugin["artifact_url"];
				}
			}
		}

		if (empty($downloadURL)) {
			if (!$this->silent) {
				$this->sender->sendMessage(str_replace(["{{plugin}}", "{{version}}"], [$this->pluginName, $this->pluginVersion], OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.version.not.found")));
				return;
			}
			return;
		}

		Internet::fetch($downloadURL . "/$this->pluginName.phar")->then(
			function ($raw) {
				$writable = Filesystem::writeFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar", $raw);
				$writable->then(
					function () {
						if (!$this->silent) {
							$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.installed")));
						}

						if ($this->extract) {
							$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.extracting")));

							Filesystem::extractPhar(
								OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar",
								OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$this->pluginName"
							)->then(
								function () {
									Filesystem::unlinkPhar(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar")->then(
										function () {
											$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.extracted")));
										}
									);
								}
							);
						}
						if ($this->onSuccess !== null) {
							($this->onSuccess)();
						}
					},
					function (Throwable $e) {
						if (!$this->silent) {
							$this->sender->sendMessage(TextFormat::RED . $e->getMessage());
						}
					},
				);
			},
			function (Throwable $e) {
				if (!$this->silent) {
					$this->sender->sendMessage(str_replace("{{reason}}", $e->getMessage(), OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.cannot.downloaded")));
				}
			},
		);
	}
}
