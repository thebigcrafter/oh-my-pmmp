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
use pocketmine\utils\InternetRequestResult;
use thebigcrafter\omp\Language;
use thebigcrafter\omp\OhMyPMMP;
use thebigcrafter\omp\pool\PoggitPluginsPool;
use thebigcrafter\omp\types\API;
use thebigcrafter\omp\types\Dependency;
use thebigcrafter\omp\types\Plugin;
use thebigcrafter\omp\types\PluginVersion;
use thebigcrafter\omp\Utils;
use thebigcrafter\omp\Vars;
use function array_map;
use function count;
use function json_decode;
use function strval;

class FetchDataTask extends AsyncTask
{
    public function onRun() : void
    {
        $res = Internet::getURL(Vars::POGGIT_REPO_URL);

        if (!$res instanceof InternetRequestResult) {
            return;
        }

        $this->setResult(json_decode($res->getBody(), true));
    }

    public function onCompletion() : void
    {
        /** @var array<string, array<string>> $data */
        $data = $this->getResult();

        foreach ($data as $pl) {
            if (!isset($pl["api"][0])) {
                continue;
            }

            // @phpstan-ignore-next-line
            if (OhMyPMMP::getInstance()->getConfig()->get("skipIncompatiblePlugins") && !Utils::isMajorVersionInRange(OhMyPMMP::getInstance()->getServer()->getApiVersion(), $pl["api"][0]["from"], $pl["api"][0]["to"])) {
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

        OhMyPMMP::getInstance()->getLogger()->info(Language::translate("messages.pool.fetched", ["amount" => count(PoggitPluginsPool::getPool())]));
    }
}
