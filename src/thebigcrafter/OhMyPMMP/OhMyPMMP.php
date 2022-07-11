<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

use pocketmine\lang\Language;
use pocketmine\plugin\PluginBase;
use thebigcrafter\OhMyPMMP\commands\OMPCommand;
use thebigcrafter\OhMyPMMP\tasks\CachePoggitPlugins;
use thebigcrafter\OhMyPMMP\utils\SingletonTrait;

use function is_dir;
use function is_file;
use function mkdir;
use function strval;

class OhMyPMMP extends PluginBase
{

	use SingletonTrait;

	public Language $language;

	public bool $isCachePoggitPluginsTaskRunning = false;

	/** @var array<string, array<string>> */
	public array $pluginsList = [];

	public function onEnable(): void
	{
		self::setInstance($this);

		$this->saveDefaultConfig();
		$this->loadLanguage();

		$this->isCachePoggitPluginsTaskRunning = true;
		$this->getServer()->getAsyncPool()->submitTask(new CachePoggitPlugins());

		$this->getServer()->getCommandMap()->register("OhMyPMMP", new OMPCommand($this, "ohmypmmp", "Oh My PMMP", ["omp", "oh-my-pmmp"]));
	}

	public function loadLanguage(): void
	{
		$langFolder = $this->getDataFolder() . "lang/";

		if (!is_dir($langFolder)) {
			@mkdir($langFolder);
		}

		foreach ((array) $this->getConfig()->get("availableLanguages") as $lang) {
			if (!is_file(strval($lang))) {
				$this->saveResource("lang/" . strval($lang) . ".ini");
			}
		}

		$this->language = new Language(strval($this->getConfig()->get('language')), $langFolder);
	}

	/**
	 * @return array<string, array<string>>
	 */
	public function getPluginsList(): array
	{
		return $this->pluginsList;
	}

	/**
	 * @param array<string, array<string>> $pluginsList
	 */
	public function setPluginsList(array $pluginsList): void
	{
		$this->pluginsList = $pluginsList;
	}

	public function getLanguage(): Language {
		return $this->language;
	}
}
