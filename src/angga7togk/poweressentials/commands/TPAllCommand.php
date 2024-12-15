<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\player\Player;

class TPAllCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("tpall", "Teleported all players", "/tpall [player]");
    $this->setPrefix('tpall.prefix');
    $this->setPermission('tpall');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!$sender instanceof Player) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }

    $target = $sender->getServer()->getPlayerExact($args[0] ?? "") ?? $sender;

    $players = $sender->getServer()->getOnlinePlayers();
    if (count($players) < 2) {
      $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
      return;
    }

    foreach ($players as $player) {
      if ($player->getName() !== $target->getName()) {
        $player->teleport($target->getPosition());
      }
    }

    $sender->sendMessage($prefix . $lang->translateString('tpall.success', [$target->getName()]));
  }
}
