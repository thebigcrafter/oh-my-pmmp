<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

use pocketmine\lang\Language;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use thebigcrafter\OhMyPMMP\async\CachePlugins;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\commands\OMPCommand;
use function is_dir;
use function is_file;
use function mkdir;

class OhMyPMMP extends PluginBase {
	use SingletonTrait;

	public Language $language;

	public function onLoad() : void {
		self::setInstance($this);
	}

	public function onEnable() : void {
		$this->saveDefaultConfig();
		$this->loadLanguage();

		PluginsPool::init();
		CachePlugins::cachePlugins();

		$this->getServer()->getCommandMap()->register("OhMyPMMP", new OMPCommand($this, "ohmypmmp", "Oh My PMMP", ["omp", "oh-my-pmmp"]));
	}

	public function loadLanguage() : void {
		$langFolder = $this->getDataFolder() . "lang/";

		if (!is_dir($langFolder)) {
			@mkdir($langFolder);
		}

		/** @var string $lang */
		foreach ((array) $this->getConfig()->get("availableLanguages") as $lang) {
			if (!is_file($lang)) {
				$this->saveResource("lang/" . $lang . ".ini");
			}
		}
		/** @var string $lang */
		$lang = $this->getConfig()->get("language");
		$this->language = new Language($lang, $langFolder);
	}

	public function getLanguage() : Language {
		return $this->language;
	}
}
