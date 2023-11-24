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

use Symfony\Component\Filesystem\Path;
use function explode;

class Utils
{
    public static function isMajorVersionInRange($checkVersion, $minVersion, $maxVersion)
    {
        $checkMajor = (int) explode('.', $checkVersion)[0];
        $minMajor = (int) explode('.', $minVersion)[0];
        $maxMajor = (int) explode('.', $maxVersion)[0];

        return $checkMajor >= $minMajor && $checkMajor <= $maxMajor;
    }

    public static function getPluginsFolder() : string {
        return Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins");
    }
}
