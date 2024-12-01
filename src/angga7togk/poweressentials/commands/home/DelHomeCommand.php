<?php

namespace angga7togk\poweressentials\commands\home;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DelHomeCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("delhome", "delete home", "/delhome <name>");
    $this->setPrefix('home.prefix');
    $this->setPermission("delhome");
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!($sender instanceof Player)) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }

    if (!isset($args[0])) {
      $sender->sendMessage($prefix . $this->getUsage());
      return;
    }

    if (!$this->testPermission($sender)) {
      $sender->sendMessage($prefix . $lang->translateString('error.permission'));
      return;
    }

    $homeName = $args[0];
    $mgr = PowerEssentials::getInstance()->getUserManager($sender);
    if (!$mgr->homeExists($homeName)) {
      $sender->sendMessage($prefix . $lang->translateString('error.null'));
      return;
    }

    $mgr->deleteHome($homeName);
    $sender->sendMessage($prefix . $lang->translateString('home.del', [$homeName]));
  }
}
