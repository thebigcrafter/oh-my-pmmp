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
use function version_compare;

class Plugin
{
	/**
	 * @var array<string, PluginVersion> $versions
	 */
    private array $versions;

    public function __construct(
        private readonly string $license,
    ) {
    }

	public function addVersion(string $version, PluginVersion $info) : void
	{
		$this->versions[$version] = $info;
	}

    /**
     * Get plugin's license
     */
    public function getLicense() : string
    {
        return $this->license;
    }
    /**
     * Get available versions
     *
     * @return array<string, PluginVersion>
     */
    public function getVersions() : array
    {
        return $this->versions;
    }

    /**
     * Return an array of version. Example: ["1.0.0", "1.0.1"]
     *
     * @return string[]
     */
    public function getVersionsOnly() : array
    {
        return array_keys($this->getVersions());
    }

    /**
     * Return the latest version if no specific version is provided.
     *
     * @return array{version: string, plugin: ?PluginVersion}
     */
    public function getVersion(string $version = null) : array
    {
        if (isset($version)) {
            return ["version" => $version, "plugin" => $this->versions[$version] ?? null];
        }

        return $this->getLatestVersion();
    }

	/** @return array{version: string, plugin: PluginVersion} */
	public function getLatestVersion() : array
	{
		$latestVersion = null;
		foreach ($this->getVersionsOnly() as $version) {
			if ($latestVersion === null || version_compare($version, $latestVersion, '>')) {
				$latestVersion = $version;
			}
		}

		return [
			"version" => (string) $latestVersion,
			"plugin" => $this->versions[$latestVersion]
		];
	}
}
