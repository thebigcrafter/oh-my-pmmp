<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

class Vars {
	public const POGGIT_REPO_URL = "https://poggit.pmmp.io/releases.min.json?fields=name,version,artifact_url,html_url,license,downloads,score,api,deps";

	public static function getPluginsFolder() : string {
		return OhMyPMMP::getInstance()->getServer()->getDataPath() . "plugins/";
	}
}
