<?php

declare(strict_types=1);

namespace thebigcrafter\omp\helpers;

use pocketmine\plugin\Plugin;
use pocketmine\Server;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\tasks\CheckForUpdates;

class UpdateChecker
{
	public static function init(Plugin $plugin): void
	{
		OhMyPMMP::getInstance()->getServer()->getAsyncPool()->submitTask(new CheckForUpdates($plugin->getDescription()->getName(), $plugin->getDescription()->getVersion()));
	}
}