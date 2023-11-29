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

namespace thebigcrafter\omp\trait;

use thebigcrafter\omp\OhMyPMMP;

trait SingletonTrait{
    /** @var OhMyPMMP|null */
    private static $instance = null;

    private static function make() : OhMyPMMP{
		// @phpstan-ignore-next-line
        return new self();
    }

    public static function getInstance() : OhMyPMMP{
        if(self::$instance === null){
            self::$instance = self::make();
        }
        return self::$instance;
    }

    public static function setInstance(OhMyPMMP $instance) : void{
        self::$instance = $instance;
    }
}
