<?php

namespace thebigcrafter\omp\lang;

use Exception;

/**
 * This class was made base on utopia-php/locale
 * GitHub: https://github.com/utopia-php/locale
 */
final class Locale
{
	/**
	 * @var array<string, array<string, string>>
	 */
	protected static $languages = [];

	/**
	 * Throw Exceptions?
	 *
	 * @var bool
	 */
	public static $exceptions = true;

	/**
	 * Default Locale
	 *
	 * @var string
	 */
	public $default;

	public function __construct(string $default)
	{
		if (!array_key_exists($default, self::$languages)) {
			throw new Exception("Locale not found");
		}

		$this->default = $default;
	}

	public static function setLanguageFromJSON(string $name, string $path): void
	{
		if (!file_exists($path)) {
			throw new Exception("Translation file not found.");
		}

		/** @var array<string, string> $translations */
		$translations = json_decode(file_get_contents($path) ?: "", true);
		self::$languages[$name] = $translations;
	}

	/**
	 * @param  array<string, string|int>  $placeholders
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function getText(string $key, array $placeholders = [])
	{
		$default = "{{$key}}";

		if (!\array_key_exists($key, self::$languages[$this->default])) {
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