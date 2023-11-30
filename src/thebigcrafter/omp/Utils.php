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

namespace thebigcrafter\omp;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use function explode;

class Utils
{
    public static function isMajorVersionInRange(string $checkVersion, string $minVersion, string $maxVersion) : bool
    {
        $checkMajor = (int) explode('.', $checkVersion)[0];
        $minMajor = (int) explode('.', $minVersion)[0];
        $maxMajor = (int) explode('.', $maxVersion)[0];

        return $checkMajor >= $minMajor && $checkMajor <= $maxMajor;
    }

    public static function getPluginsFolder() : string
    {
        return Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins");
    }

    /**
     * Check plugin by looking for it PHAR file
     */
    public static function doesPluginExist(string $name) : bool
    {
        $fs = new Filesystem();
        return $fs->exists(self::getPluginFilePath($name));
    }

    /**
     * Get it PHAR file path
     */
    public static function getPluginFilePath(string $name) : string
    {
        return Path::join(self::getPluginsFolder(), "$name.phar");
    }
}
