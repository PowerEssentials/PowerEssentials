<?php

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WarpCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('warp', 'teleport to any warp', '/warp <warpname>', ['warps']);
    $this->setPermission("warp");
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

    $mgr = PowerEssentials::getInstance()->getDataManager();
    if (!isset($args[0])) {
      $sender->sendMessage($prefix . implode(', ', $mgr->getWarpNames()));
      return;
    }

    if (!$mgr->warpExists($args[0])) {
      $sender->sendMessage($prefix . $msg['not-found']);
      return;
    }

    $warpPos = $mgr->getWarp($args[0]);
    if ($warpPos === null) {
      $sender->sendMessage($prefix . $msg['not-found']);
      return;
    }

    $sender->teleport($warpPos);
    $sender->sendMessage($prefix . strtr($msg['teleport'], [
      '{name}' => $args[0]
    ]));
  }
}
