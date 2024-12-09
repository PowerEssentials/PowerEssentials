<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\player\Player;

class AFKCommand extends PECommand
{
  /** @var string[] $afk */
  private static array $afk = [];

  public function __construct()
  {
    parent::__construct("afk", "set afk for your self", "/afk", ["away"]);
    $this->setPrefix('afk.prefix');
    $this->setPermission('afk');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!$sender instanceof Player) {
      $sender->sendMessage($prefix . $lang->translateString("error.console"));
      return;
    }

    $nick = $sender->getName();

    if (isset(self::$afk[$nick])) {
      unset(self::$afk[$nick]);
      $sender->sendMessage($prefix . $lang->translateString("afk.disabled"));
      return;
    } else {
      self::$afk[$nick] = $nick;
      $sender->sendMessage($prefix . $lang->translateString("afk.enabled"));

      foreach ($sender->getServer()->getOnlinePlayers() as $player) {
        if ($sender->getName() == $player->getName()) {
          continue;
        }
        $player->sendMessage($prefix . $lang->translateString("afk.broadcast", [$nick]));
      }
    }
  }

  public static function isAfk(Player $player): bool
  {
    return isset(self::$afk[$player->getName()]);
  }

  /** @return string[] */
  public static function getAfk(): array
  {
    return self::$afk;
  }

  public static function disabledAfk(Player $player): void
  {
    unset(self::$afk[$player->getName()]);
  }
}
