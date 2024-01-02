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

use pocketmine\plugin\PluginBase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\commands\OMPCommand;
use thebigcrafter\omp\helpers\UpdateChecker;
use thebigcrafter\omp\tasks\FetchDataTask;
use thebigcrafter\omp\trait\SingletonTrait;

class OhMyPMMP extends PluginBase
{
    use SingletonTrait;
    public function onLoad() : void
    {
        self::setInstance($this);
		/** @var string $selectedLanguage */
		$selectedLanguage = $this->getConfig()->get("language");
        Language::loadLanguages($selectedLanguage);
        $this->createFolders();
    }

    public function onEnable() : void
    {
        UpdateChecker::init($this);
        $this->fetchData();
        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->register("OhMyPMMP", new OMPCommand($this, "ohmypmmp", "Oh My PMMP", ["omp", "oh-my-pmmp"]));
    }

    /**
     * Fetch necessary data such as Poggit plugins list
     * TODO: fetch installed plugins and upgradable plugins
     */
    public function fetchData() : void
    {
        $this->getLogger()->info(Language::translate("messages.pool.fetching", []));
        $this->getServer()->getAsyncPool()->submitTask(new FetchDataTask());
    }

    /**
     * Create necessary folders if they haven't existed yet
     */
    private function createFolders() : void
    {
        $fs = new Filesystem();
        $disabledPluginsFolderPath = Path::join($this->getServer()->getPluginPath(), "..", "disabled_plugins");

        if (!$fs->exists($disabledPluginsFolderPath)) {
            $fs->mkdir($disabledPluginsFolderPath);
        }
    }
}
