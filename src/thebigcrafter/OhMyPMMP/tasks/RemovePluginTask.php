<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;
use Throwable;
use function is_file;
use function is_null;
use function realpath;
use function str_replace;
use function str_starts_with;

class RemovePluginTask extends Task {
	private CommandSender $sender;

	private string $pluginName;

	private bool $silent;

	public function __construct(CommandSender $sender, string $pluginName, bool $silent = false) {
		$this->sender = $sender;
		$this->pluginName = $pluginName;
		$this->silent = $silent;
	}

	public function onRun() : void {

		$basePath = Vars::getPluginsFolder();
		$fullPath = $basePath . $this->pluginName;
		$normalizedPath = realpath($basePath . $this->pluginName . ".phar");

		if ($normalizedPath === false || !str_starts_with($normalizedPath, $basePath)) {
			$this->sender->sendMessage(OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.name.invalid"));
			return;
		}

		$pluginManager = OhMyPMMP::getInstance()->getServer()->getPluginManager();
		$plugin = $pluginManager->getPlugin($this->pluginName);

		if (is_null($plugin)) {
			$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.not.found")));
			return;
		}

		$pluginManager->disablePlugin($plugin);

		if (is_file($fullPath . ".phar")) {
			Filesystem::unlinkPhar($fullPath . ".phar")->then(
				function () {
					if (!$this->silent) {
						$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.removed")));
					}
				},
				function (Throwable $e) {
					if (!$this->silent) {
						$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.not.found")));
					}
				},
			);
		} else {
			Filesystem::deleteFolder(Vars::getPluginsFolder() . $this->pluginName);
			if (!$this->silent) {
				$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.removed")));
			}
		}
	}
}
