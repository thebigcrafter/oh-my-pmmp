<?php

/*
 * This file is part of oh-my-pmmp.
 *
 * (c) thebigcrafter <hello@thebigcrafter.team>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\omp\commands\subcommands;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use function Amp\File\deleteDirectory;
use function Amp\File\deleteFile;
use function Amp\File\exists;

class RemoveCommand extends BaseSubCommand
{
    protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.remove");

        $this->registerArgument(0, new RawStringArgument("name", false));
        $this->registerArgument(1, new BooleanArgument("wipeData", true));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $wipeData = isset($args["wipeData"]) ? $args["wipeData"] : false;

        $exec = $this->removePlugin($name, $wipeData);

        if (!$exec) {
            $sender->sendMessage(Language::translate("commands.remove.failed", ["name" => $name]));
            return;
        }
        $sender->sendMessage(Language::translate("commands.remove.successfully", ["name" => $name]));
        return;
    }

    /**
     * Return false if plugin not found
     */
    private function removePlugin(string $name, bool $wipeData) : bool
    {
        $pluginFilePath = Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins", "$name.phar");
        $pluginFolderPath = Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins", $name);

        if (exists($pluginFilePath)) {
            deleteFile($pluginFilePath);
        } elseif (exists($pluginFolderPath)) {
            deleteDirectory($pluginFolderPath);
        } else {
            return false;
        }
        if ($wipeData) {
            $this->wipeData($name);
        }
        return true;
    }

    private function wipeData(string $name) : void
    {
        $pluginDataFolder = Path::join(OhMyPMMP::getInstance()->getDataFolder(), "..", $name);
        deleteDirectory($pluginDataFolder);
    }
}
