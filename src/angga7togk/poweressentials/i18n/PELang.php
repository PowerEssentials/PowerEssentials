<?php

namespace angga7togk\poweressentials\i18n;

use angga7togk\poweressentials\PowerEssentials;
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

  public const LANGUAGE_EXTENSION = "ini";
  public const FALLBACK_LANGUAGE = "en_us";

  private static string $consoleLocale = self::FALLBACK_LANGUAGE;
  public function __construct(SplFileInfo $file)
  {
    $locale = $file->getBasename("." . PELang::LANGUAGE_EXTENSION);
    parent::__construct($locale, $file->getPath() . DIRECTORY_SEPARATOR, self::FALLBACK_LANGUAGE);
    $this->stringArrayMultitonConstruct($locale);
  }

  public function translateString(string $str, array $params = [], ?string $onlyPrefix = null): string
  {
    $result = parent::translateString($str, $params, $onlyPrefix);
    if (stripos(trim($str), "error") !== false) {
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
