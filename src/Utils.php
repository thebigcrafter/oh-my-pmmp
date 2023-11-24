<?php

namespace thebigcrafter\omp;

class Utils
{
	public static function isMajorVersionInRange($checkVersion, $minVersion, $maxVersion)
	{
		$checkMajor = (int) explode('.', $checkVersion)[0];
		$minMajor = (int) explode('.', $minVersion)[0];
		$maxMajor = (int) explode('.', $maxVersion)[0];

		return $checkMajor >= $minMajor && $checkMajor <= $maxMajor;
	}
}
