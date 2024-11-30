<?php

namespace angga7togk\poweressentials\commands\home;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\user\HomeManager;
use angga7togk\poweressentials\utils\ValidationUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SetHomeCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("sethome", "set home", "/sethome <name>");
    $this->setPermission("sethome");
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

    if (!ValidationUtils::isValidString($args[0])) {
      $sender->sendMessage($prefix . $message['general']['invalid-name']);
      return;
    }
    $worldName = $sender->getWorld()->getFolderName();
    if (PEConfig::isWorldBlacklistSetHome($worldName)) {
      $sender->sendMessage($prefix . strtr($msg['blacklist'], ["{world}" => $worldName]));
      return;
    }

    $homeName = $args[0];
    $mgr = new HomeManager($sender);
    if (PEConfig::isHomePermissionLimit()) {
      if ($mgr->getHomeCount() >= $max = $mgr->getHomeLimit()) {
        $sender->sendMessage($prefix . strtr($msg['max-home'], ["{max}" => $max]));
        return;
      }
    }
    $mgr->createHome($homeName, $sender->getPosition());
    $sender->sendMessage($prefix . strtr($msg['set'], ["{home}" => $homeName]));
  }
}
