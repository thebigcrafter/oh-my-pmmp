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

namespace thebigcrafter\omp\utils;

use Exception;
use SOFe\AwaitGenerator\Await;

final class Internet {
    public static function fetch(string $url) : \Generator {
        return yield from Await::promise(function (\Closure $resolve, \Closure $reject) use ($url) {
            $res = \pocketmine\utils\Internet::getURL($url, 10, [], $err);

            /**
             * @var string $err
             */
            if($err || $res === null) {
                $reject(new Exception($err));
				return;
            }

            $resolve($res->getBody());
        });
    }
}
