<?php

namespace angga7togk\poweressentials\config;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\GameMode;
use pocketmine\utils\Config;
use pocketmine\world\World;

class PEConfig
{
  private static Config $config;
  private const CONFIG_NEW_VERSION = 1.0;

  public static function init()
  {
    PowerEssentials::getInstance()->saveDefaultConfig();
    self::$config = PowerEssentials::getInstance()->getConfig();
  }

  public static function getNewVersion(): float
  {
    return self::CONFIG_NEW_VERSION;
  }

  public static function getVersion(): float
  {
    return (float) self::$config->get("config-version");
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

  public static function isRandomTeleportAntiWater(): bool
  {
    return self::$config->get("random-teleport-anti-water");
  }

  public static function getRandomTeleportTimeOut(): int
  {
    return self::$config->get('random-teleport-timeout');
  }

  /** @return int[] 
   * @param "x" | "z"  $coordType
   */
  public static function getRandomTeleportRange(string $coordType): array
  {
    return isset(self::$config->get("random-teleport-range")[$coordType]) ? self::$config->get("random-teleport-range")[$coordType] : [];
  }

  public static function isRandomTeleportWorldBlocked(World $world): bool
  {
    $worldName = $world->getFolderName();
    return in_array($worldName, self::$config->get("random-teleport-world-blacklists"));
  }

  public static function getSizeMax(): float
  {
    return (float) self::$config->get("size-max", 5.0);
  }

  public static function isOneSleepEnabled(): bool
  {
    return (bool) self::$config->get("one-sleep-enable", true);
  }

  public static function isOneSleepCancelVote(): bool
  {
    return (bool) self::$config->get("cancel-sleep-vote", true);
  }

  public static function getOneSleepCancelVoteCount(): int
  {
    return (int) self::$config->get("cancel-sleep-vote-count", 3);
  }

  public static function getOneSleepCancelVoteTimeout(): int
  {
    return (int) self::$config->get("cancel-sleep-vote-timeout", 10);
  }
}
