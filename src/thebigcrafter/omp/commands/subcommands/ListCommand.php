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

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\types\Plugin;
use function array_slice;
use function ceil;
use function count;
use function implode;
use function max;
use function min;

class ListCommand extends BaseSubCommand
{
	/**
	 * @throws ArgumentOrderException
	 */
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
        $page = isset($args["page"]) ? (int) $args["page"] : 0;
        /** @var int $pluginsPerPage */
        $pluginsPerPage = OhMyPMMP::getInstance()->getConfig()->get("pluginsPerPage");
        $totalPages = ceil(count(PoggitPluginsPool::getPool()) / $pluginsPerPage);
        $page = max(0, min($page, $totalPages - 1));
        $offset = $page * $pluginsPerPage;
        /** @var array{name: string, plugin: Plugin} $pluginsOnPage */
        $pluginsOnPage = array_slice(PoggitPluginsPool::getPool(), $offset, $pluginsPerPage);

        foreach ($pluginsOnPage as $name => $plugin) {
            $sender->sendMessage(Language::translate("commands.list.form.name", ["name" => $name]));
            $sender->sendMessage(Language::translate("commands.list.form.license", ["license" => $plugin->getLicense()]));
            $sender->sendMessage(Language::translate("commands.list.form.versions", ["versions" => implode(", ", $plugin->getVersionsOnly())]));
            $sender->sendMessage("====================");
        }
    }
}
