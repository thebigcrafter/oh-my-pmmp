<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\OhMyPMMP;

class RemovePluginTask extends Task {
    /**
     * @var CommandSender $sender
     */
    private CommandSender $sender;

    /**
     * @var string $pluginName
     */
    private string $pluginName;

    /**
     * @var bool $silent
     */
    private bool $silent;

    public function __construct(CommandSender $sender, string $pluginName, bool $silent = false)
    {
        $this->sender = $sender;
        $this->pluginName = $pluginName;
        $this->silent = $silent;
    }

    public function onRun(): void
    {
        $this->sender->sendMessage("Remove start");
        Filesystem::unlinkPhar(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/$this->pluginName.phar")->then(function () {
            if (!$this->silent) {
                $this->sender->sendMessage(TextFormat::GREEN . "Plugin $this->pluginName " . TextFormat::GREEN . "has been removed successfully");
            }
        }, function () {
            if (!$this->silent) {
                $this->sender->sendMessage(TextFormat::RED . "Plugin $this->pluginName " . TextFormat::RED . "not found");
            }
        });
        $this->sender->sendMessage("Remove end");
    }
}