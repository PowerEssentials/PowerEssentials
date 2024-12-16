<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\player\Player;
use pocketmine\Server;

class KickAllCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('kickall', 'Kick all players', '/kickall [reason]');
    $this->setPrefix('kickall.prefix');
    $this->setPermission('kickall');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    foreach (Server::getInstance()->getOnlinePlayers() as $player) {
      if ($sender instanceof Player) {
        if ($sender->getName() === $player->getName()) continue;
      }
      if (isset($args[0])) {
        $player->kick(implode(" ", $args));
      } else {
        $player->kick();
      }
    }
    $sender->sendMessage($prefix . $lang->translateString('kickall.success'));
  }
}
