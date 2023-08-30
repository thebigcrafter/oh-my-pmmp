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
use pocketmine\utils\Utils;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use thebigcrafter\OhMyPMMP\Vars;
use Throwable;
use function is_file;
use function is_null;
use function str_replace;

class RemovePluginTask extends Task {
	private CommandSender $sender;

	private string $pluginName;

	private bool $silent;
	private ?Closure $onSuccess;
	private ?Closure $onFail;


	public function __construct(CommandSender $sender, string $pluginName, bool $silent = false, ?Closure $onSuccess = null, ?Closure $onFail = null) {
		$this->sender = $sender;
		$this->pluginName = $pluginName;
		$this->silent = $silent;
		$this->onSuccess = $onSuccess;
		$this->onFail = $onFail;
	}

	public function onRun() : void {

		$basePath = Vars::getPluginsFolder();
		$fullPath = $basePath . $this->pluginName;

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
					if($this->onFail !== null) {
						($this->onFail)($e);
					}
				},
			);
		} else {
			Filesystem::deleteFolder(Vars::getPluginsFolder() . $this->pluginName);
			if (!$this->silent) {
				$this->sender->sendMessage(str_replace("{{plugin}}", $this->pluginName, OhMyPMMP::getInstance()->getLanguage()->translateString("plugin.removed")));
			}
		}

		$onSuccess = $this->onSuccess;
		if($onSuccess !== null) {
			$onSuccess();
		}
	}
}
