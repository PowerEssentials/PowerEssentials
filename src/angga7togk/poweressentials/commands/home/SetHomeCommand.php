<?php

namespace angga7togk\poweressentials\commands\home;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use angga7togk\poweressentials\utils\ValidationUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SetHomeCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("sethome", "set home", "/sethome <name>");
    $this->setPrefix('home.prefix');
    $this->setPermission("sethome");
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

    if (!ValidationUtils::isValidString($args[0])) {
      $sender->sendMessage($prefix . $lang->translateString('error.invalid.name'));
      return;
    }
    $worldName = $sender->getWorld()->getFolderName();
    if (PEConfig::isWorldBlacklistSetHome($worldName)) {
      $sender->sendMessage($prefix . $lang->translateString('error.blacklist'), ["World $worldName"]);
      return;
    }

    $homeName = $args[0];
    $mgr = PowerEssentials::getInstance()->getUserManager($sender);
    if (PEConfig::isHomePermissionLimit()) {
      if ($mgr->getHomeCount() >= $max = $mgr->getHomeLimit()) {
        $sender->sendMessage($prefix . $lang->translateString('home.error.max.home', [$max]));
        return;
      }
    }

    if ($mgr->homeExists($homeName)) {
      $sender->sendMessage($prefix . $lang->translateString('error.exists', [$homeName]));
      return;
    }
    $mgr->createHome($homeName);
    $sender->sendMessage($prefix . $lang->translateString('home.set', [$homeName]));
  }
}
