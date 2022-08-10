<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

use thebigcrafter\OhMyPMMP\Vars;
use function str_replace;

class RemovePluginTask extends Task
{
	private CommandSender $sender;

	private string $pluginName;

	private bool $silent;

	public function __construct(
		CommandSender $sender,
		string $pluginName,
		bool $silent = false,
	) {
		$this->sender = $sender;
		$this->pluginName = $pluginName;
		$this->silent = $silent;
	}

	public function onRun(): void
	{
		$pluginManager = OhMyPMMP::getInstance()
			->getServer()
			->getPluginManager();
		$plugin = $pluginManager->getPlugin($this->pluginName);

		if (is_null($plugin)) {
			$this->sender->sendMessage(
				str_replace(
					"{{plugin}}",
					$this->pluginName,
					OhMyPMMP::getInstance()
						->getLanguage()
						->translateString("plugin.not.found"),
				),
			);
			return;
		}

		$pluginManager->disablePlugin($plugin);

		if (is_file(Vars::getPluginsFolder() . "$this->pluginName.phar")) {
			Filesystem::unlinkPhar(
				OhMyPMMP::getInstance()
					->getServer()
					->getDataPath() . "plugins/$this->pluginName.phar",
			)->then(
				function () {
					if (!$this->silent) {
						$this->sender->sendMessage(
							str_replace(
								"{{plugin}}",
								$this->pluginName,
								OhMyPMMP::getInstance()
									->getLanguage()
									->translateString("plugin.removed"),
							),
						);
					}
				},
				function () {
					if (!$this->silent) {
						$this->sender->sendMessage(
							str_replace(
								"{{plugin}}",
								$this->pluginName,
								OhMyPMMP::getInstance()
									->getLanguage()
									->translateString("plugin.not.found"),
							),
						);
					}
				},
			);
		} else {
			Filesystem::deleteFolder(
				Vars::getPluginsFolder() . $this->pluginName,
			);
			if (!$this->silent) {
				$this->sender->sendMessage(
					str_replace(
						"{{plugin}}",
						$this->pluginName,
						OhMyPMMP::getInstance()
							->getLanguage()
							->translateString("plugin.removed"),
					),
				);
			}
		}
	}
}
