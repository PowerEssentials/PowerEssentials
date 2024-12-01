<?php

namespace angga7togk\poweressentials\commands\healfeed;

use angga7togk\poweressentials\commands\PECommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class FeedCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('feed', 'feeding', '/feed', ['eat']);
    $this->setPermission('feed');
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
      if (!$sender->hasPermission(self::PREFIX_PERMISSION . 'feed.other')) {
        $sender->sendMessage($prefix . $message['general']['no-perm']);
        return;
      }
      $target = Server::getInstance()->getPlayerExact($args[0]);
      if ($target == null) {
        $sender->sendMessage($prefix . $message['general']['player-null']);
        return;
      }
      $target->getHungerManager()->setFood($target->getHungerManager()->getMaxFood());
      $target->sendMessage($prefix . str_replace('{player}', $sender->getName(), $msg['feed']));
      $sender->sendMessage($prefix . str_replace('{player}', $target->getName(), $msg['feed-other']));
    } else {
      if ($sender instanceof Player) {
        $sender->getHungerManager()->setFood($sender->getHungerManager()->getMaxFood());
        $sender->sendMessage($prefix . $msg['feed']);
      } else {
        $sender->sendMessage($prefix . $message['general']['cmd-console']);
      }
    }
  }
}
