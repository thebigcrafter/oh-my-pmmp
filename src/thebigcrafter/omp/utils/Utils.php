<?php

namespace thebigcrafter\omp\utils;

use Symfony\Component\Filesystem\Path;
use thebigcrafter\omp\OhMyPMMP;

class Utils
{
	/**
	 * <p>Generate an <b>absolute</b> plugin folder path with it name.</p>
	 * <p>For example, with <i>$name = "oh-my-pmmp"</i>, it will be <i>/root/Server/plugins/oh-my-pmmp</i></p>
	 *
	 * @param string $name
	 * @return string
	 */
	public static function generatePluginFolderPathWithName(string $name): string
	{
		return Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins/$name");
	}

	/**
	 * <p>Generate an <b>absolute</b> plugin PHAR file path with it name.</p>
	 * <p>For example, with <i>$name = "oh-my-pmmp"</i>, it will be <i>/root/Server/plugins/oh-my-pmmp.phar</i></p>
	 *
	 * @param string $name
	 * @return string
	 */
	public static function generatePluginFilePathWithName(string $name): string
	{
		return Path::join(OhMyPMMP::getInstance()->getServer()->getDataPath(), "plugins", "$name.phar");
	}

	/**
	 * <p>Check if the major version in range</p>
	 * <p>For example, if <i>$minVersion = 5</i>, <i>$maxVersion = 5</i>, return true if version is 5.X.X, otherwise false</p>
	 *
	 * @param string $checkVersion
	 * @param string $minVersion
	 * @param string $maxVersion
	 * @return bool
	 */
	public static function isMajorVersionInRange(string $checkVersion, string $minVersion, string $maxVersion) : bool
	{
		$checkMajor = (int) explode('.', $checkVersion)[0];
		$minMajor = (int) explode('.', $minVersion)[0];
		$maxMajor = (int) explode('.', $maxVersion)[0];

		return $checkMajor >= $minMajor && $checkMajor <= $maxMajor;
	}

}