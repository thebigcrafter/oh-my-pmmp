<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use pocketmine\utils\InternetException;
use pocketmine\utils\TextFormat;
use React\Promise\Promise;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use Throwable;

use function str_replace;
use function version_compare;

class InstallPluginTask extends Task
{
	private CommandSender $sender;

	private string $pluginName;

	private string $pluginVersion;

	private bool $silent;

	private bool $extract;

	public function __construct(CommandSender $sender, string $pluginName, string $pluginVersion, bool $silent = false, bool $extract = false)
	{
		$this->sender = $sender;
		$this->pluginName = $pluginName;
		$this->pluginVersion = $pluginVersion;
		$this->silent = $silent;
		$this->extract = $extract;
	}

	public function onRun(): void
	{
		$pluginsList = [];
		$downloadURL = "";

		foreach (OhMyPMMP::getInstance()->getPluginsList() as $plugin) {
			if ($plugin["name"] == $this->pluginName) {
				$pluginsList[] = $plugin;
			}
		}

		if ($this->pluginVersion != "latest") {
			foreach ($pluginsList as $plugin) {
				if ($plugin["version"] == $this->pluginVersion) {
					$downloadURL = $plugin["artifact_url"];
				}
			}
		} else {
			$version = "0.0.0";

			foreach ($pluginsList as $plugin) {
				if (version_compare($plugin["version"], $version, ">")) {
					$version = $plugin["version"];
					$downloadURL = $plugin["artifact_url"];
				}
			}
		}

		if (empty($downloadURL)) {
			if (!$this->silent) {
				$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.not.found")));
				return;
			}
			return;
		}
		Internet::fetch($downloadURL . "/$this->pluginName.phar")->then(function ($raw) {
			/** @var Promise $writefile */
			$writefile = Filesystem::writeFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar", $raw);
			$writefile->done(function () {
				if (!$this->silent) {
					$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.installed")));
				}

				if($this->extract) {
					$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.extracting")));

					Filesystem::extractPhar(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar", OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$this->pluginName")->then(function () {
						Filesystem::unlinkPhar(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar")->then(function () {
							$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.extracted")));
						});
					});
				}
			}, function (Throwable $e) {
				if (!$this->silent) {
					$this->sender->sendMessage(TextFormat::RED . $e->getMessage());
				}
			});
		}, function (InternetException $e) {
			if (!$this->silent) {
				$this->sender->sendMessage(str_replace("{{reason}}", $e->getMessage(), OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.cannot.downloaded")));
			}
		});
	}
}
