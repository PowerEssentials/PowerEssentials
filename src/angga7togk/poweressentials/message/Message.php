<?php

namespace angga7togk\poweressentials\message;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\utils\Config;

class Message
{
    private static array $messages;

    public static function init()
    {
        $lang = PEConfig::getLang();
        PowerEssentials::getInstance()->saveResource("language/$lang.yml");
        self::$messages = (new Config(PowerEssentials::getInstance()->getDataFolder() . "/language/$lang.yml"))->getAll();
    }

    public static function getMessage(): array
    {
        return self::$messages;
    }
}
