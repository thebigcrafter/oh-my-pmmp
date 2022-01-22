<?php

declare(strict_types=1);

namespace MintoD\OhMyPMMP;

use MintoD\OhMyPMMP\commands\InstallPluginCommand;
use MintoD\OhMyPMMP\commands\RemovePluginCommand;
use MintoD\OhMyPMMP\commands\UpgradePluginCommand;
use MintoD\OhMyPMMP\tasks\CachePoggitPlugins;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class OhMyPMMP extends PluginBase {
    use SingletonTrait;

    public array $pluginsList = [];

    public function onEnable(): void {
        $this->setInstance($this);

        $this->getServer()->getAsyncPool()->submitTask(new CachePoggitPlugins());

        $this->getServer()->getCommandMap()->register("installPlugin", new InstallPluginCommand());
        $this->getServer()->getCommandMap()->register("removePlugin", new RemovePluginCommand());
        $this->getServer()->getCommandMap()->register("upgradePlugin", new UpgradePluginCommand());
    }

    public function setPluginsList(array $pluginsList): void {
        $this->pluginsList = $pluginsList;
    }

    public function getPluginsList(): array {
        return $this->pluginsList;
    }
}