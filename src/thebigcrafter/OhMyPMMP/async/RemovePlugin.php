<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);
namespace thebigcrafter\OhMyPMMP\async;

use pocketmine\Server;
use SOFe\AwaitGenerator\Await;
use thebigcrafter\OhMyPMMP\utils\Filesystem;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function is_file;
use const DIRECTORY_SEPARATOR;

class RemovePlugin extends PluginAction {

	public function execute() : void {
		$pluginName = $this->getPluginName();
		$basePath = Utils::getPluginsFolder();
		$fullPath = $basePath . DIRECTORY_SEPARATOR . $pluginName;

		$pluginManager = Server::getInstance()->getPluginManager();
		$plugin = $pluginManager->getPlugin($pluginName);

		if(!$plugin) {
			$this->sender->sendMessage(Utils::translate("plugin.not.found", ["plugin" => $pluginName]));
			return;
		}

		$pluginManager->disablePlugin($plugin);
		Await::f2c(function() use ($fullPath) {
			$pharPath = $fullPath . ".phar";
			$isDelete = is_file($pharPath)
				? (yield from Filesystem::awaitUnlinkPhar($pharPath))
				: Filesystem::deleteFolder($fullPath);
			if(!$this->isSilent() && $isDelete) {
				$this->getCommandSender()->sendMessage(Utils::translate("plugin.removed", ["plugin" => $this->getPluginName()]));
			}
			$this->onSuccess();
		});
	}
}
