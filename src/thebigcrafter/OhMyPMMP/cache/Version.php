<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\cache;

final class Version {

	/** @phpstan-var array{from: string, to: string} $api */
	private array $api;

	/** @phpstan-var array<array{name: string, version: string, depRelId: int, isHard: bool}> $deps */
	private array $deps;

	/**
	 * @param array{from: string, to: string}                                          $api
	 * @param array<array{name: string, version: string, depRelId: int, isHard: bool}> $deps
	 */
	public function __construct(
		private string $version,
		private string $artifact_url,
		array $api,
		array $deps){
		$this->api = $api;
		$this->deps = $deps;
	}

	public function getVersion() : string {
		return $this->version;
	}

	public function getArtifactUrl() : string {
		return $this->artifact_url;
	}

	/**
	 * @return array{from: string, to: string}
	 */
	public function getAPI() : array {
		return $this->api;
	}

	/**
	 * @return array<array{name: string, version: string, depRelId: int, isHard: bool}>
	 */
	public function getDepends() : array {
		return $this->deps;
	}
}
