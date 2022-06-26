<?php

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use React\Promise\Deferred;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class Filesystem {
    /**
     * Write a data to a file
     *
     * @param string $file
     * @param string $data
     *
     * @return PromiseInterface|Promise
     */
    public static function writeFile(string $file, string $data): PromiseInterface|Promise
    {
        $deferred = new Deferred();

        try {
            file_put_contents($file, $data);

            $deferred->resolve();
        } catch (\Throwable $e) {
            $deferred->reject($e);
        }
        return $deferred->promise();
    }

    /**
     * Unlink Phar file
     *
     * @param string $file
     *
     * @return Promise|PromiseInterface
     */
    public static function unlinkPhar(string $file): PromiseInterface|Promise
    {
        $deferred = new Deferred();

        try {
            \Phar::unlinkArchive($file);

            $deferred->resolve();
        } catch (\Throwable $e) {
            $deferred->reject($e);
        }

        return $deferred->promise();
    }
}