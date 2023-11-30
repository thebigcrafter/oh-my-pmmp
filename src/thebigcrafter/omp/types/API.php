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

class API {
    public function __construct(private string $from, private string $to) {}

    public function getMinimumSupportedVersion() : string {
        return $this->from;
    }

    public function getMaximumSupportedVersion() : string {
        return $this->to;
    }
}
