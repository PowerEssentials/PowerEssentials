<?php

namespace angga7togk\poweressentials\language;

use angga7togk\poweressentials\PowerEssentials;
use IvanCraft623\languages\Language;
use IvanCraft623\languages\Translator;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;

class LanguageManager
{

    private const DEFAULT_LANGUAGE = "en_US";

    /** @var Translator $translator */
    private static Translator $translator;

    /**
     * @return void
     */
    public static function init(): void {
        self::$translator = new Translator(PowerEssentials::getInstance());

        $files = glob(PowerEssentials::getInstance()->getDataFolder() . "languages" . DIRECTORY_SEPARATOR . "*.ini");
        if ($files === false) {
            throw new \RuntimeException("Failed to get languages files");
        }

        foreach ($files as $file) {
            $locale = basename($file, ".ini");
            $content = parse_ini_file($file, false, INI_SCANNER_RAW);
            if ($content === false) {
                throw new AssumptionFailedError("Missing or inaccessible required resource files");
            }
            $data = array_map('\stripcslashes', $content);
            self::$translator->registerLanguage(new Language($locale, $data));
        }

        $l = PowerEssentials::getInstance()->getConfig()->get("languages", self::DEFAULT_LANGUAGE);
        $lang = self::$translator->getLanguage($l) ?? throw new \InvalidArgumentException("Language $l not found");
        self::$translator->setDefaultLanguage($lang);
    }

    public static function getTranslator(): Translator {
        return self::$translator;
    }
}