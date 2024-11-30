<?php

namespace angga7togk\poweressentials\config;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\GameMode;
use pocketmine\utils\Config;

class PEConfig
{
  private static Config $config;

  public static function init()
  {
    PowerEssentials::getInstance()->saveDefaultConfig();
    self::$config = PowerEssentials::getInstance()->getConfig();
  }
  public static function getLang(): string
  {
    return self::$config->get("language", "en");
  }

  public static function isGamemodeJoin(): bool
  {
    return self::$config->get("gamemode-join-enable");
  }

  public static function getGamemodeJoin(): ?GameMode
  {
    return GameMode::fromString(self::$config->get("gamemode-join"));
  }

  public static function isSpawnLobbyJoin(): bool
  {
    return self::$config->get("spawn-lobby-join");
  }

  public static function isAntiNamespace(): bool
  {
    return self::$config->get("anti-namespace");
  }

  public static function isBlacklistNickname(string $nick): bool
  {
    foreach (self::$config->get("blacklist-nicknames") as $nickBL) {
      if (strpos($nick, $nickBL)) return true;
    }
    return false;
  }

  public static function getMaxCharNickname(): int
  {
    return (int) self::$config->get("nickname-max-char");
  }


  public static function isCommandDisabled(string $commandKey): bool
  {
    return in_array($commandKey, self::$config->get("disable-commands"));
  }

  public static function isWorldBlacklistSetHome(string $world): bool
  {
    return in_array($world, self::$config->get("home-world-blacklists"));
  }

  public static function isHomePermissionLimit(): bool
  {
    return self::$config->get("home-permission-limit");
  }

  public static function getHomePermissionDefaultLimit(): int
  {
    return (int) self::$config->get("home-permission-default-limit");
  }

  public static function getHomePermissionLimits(): array
  {
    return self::$config->get("home-permission-limits");
  }

  public static function isShowCoordinates(): bool
  {
    return self::$config->get("show-coordinates");
  }
}
