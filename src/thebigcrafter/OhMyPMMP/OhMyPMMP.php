<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP;

use pocketmine\lang\Language;
use pocketmine\plugin\PluginBase;
use thebigcrafter\OhMyPMMP\commands\OMPCommand;
use thebigcrafter\OhMyPMMP\tasks\CachePoggitPlugins;
use thebigcrafter\OhMyPMMP\utils\SingletonTrait;

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

		foreach ($this->getConfig()->get("availableLanguages") as $lang) {
			if (!is_file($lang)) {
				$this->saveResource("lang/$lang.ini");
			}
		}

		$this->language = new Language($this->getConfig()->get('language'), $langFolder);
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