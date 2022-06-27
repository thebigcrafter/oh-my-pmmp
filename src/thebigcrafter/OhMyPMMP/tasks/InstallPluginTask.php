<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\tasks;

use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use pocketmine\utils\InternetException;
use pocketmine\utils\TextFormat;
use thebigcrafter\OhMyPMMP\async\Filesystem;
use thebigcrafter\OhMyPMMP\async\Internet;
use thebigcrafter\OhMyPMMP\OhMyPMMP;
use Throwable;

class InstallPluginTask extends Task
{
    /**
     * @var CommandSender $sender
     */
    private CommandSender $sender;

    /**
     * @var string $pluginName
     */
    private string $pluginName;

    /**
     * @var string $pluginVersion
     */
    private string $pluginVersion;

    private bool $silent;

    public function __construct(CommandSender $sender, string $pluginName, string $pluginVersion, bool $silent = false)
    {
        $this->sender = $sender;
        $this->pluginName = $pluginName;
        $this->pluginVersion = $pluginVersion;
        $this->silent = $silent;
    }

    public function onRun(): void
    {
        $this->sender->sendMessage("Install start");
        $pluginsList = [];
        $downloadURL = "";

        foreach (OhMyPMMP::getInstance()->getPluginsList() as $plugin) {
            if ($plugin["name"] == $this->pluginName) {
                $pluginsList[] = $plugin;
            }
        }

        if ($this->pluginVersion != "latest") {
            foreach ($pluginsList as $plugin) {
                if ($plugin["version"] == $this->pluginVersion) {
                    $downloadURL = $plugin["artifact_url"];
                }
            }
        } else {
            $version = "0.0.0";

            foreach ($pluginsList as $plugin) {
                if (version_compare($plugin["version"], $version, ">")) {
                    $version = $plugin["version"];
                    $downloadURL = $plugin["artifact_url"];
                }
            }
        }

        if (empty($downloadURL)) {
            if (!$this->silent) {
                $this->sender->sendMessage(TextFormat::RED . "Plugin $this->pluginName not found");
                return;
            }
            return;
        }
        Internet::fetch($downloadURL . "/$this->pluginName.phar")->then(function ($raw) {
            /** @var \React\Promise\Promise $writefile */
            $writefile = Filesystem::writeFile(OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/" . $this->pluginName . ".phar", $raw);
            $writefile->done(function () {
                if (!$this->silent) {
                    $this->sender->sendMessage(TextFormat::GREEN . "Plugin $this->pluginName installed successfully");
                }
            }, function (Throwable $e) {
                if (!$this->silent) {
                    $this->sender->sendMessage(TextFormat::RED . $e->getMessage());
                }
            });
        }, function (InternetException $e) {
            if (!$this->silent) {
                $this->sender->sendMessage(TextFormat::RED . "Could not download plugin: " . $e->getMessage());
            }
        });

        $this->sender->sendMessage("Install end");
    }
}
