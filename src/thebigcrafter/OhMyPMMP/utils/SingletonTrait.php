<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <thebigcrafterteam@proton.me>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

use thebigcrafter\OhMyPMMP\OhMyPMMP;

trait SingletonTrait
{
	public static OhMyPMMP $instance;

	public static function setInstance(OhMyPMMP $instance) : void
	{
		self::$instance = $instance;
	}

	public static function getInstance() : OhMyPMMP
	{
		return self::$instance;
	}
}
