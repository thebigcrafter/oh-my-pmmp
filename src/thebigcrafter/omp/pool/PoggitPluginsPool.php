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

namespace thebigcrafter\omp\pool;

use thebigcrafter\omp\types\Plugin;

/**
 * Items in pool are saved following this structure: ["plugin name" => new Plugin()];
 */

class PoggitPluginsPool
{
    /** @var array{name: string, plugin: Plugin} $pool */
    private static array $pool = [];

    /**
     * @return array{name: string, plugin: Plugin}
     */
    public static function getPool() : array
    {
        return self::$pool;
    }

    public static function addItem(string $name, Plugin $plugin) : void
    {
        self::$pool[$name] = $plugin;
    }

    /**
     * Find an item by it `name`, return `null` if it hasn't existed
     */
    public static function getItem(string $name) : Plugin|null
    {
        return self::$pool[$name] ?? null;
    }

    /**
     * Check if an item exists or not
     */
    public static function hasItem(string $name) : bool
    {
        return isset(self::$pool[$name]);
    }

    /**
     * Remove item by it name
     */
    public static function removeItem(string $name) : void
    {
        unset(self::$pool[$name]);
    }
}
