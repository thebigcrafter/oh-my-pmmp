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

namespace thebigcrafter\omp\tasks;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\Utils;

class RemovePluginTask extends Task
{
    private readonly Filesystem $fs;
    public function __construct(private readonly string $name, private readonly bool $wipeData)
    {
        $this->fs = new Filesystem();
    }
    public function execute() : bool
    {
        $name = $this->name;
        $wipeData = $this->wipeData;

        $pluginFilePath = Path::join(Utils::getPluginsFolder(), "$name.phar");
        $pluginFolderPath = Path::join(Utils::getPluginsFolder(), $name);

        if ($this->fs->exists($pluginFilePath)) {
            $this->fs->remove($pluginFilePath);
        } elseif ($this->fs->exists($pluginFolderPath)) {
            $this->fs->remove($pluginFolderPath);
        } else {
            return false;
        }
        if ($wipeData) {
            $pluginDataFolder = Path::join(OhMyPMMP::getInstance()->getDataFolder(), "..", $name);
            $this->fs->remove($pluginDataFolder);
        }
        return true;
    }
}
