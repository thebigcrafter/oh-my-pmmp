<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

use pocketmine\plugin\PluginBase;
use thebigcrafter\OhMyPMMP\commands\InstallPluginCommand;
use thebigcrafter\OhMyPMMP\commands\OMPCommand;
use thebigcrafter\OhMyPMMP\commands\RemovePluginCommand;
use thebigcrafter\OhMyPMMP\commands\UpgradePluginCommand;
use thebigcrafter\OhMyPMMP\tasks\CachePoggitPlugins;
use thebigcrafter\OhMyPMMP\utils\SingletonTrait;

class OhMyPMMP extends PluginBase {

	use SingletonTrait;

	public bool $isCachePoggitPluginsTaskRunning = false;
	/** @var array<string, array<string>> */
	public array $pluginsList = [];

	public function onEnable(): void {
		self::setInstance($this);

		$this->isCachePoggitPluginsTaskRunning = true;
		$this->getServer()->getAsyncPool()->submitTask(new CachePoggitPlugins());

        $this->getServer()->getCommandMap()->register("OhMyPMMP", new OMPCommand($this, "ohmypmmp", "Oh My PMMP", ["omp", "oh-my-pmmp"]));
	}
	/**
	 * @param array<string, array<string>> $pluginsList
	 */
	public function setPluginsList(array $pluginsList): void {
		$this->pluginsList = $pluginsList;
	}
	/**
	 * @return array<string, array<string>>
	 */
	public function getPluginsList(): array {
		return $this->pluginsList;
	}
}