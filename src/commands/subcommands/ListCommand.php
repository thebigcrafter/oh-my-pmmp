<?php

namespace thebigcrafter\omp\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\pool\PoggitPluginsPool;

class ListCommand extends BaseSubCommand {
	protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.list");

		$this->registerArgument(0, new IntegerArgument("page", true));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
		$page = isset($args["page"]) ? $args["page"] : 0;
		$pluginsPerPage = OhMyPMMP::getInstance()->getConfig()->get("pluginsPerPage");
		$totalPages = ceil(count(PoggitPluginsPool::getPool()) / $pluginsPerPage);
		$page = max(0, min($page, $totalPages - 1));
		$offset = $page * $pluginsPerPage;
		/** @var array{name: string, plugin: Plugin} $pluginsOnPage */
		$pluginsOnPage = array_slice(PoggitPluginsPool::getPool(), $offset, $pluginsPerPage);

		foreach ($pluginsOnPage as $name => $info) {
			// TODO: make a beautiful table :D
		}
    }
}