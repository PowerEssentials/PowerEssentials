<?php

namespace angga7togk\poweressentials\commands\home;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DelHomeCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("delhome", "delete home", "/delhome <name>");
    $this->setPermission("delhome");
  }

  public function run(CommandSender $sender, array $message, array $args): void
  {
    $msg = $message['home'];
    $prefix = $msg['prefix'];
    if (!($sender instanceof Player)) {
      $sender->sendMessage($prefix . $message['general']['cmd-console']);
      return;
    }

    if (!isset($args[0])) {
      $sender->sendMessage($prefix . $msg['usage']);
      return;
    }

    if (!$this->testPermission($sender)) {
      $sender->sendMessage($prefix . $message['general']['no-perm']);
      return;
    }

    $homeName = $args[0];
    $mgr = PowerEssentials::getInstance()->getUserManager($sender);
    if (!$mgr->homeExists($homeName)) {
      $sender->sendMessage($prefix . $msg['not-found']);
      return;
    }

    $mgr->deleteHome($homeName);
    $sender->sendMessage($prefix . strtr($msg['del'], ["{home}" => $homeName]));
  }
}
