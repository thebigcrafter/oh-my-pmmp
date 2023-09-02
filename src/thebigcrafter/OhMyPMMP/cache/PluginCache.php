<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\cache;

use function count;
use function sort;

class PluginCache {

	/**
	 * @param Version[] $versions
	 */
	public function __construct(
		private string $name,
		private string $license,
		private int $downloads,
		private array $versions,
		private int $score
	) {}

	public function getName() : string {
		return $this->name;
	}

	public function getLicense() : string {
		return $this->license;
	}

	public function getDownloads() : int {
		return $this->downloads;
	}

	/**
	 * @return string[]
	 */
	public function getVersions() : array {
		$versions = [];
		foreach ($this->versions as $version) {
			$versions[] = $version->getVersion();
		}
		return $versions;
	}

	public function getScore() : int {
		return $this->score;
	}

	public function pushVersion(Version $version) : void {
		$this->versions[$version->getVersion()] = $version;
	}

	public function getVersion(string $version) : ?Version {
		if($version === "latest") {
			$version = $this->getLatestVersion();
		}
		if(isset($this->versions[$version])) {
			$versionObj = $this->versions[$version];
			if($versionObj->getSize() == null) {
				$versionObj->fetchSize();
				$versionObj->fetchDescriptions();
				$this->versions[$version] = $versionObj;
			}
			return $versionObj;
		}
		return null;
	}

	public function getLatestVersion() : string {
		$versions = $this->versions;
		sort($versions);
		$version = $versions[count($versions) - 1];
		return $version->getVersion();
	}

	public function getHomePageByVersion(string $version) : string {
		return "https://poggit.pmmp.io/p/" . $this->getName() . "/" . $version;
	}
}
