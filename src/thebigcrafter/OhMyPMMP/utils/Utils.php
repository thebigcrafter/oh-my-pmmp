<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\utils;

use function preg_match;

class Utils {

	/**
	 * Validate the plugin name
	 */
	public static function validatePluginName(string $pluginName) : bool {
		if(preg_match('#\.\./#', $pluginName)) {
			return false;
		}
		return true;
	}
}