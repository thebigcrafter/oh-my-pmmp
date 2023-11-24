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
use Phar;
use pocketmine\command\CommandSender;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\Utils;

use function Amp\File\exists;

class ExtractCommand extends BaseSubCommand {
    protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.extract");

        $this->registerArgument(0, new RawStringArgument("name", false));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $pluginFilePath = Path::join(Utils::getPluginsFolder(), "$name.phar");

        if(!exists($pluginFilePath)) {
            $sender->sendMessage(Language::translate("commands.extract.failed", ["name" => $name]));
            return;
        }

        $phar = new Phar($pluginFilePath);
        $phar->extractTo(Path::join(Utils::getPluginsFolder(), $name));

        $sender->sendMessage(Language::translate("commands.extract.successfully", ["name" => $name]));
    }
}
