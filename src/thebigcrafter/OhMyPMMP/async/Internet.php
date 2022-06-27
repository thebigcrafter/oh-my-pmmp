<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use pocketmine\utils\InternetException;
use React\Promise\Deferred;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class Internet {
    /**
     * Get resource from the URL
     *
     * @param string $url
     *
     * @return PromiseInterface|Promise
     */
    public static function fetch(string $url): PromiseInterface|Promise
    {
        $deferred = new Deferred();

        try {
            $res = \pocketmine\utils\Internet::getURL($url);
            if($res instanceof \pocketmine\utils\InternetRequestResult){
                $deferred->resolve($res->getBody());
            }
        } catch (InternetException $e) {
            $deferred->reject($e);
        }

        return $deferred->promise();
    }
}
