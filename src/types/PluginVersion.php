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

class PluginVersion {
    /**
     * @param Dependency[] $deps
     */
    public function __construct(
        private string $html_url,
        private string $artifact_url,
        private int $downloads,
        private ?int $score,
        private string $description_url,
        private string $changelog_url,
        private API $api,
        private array $deps = []
    ) {}

    public function getHtmlUrl() : string {
        return $this->html_url;
    }
    public function getArtifactUrl() : string {
        return $this->artifact_url;
    }
    public function getDownloads() : int {
        return $this->downloads;
    }
    public function getScore() : int {
        return $this->score ? $this->score : 0;
    }
    public function getDescriptionUrl() : string {
        return $this->description_url;
    }
    public function getChangelogUrl() : string {
        return $this->changelog_url;
    }

    public function getSupportedAPI() : API {
        return $this->api;
    }

    /**
     * @return Dependency[]
     */
    public function getDependencies() : array {
        return $this->deps;
    }
}
