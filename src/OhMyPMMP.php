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

	public bool $isCachePoggitPluginsTaskRunning = false;

	/* @phpstan-ignore-next-line */
	public array $pluginsList = [];

	public function onEnable(): void {
		self::setInstance($this);

		$this->isCachePoggitPluginsTaskRunning = true;
		$this->getServer()->getAsyncPool()->submitTask(new CachePoggitPlugins());

		$this->getServer()->getCommandMap()->registerAll("OhMyPMMP", [
			new InstallPluginCommand(),
			new RemovePluginCommand(),
			new UpgradePluginCommand()
		]);
	}

	/* @phpstan-ignore-next-line */
	public function setPluginsList(array $pluginsList): void {
		$this->pluginsList = $pluginsList;
	}

	/* @phpstan-ignore-next-line */
	public function getPluginsList(): array {
		return $this->pluginsList;
	}
}