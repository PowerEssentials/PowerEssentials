<?php

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DelWarpCommand extends PECommand {

  public function __construct()
  {
    parent::__construct('delwarp', 'delete warp from server', '/delwarp <warpname>', ['removewarp']);
    $this->setPermission("delwarp");
  }

  public function run(CommandSender $sender, array $message, array $args): void
  {
    $msg = $message['warp'];
    $prefix = $msg['prefix'];

    if (!($sender instanceof Player)) {
      $sender->sendMessage($prefix . $message['general']['cmd-console']);
      return;
    }

    if (!$this->testPermission($sender)) {
      $sender->sendMessage($prefix . $message['general']['no-perm']);
      return;
    }

    if (!isset($args[0])) {
      $sender->sendMessage($prefix . $this->getUsage());
      return;
    }

    $mgr = PowerEssentials::getInstance()->getDataManager();
    if (!$mgr->warpExists($args[0])) {
      $sender->sendMessage($prefix . $msg['not-found']);
      return;
    }

    $mgr->removeWarp($args[0]);
    $sender->sendMessage($prefix . strtr($msg['del'], [
      '{name}' => $args[0]
    ]));
  }
}