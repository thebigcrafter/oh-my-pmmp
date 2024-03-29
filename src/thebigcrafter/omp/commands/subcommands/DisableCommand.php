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
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\utils\Filesystem;
use thebigcrafter\omp\utils\Utils;

class DisableCommand extends BaseSubCommand
{
	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.disable");

        $this->registerArgument(0, new RawStringArgument("name", false));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $oldPluginFilePath = Utils::generatePluginFilePathWithName($name);
        $newPluginFilePath = Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "disabled_plugins", "$name.phar");

        if (!Filesystem::exists($oldPluginFilePath)) {
            $sender->sendMessage(Language::translate("commands.disable.failed", ["name" => $name]));
            return;
        }

        Await::f2c(function () use ($oldPluginFilePath, $newPluginFilePath, $sender) : Generator {
            try {
                yield from Filesystem::rename($oldPluginFilePath, $newPluginFilePath);
            } catch (IOException $e) {
                $sender->sendMessage(Language::translate("messages.operation.failed", ["reason" => $e->getMessage()]));
            }
        });

        $sender->sendMessage(Language::translate("commands.disable.successfully", ["name" => $name]));
    }
}
