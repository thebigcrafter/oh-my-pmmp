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

namespace thebigcrafter\omp\helpers;

use Closure;
use Exception;
use Generator;
use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\types\PluginType;
use thebigcrafter\omp\Utils;
use thebigcrafter\omp\utils\Filesystem;

class PluginHelper
{
    public static function remove(string $name, bool $wipeData) : Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($name, $wipeData) {
            $pluginFilePath = Path::join(Utils::getPluginsFolder(), "$name.phar");
            $pluginFolderPath = Path::join(Utils::getPluginsFolder(), $name);

            if (Filesystem::exists($pluginFilePath)) {
                Await::g2c(Filesystem::remove($pluginFilePath));
            } elseif (Filesystem::exists($pluginFolderPath)) {
                Await::g2c(Filesystem::remove($pluginFolderPath));
            } else {
                $reject(new Exception("Plugin not found"));
            }
            if ($wipeData) {
                $pluginDataFolder = Path::join(OhMyPMMP::getInstance()->getDataFolder(), "..", $name);
                Await::g2c(Filesystem::remove($pluginDataFolder));
            }
            $resolve(true);
        });
    }

    /**
     * Return true if plugin exists, false on failure
     */
    public static function exists(string $name, PluginType $type = PluginType::FILE_TYPE) : bool
    {
        if ($type === PluginType::FILE_TYPE) {
            return Filesystem::exists(Path::join(Utils::getPluginsFolder(), "$name.phar"));
        }

        return Filesystem::exists(Path::join(Utils::getPluginsFolder(), $name));
    }
}
