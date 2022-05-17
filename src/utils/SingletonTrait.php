<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

use thebigcrafter\OhMyPMMP\OhMyPMMP;

trait SingletonTrait {

	public static OhMyPMMP $instance;

	public static function setInstance(OhMyPMMP $instance): void
	{
		self::$instance = $instance;
	}

	public static function getInstance(): OhMyPMMP
	{
		return self::$instance;
	}
}