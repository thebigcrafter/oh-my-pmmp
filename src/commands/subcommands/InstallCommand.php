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

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use function Amp\File\openFile;
use function is_null;

class InstallCommand extends BaseSubCommand {
    protected function prepare() : void
    {
        $this->setPermission("oh-my-pmmp.install");

        $this->registerArgument(0, new RawStringArgument("name", false));
        $this->registerArgument(1, new RawStringArgument("version", true));
    }

    /**
     * @param array<string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {
        $name = $args["name"];
        $version = isset($args["version"]) ? $args["version"] : null;

        $plugin = PoggitPluginsPool::getItem($name);

        if (!isset($plugin)) {
            $sender->sendMessage(Language::translate("commands.install.failed_1", ["name" => $name]));
            return;
        }

        $pluginVersion = $plugin->getVersion($version);

        if (is_null($pluginVersion["plugin"])) {
            $sender->sendMessage(Language::translate("commands.install.failed_2", ["version" => (string)$version]));
            return;
        }

        $info = $pluginVersion["plugin"];
        // aka $version if user provides a specified version
        $latestVersion = (string) $pluginVersion["version"];

        $client = HttpClientBuilder::buildDefault();

        $res = $client->request(new Request($info->getArtifactUrl()));

        if($res->getStatus() !== 200) {
            $sender->sendMessage(Language::translate("messages.operation.failed", ["reason" => $res->getReason()]));
            return;
        }

        $pharPath = Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins", "$name.phar");
        $phar = openFile($pharPath, "w");

        while (($chunk = $res->getBody()->read()) !== null) {
            $phar->write((string) $chunk);
        }
        $phar->end();

        $sender->sendMessage(Language::translate("commands.install.successfully", ["name" => $name, "version" => $latestVersion]));
    }
}
