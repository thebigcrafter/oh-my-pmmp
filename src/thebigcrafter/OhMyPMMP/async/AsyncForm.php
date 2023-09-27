<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use Closure;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\CustomFormElement;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use dktapps\pmforms\ModalForm;
use Generator;
use pocketmine\player\Player;
use pocketmine\Server;
use SOFe\AwaitGenerator\Await;
use thebigcrafter\OhMyPMMP\cache\PluginCache;
use thebigcrafter\OhMyPMMP\cache\PluginsPool;
use thebigcrafter\OhMyPMMP\utils\Utils;
use function array_keys;
use function array_map;
use function implode;
use function is_null;

final class AsyncForm {

	private const ACTION_SHOW = 0;
	private const ACTION_INSTALL = 1;

	public static function groupsForm(Player $player) : Generator {
		$listGroups = array_keys(Utils::groupByFirstLetter());

		$groupChoose = yield from self::menu($player, Utils::translate("list.group.title"), Utils::translate("list.group.content"), array_map(function(string $group){
			return new MenuOption($group);
		}, $listGroups));
		if($groupChoose !== null) {
			yield self::pluginsForm($player, (string) $listGroups[$groupChoose]);
		}
	}

	public static function pluginsForm(Player $player, string $group) : Generator {
		$listPlugins = Utils::groupByFirstLetter()[$group];
		$options = [];
		foreach($listPlugins as $plugin) {
			$pluginObj = PluginsPool::getPluginCacheByName($plugin);
			if($pluginObj !== null) {
				$options[] = new MenuOption($plugin, new FormIcon($pluginObj->getIconURL(), FormIcon::IMAGE_TYPE_URL));
			}
		}
		$pluginChoose = yield from self::menu($player, Utils::translate("list.plugin.of.group.title"), Utils::translate("list.plugin.of.group.content"), $options);

		if(!is_null($pluginChoose)) {
			$pluginName = $listPlugins[$pluginChoose];
			$plugin = PluginsPool::getPluginCacheByName($pluginName);
			if($plugin !== null) {
				yield self::versionsForm($player, $plugin, $group);
			}
		}
	}

	public static function actionForm(Player $player, PluginCache $plugin, string $version, string $group) : Generator {
		$actionChoose = yield from self::menu($player, $plugin->getName(), Utils::translate("action.title"), [
			new MenuOption(Utils::translate("action.button.info")),
			new MenuOption(Utils::translate("action.button.install")),
		]);
		switch ($actionChoose) {
			case self::ACTION_INSTALL:
				$serverAPI = Server::getInstance()->getApiVersion();
				/** @var null|array{from: string, to: string} $versionAPI */
				$versionAPI = $plugin->getVersion($version)?->getAPI();
				if(is_null($versionAPI)) {
					return;
				}
				if (Utils::compareVersion($plugin, $version)) {
					$installAction = new InstallPlugin($player, $plugin->getName(), $version);
					$installAction->execute();
				} else {
					$pluginName = $plugin->getName();
					$pluginAPI = "{$versionAPI["from"]} -> {$versionAPI["to"]}";

					yield self::custom(
						$player,
						Utils::translate("version.not.compare"),
						[
							new Label("warning_label", Utils::translate("version.not.compare.content", ["plugin" => $pluginName, "serverAPI" => $serverAPI, "pluginAPI" => $pluginAPI]))
						]
					);
					return;
				}

				break;
			case self::ACTION_SHOW:
				yield self::showForm($player, $plugin->getName(), $version, $group);
				break;
			default:
				yield self::versionsForm($player, $plugin, $group);
		}
	}

	public static function versionsForm(Player $player, PluginCache $plugin, string $group) : Generator {
		$versions = $plugin->getVersions();
		$pluginName = $plugin->getName();
		$versionChoose = yield from self::menu($player, Utils::translate("version.title", ["plugin" => $pluginName]), Utils::translate("version.content", ["plugin" => $pluginName]), array_map(function(string $version){
			return new MenuOption($version);
		}, $versions));
		if(!is_null($versionChoose)) {
			yield self::actionForm($player, $plugin, $versions[$versionChoose], $group);
		}
	}

	public static function showForm(Player $player, string $pluginName, string $pluginVersion, string $group) : Generator {
		$plugin = PluginsPool::getPluginCacheByName($pluginName);
		$version = $plugin?->getVersion($pluginVersion);
		if($plugin == null || $version == null) {
			return;
		}

		$pluginHomepage = $plugin->getHomePageByVersion($pluginVersion);
		$pluginLicense = $plugin->getLicense();
		$pluginDownloads = $plugin->getDownloads();
		$pluginScore = $plugin->getScore();
		$deps = array_map(function ($item) {
			/** @var array<string> $item */
			return $item["name"] . " v" . $item["version"];
		}, $version->getDepends());
		$deps = implode(", ", $deps);
		$size = $version->getSize();
		$pluginAPI = $version->getAPI();
		$descriptions = $version->getDescriptions();
		$information = Utils::translate("information.content", [
			"version" => $pluginVersion,
			"homepage" => $pluginHomepage,
			"license" => $pluginLicense,
			"downloads" => $pluginDownloads,
			"score" => $pluginScore,
			"api_from" => $pluginAPI["from"],
			"api_to" => $pluginAPI["to"],
			"depends" => $deps,
			"size" => $size
		]);
		$descriptionChoose = yield from self::menu($player, $pluginName, $information,
			array_map(function($keyDescription){
				return new MenuOption($keyDescription);
			}, array_keys($descriptions))
		);
		if(!is_null($descriptionChoose)) {
			$keyDescription = array_keys($descriptions)[$descriptionChoose];
			yield self::custom($player, $keyDescription, [new Label("des", $descriptions[$keyDescription])]);
			yield self::showForm($player, $pluginName, $pluginVersion, $group);
		}
	}

	/**
	 * @param CustomFormElement[] $elements
	 */
	public static function custom(Player $player, string $title, array $elements) : Generator {
		return yield from Await::promise(function (Closure $resolve) use ($elements, $title, $player) {
			$player->sendForm(new CustomForm(
				$title, $elements,
				function(Player $player, CustomFormResponse $result) use ($resolve) : void {
					$resolve($result);
				},
				function(Player $player) use ($resolve) : void {
					$resolve(null);
				})
			);
		});
	}

	/**
	 * @param MenuOption[] $options
	 */
	public static function menu(Player $player, string $title, string $text, array $options) : Generator {
		return yield from Await::promise(function (Closure $resolve) use ($text, $player, $title, $options) {
			$player->sendForm(new MenuForm(
				$title, $text, $options,
				function (Player $player, int $selectedOption) use ($resolve) : void {
					$resolve($selectedOption);
				},
				function (Player $player) use ($resolve) : void {
					$resolve(null);
				}
			));
		});
	}

	public static function modal(Player $player, string $title, string $text, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no") : Generator {
		return yield from Await::promise(function(Closure $resolve) use ($noButtonText, $text, $yesButtonText, $title, $player) {
			$player->sendForm(new ModalForm(
				$title, $text,
				function (Player $player, bool $choice) use ($resolve) : void {
					$resolve($choice);
				},
				$yesButtonText, $noButtonText
			));
		});
	}
}
