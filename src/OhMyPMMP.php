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

namespace thebigcrafter\omp;

require __DIR__ . "/../vendor/autoload.php";

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use pocketmine\plugin\PluginBase;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\commands\OMPCommand;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\tasks\FetchDataTask;
use thebigcrafter\omp\trait\SingletonTrait;
use thebigcrafter\omp\types\API;
use thebigcrafter\omp\types\Dependency;
use thebigcrafter\omp\types\Plugin;
use thebigcrafter\omp\types\PluginVersion;
use function Amp\File\createDirectory;
use function Amp\File\isDirectory;
use function array_map;
use function count;
use function json_decode;
use function strval;

class OhMyPMMP extends PluginBase
{
    use SingletonTrait;
    public function onLoad() : void
    {
        self::setInstance($this);
        Language::loadLanguages();
        $this->createFolders();

    }

    public function onEnable() : void
    {
        $this->fetchData();
        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->register("OhMyPMMP", new OMPCommand($this, "ohmypmmp", "Oh My PMMP", ["omp", "oh-my-pmmp"]));
    }

    public function fetchData() : void
    {
        $this->getLogger()->info(Language::translate("messages.pool.fetching", []));
        $this->getServer()->getAsyncPool()->submitTask(new FetchDataTask($this)); 
    }

    private function createFolders() : void
    {
        $disabledPluginsFolderPath = Path::join($this->getServer()->getPluginPath(), "..", "disabled_plugins");

        if (!isDirectory($disabledPluginsFolderPath)) {
            createDirectory($disabledPluginsFolderPath);
        }
    }
}
