<?php

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\PowerEssentials;
use angga7togk\poweressentials\utils\ValidationUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AddWarpCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('addwarp', 'add warp on server', '/addwarp <warpname>', ['createwarp']);
    $this->setPermission("addwarp");
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

    if (!ValidationUtils::isValidString($args[0])) {
      $sender->sendMessage($prefix . $message['general']['invalid-name']);
      return;
    }

    $mgr = PowerEssentials::getInstance()->getDataManager();

    if ($mgr->warpExists($args[0])) {
      $sender->sendMessage($prefix . $msg['exists']);
      return;
    }

    $mgr->addWarp($args[0], $sender->getPosition());
    $sender->sendMessage($prefix . strtr($msg['add'], [
      '{name}' => $args[0]
    ]));
  }
}
