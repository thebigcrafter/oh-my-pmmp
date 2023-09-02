<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\cache;

use thebigcrafter\OhMyPMMP\utils\Internet;
use function array_map;
use function is_null;

final class Version {

	private ?string $fileSize = null;
	/** @phpstan-var  null|array<string, string>*/
	private ?array $descriptions = null;

	/**
	 * @param array{from: string, to: string}                                          $api
	 * @param array<array{name: string, version: string, depRelId: int, isHard: bool}> $deps
	 */
	public function __construct(
		private string $version,
		private string $artifact_url,
		private string $descriptionURL,
		private array $api,
		private array $deps){
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

	public function getSize() : string {
		$size = $this->fileSize;
		return (is_null($size)) ? $this->fetchSize() : $size;
	}

	/**
	 * @return array<string, string>
	 */
	public function getDescriptions() : array {
		$descriptions = $this->descriptions;
		return (is_null($descriptions)) ? $this->fetchDescriptions() : $descriptions;
	}

	/**
	 * @return array<string, string>
	 */
	public function fetchDescriptions() : array {
		$descriptions = Internet::fetchDescription($this->descriptionURL);

		if (is_null($descriptions)) {
			$descriptions = ["No Description" => ""];
		} else {
			$descriptions = array_map(function($value) {
				return (string) $value;
			}, $descriptions);
		}

		$this->descriptions = $descriptions;
		return $descriptions;
	}

	public function fetchSize() : string {
		$size = Internet::fetchRemoteFileSize($this->getArtifactUrl());
		$this->fileSize = $size;
		return $size;
	}
}
