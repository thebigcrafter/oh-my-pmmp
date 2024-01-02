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
use pocketmine\command\CommandSender;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use function implode;
use function is_null;

class ShowCommand extends BaseSubCommand
{
	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.show");

        $this->registerArgument(0, new RawStringArgument("name", false));
        $this->registerArgument(1, new RawStringArgument("version", true));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $version = $args["version"] ?? null;

        $plugin = PoggitPluginsPool::getItem($name);

        if (!isset($plugin)) {
            $sender->sendMessage(Language::translate("commands.show.failed_1", ["name" => $name]));
            return;
        }

        $pluginVersion = $plugin->getVersion($version);

        if (is_null($pluginVersion["plugin"])) {
            $sender->sendMessage(Language::translate("commands.show.failed_2", ["version" => $version]));
            return;
        }

        $info = $pluginVersion["plugin"];

        // aka $version if user provides a specified version
        $latestVersion = $pluginVersion["version"];

        $sender->sendMessage(Language::translate("commands.show.form.name", ["name" => $name]));
        $sender->sendMessage(Language::translate("commands.show.form.version", ["version" => $latestVersion]));
        $sender->sendMessage(Language::translate("commands.show.form.versions", ["versions" => implode(", ", $plugin->getVersionsOnly())]));
        $sender->sendMessage(Language::translate("commands.show.form.homepage", ["homepage" => $info->getHtmlUrl()]));
        $sender->sendMessage(Language::translate("commands.show.form.license", ["license" => $plugin->getLicense()]));
        $sender->sendMessage(Language::translate("commands.show.form.download_url", ["download_url" => $info->getArtifactUrl()]));
        $sender->sendMessage(Language::translate("commands.show.form.downloads", ["downloads" => $info->getDownloads()]));
        $sender->sendMessage(Language::translate("commands.show.form.score", ["score" => $info->getScore()]));
        $sender->sendMessage(Language::translate("commands.show.form.description_url", ["description_url" => $info->getDescriptionUrl()]));
        $sender->sendMessage(Language::translate("commands.show.form.changelog_url", ["changelog_url" => $info->getChangelogUrl()]));
        $sender->sendMessage(Language::translate("commands.show.form.api", ["from" => $info->getSupportedAPI()->getMinimumSupportedVersion(), "to" => $info->getSupportedAPI()->getMaximumSupportedVersion()]));
        $sender->sendMessage(Language::translate("commands.show.form.deps", []));

        foreach($info->getDependencies() as $dep) {
            if($dep->isHard()) {
                $sender->sendMessage(Language::translate("commands.show.form.dep_2", ["name" => $dep->getName(), "version" => $dep->getVersion()]));
                continue;
            }
            $sender->sendMessage(Language::translate("commands.show.form.dep_1", ["name" => $dep->getName(), "version" => $dep->getVersion()]));
        }

        $sender->sendMessage("====================");
    }
}
