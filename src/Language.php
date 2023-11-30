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

namespace thebigcrafter\omp;

use Symfony\Component\Filesystem\Filesystem;
use thebigcrafter\omp\lang\Locale;
use function is_dir;
use function mkdir;

class Language
{
    private static ?Locale $language = null;

    public static function loadLanguages() : void
    {
        /** @var string $selectedLanguage */
        $selectedLanguage = OhMyPMMP::getInstance()->getConfig()->get("language");

        self::saveAndLoadLanguageFiles();
        Locale::setLanguageFromJSON($selectedLanguage, OhMyPMMP::getInstance()->getDataFolder() . "lang/$selectedLanguage.json");
    }

    private static function saveAndLoadLanguageFiles() : void
    {
        $fs = new Filesystem();
        $langFolder = OhMyPMMP::getInstance()->getDataFolder() . "lang/";

        if (!is_dir($langFolder)) {
            @mkdir($langFolder);
        }

        foreach (Vars::AVAILABLE_LANGUAGES as $lang) {
            $languageFilePath = OhMyPMMP::getInstance()->getDataFolder() . "lang/$lang.json";

            if (!$fs->exists($languageFilePath)) {
                OhMyPMMP::getInstance()->saveResource("lang/$lang.json");
            }
        }
    }

    public static function getLanguage() : Locale
    {
        /** @var string $selectedLanguage */
        $selectedLanguage = OhMyPMMP::getInstance()->getConfig()->get("language");
        if (self::$language === null) {
            self::$language = new Locale($selectedLanguage);
        }
        return self::$language;
    }

    /**
     * @param string[] $placeholders
     */
    public static function translate(string $key, array $placeholders) : string
    {
        /** @var string $translation */
        $translation = self::getLanguage()->getText($key, $placeholders);
        return $translation;
    }
}
