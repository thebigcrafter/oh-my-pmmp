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

require __DIR__ . "/../vendor/autoload.php";

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use pocketmine\plugin\PluginBase;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\types\API;
use thebigcrafter\omp\types\Dependency;
use thebigcrafter\omp\types\Plugin;
use thebigcrafter\omp\types\PluginVersion;
use function array_map;
use function json_decode;
use function strval;

class OhMyPMMP extends PluginBase
{
    public function onEnable() : void
    {
        $this->fetchData();
    }

    private function fetchData() : void
    {
        $client = HttpClientBuilder::buildDefault();

        $res = $client->request(new Request(Vars::POGGIT_REPO_URL));

        if ($res->getStatus() !== 200) {
            return;
        }

        $data = json_decode($res->getBody()->buffer(), true);

        foreach ($data as $pl) {
            if (!isset($pl["api"][0])) {
                continue;
            }

            if (PoggitPluginsPool::getItem($pl["name"]) === null) {
                PoggitPluginsPool::addItem($pl["name"], new Plugin($pl["license"] ? $pl["license"] : ""));
                PoggitPluginsPool::getItem($pl["name"])->addVersion(
                    $pl["version"],
                    new PluginVersion(
                        $pl["html_url"],
                        $pl["artifact_url"],
                        $pl["downloads"],
                        $pl["score"],
                        $pl["description_url"],
                        $pl["changelog_url"] ? $pl["changelog_url"] : "",
                        new API($pl["api"][0]["from"], $pl["api"][0]["to"]),
                        array_map(function ($dep) {
                            return new Dependency($dep["name"], $dep["version"], strval($dep["depRelId"]), $dep["isHard"]);
                        }, $pl["deps"])
                    )
                );
            } else {
                PoggitPluginsPool::getItem($pl["name"])->addVersion(
                    $pl["version"],
                    new PluginVersion(
                        $pl["html_url"],
                        $pl["artifact_url"],
                        $pl["downloads"],
                        $pl["score"],
                        $pl["description_url"],
                        $pl["changelog_url"] ? $pl["changelog_url"] : "",
                        new API($pl["api"][0]["from"], $pl["api"][0]["to"]),
                        array_map(function ($dep) {
                            return new Dependency($dep["name"], $dep["version"], strval($dep["depRelId"]), $dep["isHard"]);
                        }, $pl["deps"])
                    )
                );
            }
        }
    }
}
