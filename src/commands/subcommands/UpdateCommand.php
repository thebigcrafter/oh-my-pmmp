<?php

namespace thebigcrafter\omp\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\omp\OhMyPMMP;

class UpdateCommand extends BaseSubCommand {
	protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.update");
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        OhMyPMMP::getInstance()->fetchData();
    }
}