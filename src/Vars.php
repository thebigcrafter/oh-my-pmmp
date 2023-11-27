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

final class Vars {
    public const POGGIT_REPO_URL = "https://poggit.pmmp.io/releases.min.json?fields=name,version,artifact_url,html_url,license,downloads,score,api,deps,description_url,changelog_url";
    public const AVAILABLE_LANGUAGES = ["en_US"];
}
