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

use SOFe\AwaitGenerator\Await;
use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\Utils;
use thebigcrafter\omp\utils\Filesystem;

class RemovePluginTask extends Task
{
	public function __construct(private readonly string $name, private readonly bool $wipeData)
	{
	}
	public function execute(): bool
	{
		$name = $this->name;
		$wipeData = $this->wipeData;

		$pluginFilePath = Path::join(Utils::getPluginsFolder(), "$name.phar");
		$pluginFolderPath = Path::join(Utils::getPluginsFolder(), $name);

		if (Filesystem::exists($pluginFilePath)) {
			Await::g2c(Filesystem::remove($pluginFilePath));
		} elseif (Filesystem::exists($pluginFolderPath)) {
			Await::g2c(Filesystem::remove($pluginFolderPath));
		} else {
			return false;
		}
		if ($wipeData) {
			$pluginDataFolder = Path::join(OhMyPMMP::getInstance()->getDataFolder(), "..", $name);
			Await::g2c(Filesystem::remove($pluginDataFolder));
		}
		return true;
	}
}
