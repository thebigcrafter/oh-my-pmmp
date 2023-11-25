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
use thebigcrafter\omp\Language;
use thebigcrafter\omp\tasks\RemovePluginTask;

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

        $exec = (new RemovePluginTask($name, $wipeData))->execute();

        if (!$exec) {
            $sender->sendMessage(Language::translate("commands.remove.failed", ["name" => $name]));
            return;
        }
        $sender->sendMessage(Language::translate("commands.remove.successfully", ["name" => $name]));
        return;
    }
}
