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

namespace thebigcrafter\omp\lang;

use Exception;
use function array_key_exists;
use function file_exists;
use function file_get_contents;
use function json_decode;
use function str_replace;

/**
 * This class was made base on utopia-php/locale
 * GitHub: https://github.com/utopia-php/locale
 */
final class Locale
{
    /** @var array<string, array<string, string>> */
    protected static array $languages = [];

    /**
     * Throw Exceptions?
     *
     * @var bool
     */
    public static bool $exceptions = true;

    /**
     * Default Locale
     *
     * @var string
     */
    public string $default;

	/**
	 * @throws Exception
	 */
	public function __construct(string $default)
    {
        if (!array_key_exists($default, self::$languages)) {
            throw new Exception("Locale not found");
        }

        $this->default = $default;
    }

	/**
	 * @throws Exception
	 */
	public static function setLanguageFromJSON(string $name, string $path) : void
    {
        if (!file_exists($path)) {
            throw new Exception("Translation file not found.");
        }

        /** @var array<string, string> $translations */
        $translations = json_decode(file_get_contents($path) ?: "", true);
        self::$languages[$name] = $translations;
    }

    /**
     * @param array<string, string|int> $placeholders
     * @return array|string|string[]
	 *
     * @throws Exception
     */
    public function getText(string $key, array $placeholders = []): array|string
	{
        $default = "{{$key}}";

        if (!array_key_exists($key, self::$languages[$this->default])) {
            if (self::$exceptions) {
                throw new Exception("Key named $key not found");
            }

            return $default;
        }

        $translation = self::$languages[$this->default][$key];

        foreach ($placeholders as $placeholderKey => $placeholderValue) {
            $translation = str_replace("{{" . $placeholderKey . "}}", (string) $placeholderValue, $translation);
        }

        return $translation;
    }
}
