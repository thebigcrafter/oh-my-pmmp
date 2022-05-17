<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

use pocketmine\plugin\PluginBase;
use thebigcrafter\OhMyPMMP\commands\InstallPluginCommand;
use thebigcrafter\OhMyPMMP\commands\RemovePluginCommand;
use thebigcrafter\OhMyPMMP\commands\UpgradePluginCommand;
use thebigcrafter\OhMyPMMP\tasks\CachePoggitPlugins;
use thebigcrafter\OhMyPMMP\utils\SingletonTrait;

class OhMyPMMP extends PluginBase {

	use SingletonTrait;

	public array $pluginsList = [];

	public function onEnable(): void {
		$this->setInstance($this);

		$this->getServer()->getAsyncPool()->submitTask(new CachePoggitPlugins());

		$this->getServer()->getCommandMap()->registerAll("OhMyPMMP", [
			new InstallPluginCommand(),
			new RemovePluginCommand(),
			new UpgradePluginCommand()
		]);
	}

	public function setPluginsList(array $pluginsList): void {
		$this->pluginsList = $pluginsList;
	}

	public function getPluginsList(): array {
		return $this->pluginsList;
	}
}