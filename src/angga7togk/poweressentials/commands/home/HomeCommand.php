<?php

namespace angga7togk\poweressentials\commands\home;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class HomeCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("home", "teleport to home", "/home <name>");
    $this->setPermission("home");
  }
  public function run(CommandSender $sender, array $message, array $args): void
  {
    $msg = $message['home'];
    $prefix = $msg['prefix'];
    if (!($sender instanceof Player)) {
      $sender->sendMessage($prefix . $message['general']['cmd-console']);
      return;
    }

    $mgr = PowerEssentials::getInstance()->getUserManager($sender);
    if (!isset($args[0])) {
      $sender->sendMessage($prefix . implode(",", $mgr->getHomeNames()));
      return;
    }

    if (!$this->testPermission($sender)) {
      $sender->sendMessage($prefix . $message['general']['no-perm']);
      return;
    }
    $homeName = $args[0];
    $worldName = $mgr->getHomeData($homeName)["world"];
    if (PEConfig::isWorldBlacklistSetHome($worldName)) {
      $sender->sendMessage($prefix . strtr($msg['blacklist'], ["{world}" => $worldName]));
      return;
    }

    if (!$mgr->homeExists($homeName)) {
      $sender->sendMessage($prefix . $msg['not-found']);
      return;
    }

    $home = $mgr->getHome($homeName);
    if ($home === null) {
      $sender->sendMessage($prefix . $msg['not-found']);
      return;
    }

    $sender->teleport($home);
    $sender->sendMessage($prefix . strtr($msg['teleport'], ["{home}" => $homeName]));
  }
}
