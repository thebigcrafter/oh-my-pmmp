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

use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\Utils;

use function Amp\File\deleteDirectory;
use function Amp\File\deleteFile;
use function Amp\File\exists;

class RemovePluginTask extends Task
{
    public function __construct(private readonly string $name, private readonly bool $wipeData)
    {
    }
    public function execute() : bool
    {
        $name = $this->name;
        $wipeData = $this->wipeData;

        $pluginFilePath = Path::join(Utils::getPluginsFolder(), "$name.phar");
        $pluginFolderPath = Path::join(Utils::getPluginsFolder(), $name);

        if (exists($pluginFilePath)) {
            deleteFile($pluginFilePath);
        } elseif (exists($pluginFolderPath)) {
            deleteDirectory($pluginFolderPath);
        } else {
            return false;
        }
        if ($wipeData) {
            $this->wipeData($name);
        }
        return true;
    }

    private function wipeData(string $name) : void
    {
        $pluginDataFolder = Path::join(OhMyPMMP::getInstance()->getDataFolder(), "..", $name);
        deleteDirectory($pluginDataFolder);
    }
}
