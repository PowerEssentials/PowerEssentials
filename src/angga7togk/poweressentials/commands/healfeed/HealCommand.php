<?php

namespace angga7togk\poweressentials\commands\healfeed;

use angga7togk\poweressentials\commands\PECommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class HealCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('heal', 'healing', '/heal');
    $this->setPermission('heal');
  }

  public function run(CommandSender $sender, array $message, array $args): void
  {
    $msg = $message['healfeed'];
    $prefix = $msg['prefix'];

    if (!$this->testPermission($sender)) {
      $sender->sendMessage($prefix . $message['general']['no-perm']);
      return;
    }

    if (isset($args[0])) {
      if (!$sender->hasPermission(self::PREFIX_PERMISSION . 'heal.other')) {
        $sender->sendMessage($prefix . $message['general']['no-perm']);
        return;
      }
      $target = Server::getInstance()->getPlayerExact($args[0]);
      if ($target == null) {
        $sender->sendMessage($prefix . $message['general']['player-null']);
        return;
      }
      $target->setHealth($target->getMaxHealth());
      $target->sendMessage($prefix . str_replace('{player}', $sender->getName(), $msg['heal']));
      $sender->sendMessage($prefix . str_replace('{player}', $target->getName(), $msg['heal-other']));
    } else {
      if ($sender instanceof Player) {
        $sender->setHealth($sender->getMaxHealth());
        $sender->sendMessage($prefix . $msg['heal']);
      } else {
        $sender->sendMessage($prefix . $message['general']['cmd-console']);
      }
    }
  }
}
