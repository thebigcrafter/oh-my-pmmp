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

use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\types\Plugin;

class PoggitHelper
{
    /**
     * Check if the plugin exists
     */
    public static function pluginExist(string $name) : bool {
        if(PoggitPluginsPool::getItem($name) === null) {
            return false;
        }
        return true;
    }

    /**
     * Check if the plugin of the plugin exists
     */
    public static function versionExist(string $name, string $version) : bool {
        $plugin = PoggitPluginsPool::getItem($name);

        if($plugin === null) {
            return false;
        }

        if($plugin->getVersion($version)["plugin"] === null) return false;

        return true;
    }
}
