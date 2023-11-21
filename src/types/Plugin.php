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

namespace thebigcrafter\omp\types;

use function array_keys;
use function is_null;
use function version_compare;

class Plugin
{
    /** @var array{version: string, info: PluginVersion} $versions */
    private array $versions;

    public function __construct(
        private string $license,
    ) {
    }

    public function getLicense() : string
    {
        return $this->license;
    }
    /**
     * @return array{version: string, info: PluginVersion}
     */
    public function getVersions() : array
    {
        return $this->versions;
    }

    public function getVersionsOnly() : array {
        return array_keys($this->getVersions());
    }

    /**
     * Return the latest version if no specific version is provided.
     */
    public function getVersion(string $version = null) : PluginVersion|null
    {
        if (!is_null($version)) {
            return $this->versions[$version] ?? null;
        }

        $latestVersion = null;
        foreach ($this->getVersions() as $version) {
            if ($latestVersion === null || version_compare($version, $latestVersion, '>')) {
                $latestVersion = $version;
            }
        }
        return $this->getVersions()[$latestVersion];
    }

    public function addVersion(string $version, PluginVersion $info) : void
    {
        $this->versions[$version] = $info;
    }
}
