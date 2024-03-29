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
use CortexPE\Commando\exception\ArgumentOrderException;
use Generator;
use pocketmine\command\CommandSender;
use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Filesystem;
use thebigcrafter\omp\helpers\PharHelper;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\utils\Utils;
use Throwable;

class ExtractCommand extends BaseSubCommand
{
	/**
	 * @throws ArgumentOrderException
	 */
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
        $fs = new Filesystem();
        $name = $args["name"];
        $pluginFilePath = Utils::generatePluginFilePathWithName($name);

        if (!$fs->exists($pluginFilePath)) {
            $sender->sendMessage(Language::translate("commands.extract.failed", ["name" => $name]));
            return;
        }

        Await::f2c(function () use ($pluginFilePath, $name, $sender) : Generator {
            try {
                yield from PharHelper::extract($pluginFilePath, Utils::generatePluginFolderPathWithName($name));
                $sender->sendMessage(Language::translate("commands.extract.successfully", ["name" => $name]));
            } catch (Throwable $e) {
                $sender->sendMessage(Language::translate("messages.operation.failed", ["reason" => $e->getMessage()]));
            }
        });
    }
}
