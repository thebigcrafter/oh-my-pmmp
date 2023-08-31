<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\cache;

use Generator;
use SplObjectStorage;

final class PluginsPool {

	/** @var SplObjectStorage<PluginCache, mixed> $storage */
	private static SplObjectStorage $storage;

	public static function init() : void {
		/** @phpstan-var SplObjectStorage<PluginCache, mixed> $storage  */
		$storage = new SplObjectStorage();
		self::$storage = $storage;
	}

	public static function add(PluginCache $pluginCache) : void {
		self::$storage->attach($pluginCache);
	}

	/**
	 * @param PluginCache[] $array
	 */
	public static function addMultiple(array $array) : void {
		foreach ($array as $plugin) {
			if ($plugin instanceof PluginCache) {
				self::$storage->attach($plugin);
			}
		}
	}

	/**
	 * @return SplObjectStorage<PluginCache, mixed>
	 */
	public static function getStorage() : SplObjectStorage {
		return self::$storage;
	}

	public static function getNamePlugins() : Generator {
		foreach (self::$storage as $pluginCache) {
			yield $pluginCache->getName();
		}
	}

	public static function getPluginCacheByName(string $name) : ?PluginCache {
		foreach(self::$storage as $pluginCache) {
			if($pluginCache->getName() == $name) {
				return $pluginCache;
			}
		}
		return null;
	}

}
