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

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\Utils;
use function Amp\File\exists;
use function Amp\File\move;

class EnableCommand extends BaseSubCommand
{
    protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.enable");

        $this->registerArgument(0, new RawStringArgument("name", false));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $newPluginFilePath = Path::join(Utils::getPluginsFolder(), "$name.phar");
        $oldPluginFilePath = Path::join(OhMyPMMP::getInstance()->getServer()->getPluginPath(), "..", "disabled_plugins", "$name.phar");

        if (!exists($oldPluginFilePath)) {
            $sender->sendMessage(Language::translate("commands.enable.failed", ["name" => $name]));
            return;
        }

        move($oldPluginFilePath, $newPluginFilePath);

        $sender->sendMessage(Language::translate("commands.enable.successfully", ["name" => $name]));
    }
}
