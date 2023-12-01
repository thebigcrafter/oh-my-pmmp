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

namespace thebigcrafter\omp\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use function json_decode;
use function version_compare;

final class CheckForUpdates extends AsyncTask
{
    private string $highestVersion;
    private string $artifactUrl;
    public function __construct(private readonly string $name, private string $currentVersion)
    {
        $this->highestVersion = $currentVersion;
        $this->artifactUrl = "";
    }

    public function onRun() : void
    {
        $res = Internet::getURL("https://poggit.pmmp.io/releases.min.json?name=" . $this->name);

        $releases = (array) json_decode($res->getBody(), true);

        if ($releases !== null) {
            /**
             * @var array{'version': string, 'artifact_url': string} $release
             */
            foreach ($releases as $release) {
                if (version_compare($this->highestVersion, $release["version"], ">")) {
                    continue;
                }

                $this->highestVersion = $release["version"];
                $this->artifactUrl = $release["artifact_url"];
            }
        }

        if ($this->highestVersion !== $this->currentVersion) {
            $this->setResult(false);
        }
    }

    public function onCompletion() : void
    {
        if (!$this->getResult()) {
            $this->artifactUrl .= "/{$this->name}_{$this->highestVersion}.phar";
            OhMyPMMP::getInstance()->getLogger()->notice(
                Language::translate(
                    "messages.new_update",
                    ["name" => $this->name, "version" => $this->highestVersion, "download_url" => $this->artifactUrl]
                )
            );
        }
    }
}
