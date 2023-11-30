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

class Dependency
{
    public function __construct(
        private string $name,
        private string $version,
        private string $depRelId,
        private bool $isHard,
    ) {
    }

    public function getName() : string
    {
        return $this->name;
    }
    public function getVersion() : string
    {
        return $this->version;
    }
    public function getDepRelId() : string
    {
        return $this->depRelId;
    }
    public function isHard() : bool
    {
        return $this->isHard;
    }
}
