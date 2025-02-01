<?php

/*
 *   ____                        _____                    _   _       _
 *  |  _ \ _____      _____ _ __| ____|___ ___  ___ _ __ | |_(_) __ _| |___
 *  | |_) / _ \ \ /\ / / _ \ '__|  _| / __/ __|/ _ \ '_ \| __| |/ _` | / __|
 *  |  __/ (_) \ V  V /  __/ |  | |___\__ \__ \  __/ | | | |_| | (_| | \__ \
 *  |_|   \___/ \_/\_/ \___|_|  |_____|___/___/\___|_| |_|\__|_|\__,_|_|___/
 *
 *
 * This file is part of PowerEssentials plugins.
 *
 * (c) Angga7Togk <kiplihode123321@gmail.com>
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace angga7togk\poweressentials\i18n;

use angga7togk\poweressentials\utils\StringArrayMultiton;
use pocketmine\lang\Language;
use pocketmine\utils\TextFormat;
use SplFileInfo;

/** Credit Code: https://github.com/fuyutsuki/Texter/blob/122f9b45a4896c51eb5b7f4fc0aa479ea0df56a7/src/jp/mcbe/fuyutsuki/Texter/i18n/TexterLang.php */
class PELang extends Language
{
    use StringArrayMultiton {
        StringArrayMultiton::__construct as stringArrayMultitonConstruct;
    }

    public const LANGUAGE_EXTENSION = 'ini';
    public const FALLBACK_LANGUAGE  = 'en_us';

    private static string $consoleLocale = self::FALLBACK_LANGUAGE;
    public function __construct(SplFileInfo $file)
    {
        $locale = $file->getBasename('.' . PELang::LANGUAGE_EXTENSION);
        parent::__construct($locale, $file->getPath() . DIRECTORY_SEPARATOR, self::FALLBACK_LANGUAGE);
        $this->stringArrayMultitonConstruct($locale);
    }

    public function translateString(string $str, array $params = [], ?string $onlyPrefix = null): string
    {
        $result = parent::translateString($str, $params, $onlyPrefix);
        if (stripos(trim($str), 'error') !== false) {
            return TextFormat::RED . $result;
        }

        return $result;
    }

    public static function setConsoleLocale(string $locale)
    {
        self::$consoleLocale = $locale;
    }

    public static function fromConsole(): PELang
    {
        return self::fromLocale(self::$consoleLocale);
    }

    public static function fromLocale(string $locale): PELang
    {
        return self::$instances[strtolower($locale)] ?? self::$instances[self::FALLBACK_LANGUAGE];
    }
}
