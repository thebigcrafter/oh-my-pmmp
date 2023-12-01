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

use pocketmine\plugin\Plugin;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\tasks\CheckForUpdates;

class UpdateChecker
{
    public static function init(Plugin $plugin) : void
    {
        OhMyPMMP::getInstance()->getServer()->getAsyncPool()->submitTask(new CheckForUpdates($plugin->getDescription()->getName(), $plugin->getDescription()->getVersion()));
    }
}
