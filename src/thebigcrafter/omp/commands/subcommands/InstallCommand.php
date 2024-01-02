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
use Exception;
use Generator;
use pocketmine\command\CommandSender;
use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\helpers\PharHelper;
use thebigcrafter\omp\helpers\PoggitHelper;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\utils\Internet;
use function is_null;

class InstallCommand extends BaseSubCommand
{
    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $version = $args["version"] ?? null;

        $plugin = PoggitPluginsPool::getItem($name);

		if(!PoggitHelper::pluginExist($name)) {
			$sender->sendMessage(Language::translate("commands.install.failed_1", ["name" => $name]));
			return;
		}

        $pluginVersion = $plugin->getVersion($version);

        if (is_null($pluginVersion["plugin"])) {
            $sender->sendMessage(Language::translate("commands.install.failed_2", ["version" => (string) $version]));
            return;
        }

        $info = $pluginVersion["plugin"];
        // aka $version if user provides a specified version
        $latestVersion = (string) $pluginVersion["version"];

        Await::f2c(function () use ($latestVersion, $name, $sender, $info) : Generator {
            try {
                $res = yield from Internet::fetch($info->getArtifactUrl());

                $pharPath = Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins", "$name.phar");
                yield from PharHelper::create($pharPath, $res);
                $sender->sendMessage(Language::translate("commands.install.successfully", ["name" => $name, "version" => $latestVersion]));

            } catch (Exception $e) {
                $sender->sendMessage(Language::translate("messages.operation.failed", ["reason" => $e->getMessage()]));
            }
        });
    }

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.install");

        $this->registerArgument(0, new RawStringArgument("name", false));
        $this->registerArgument(1, new RawStringArgument("version", true));
    }
}
