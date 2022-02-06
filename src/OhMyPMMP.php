<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

use thebigcrafter\OhMyPMMP\commands\InstallPluginCommand;
use thebigcrafter\OhMyPMMP\commands\RemovePluginCommand;
use thebigcrafter\OhMyPMMP\commands\UpgradePluginCommand;
use thebigcrafter\OhMyPMMP\tasks\CachePoggitPlugins;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class OhMyPMMP extends PluginBase {
    use SingletonTrait;

    public array $pluginsList = [];

    public function onEnable(): void {
        $this->setInstance($this);

        $this->getServer()->getAsyncPool()->submitTask(new CachePoggitPlugins());

        $this->getServer()->getCommandMap()->register("oh-my-pmmp", new InstallPluginCommand());
        $this->getServer()->getCommandMap()->register("oh-my-pmmp", new RemovePluginCommand());
        $this->getServer()->getCommandMap()->register("oh-my-pmmp", new UpgradePluginCommand());
    }

    public function setPluginsList(array $pluginsList): void {
        $this->pluginsList = $pluginsList;
    }

    public function getPluginsList(): array {
        return $this->pluginsList;
    }

    public static function getInstance(): OhMyPMMP {
        return self::$instance;
    }
}