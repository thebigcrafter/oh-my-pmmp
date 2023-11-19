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

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\omp\OhMyPMMP;
use function phpversion;
use const PHP_INT_SIZE;

class VersionCommand extends BaseSubCommand
{
    protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.version");
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $phpVersion = phpversion();
        $pluginVersion = OhMyPMMP::getInstance()->getDescription()->getVersion();
        $arch = PHP_INT_SIZE * 8 . "bit";

        $sender->sendMessage("OMP v$pluginVersion");
        $sender->sendMessage("PHP v$phpVersion $arch");
    }
}
