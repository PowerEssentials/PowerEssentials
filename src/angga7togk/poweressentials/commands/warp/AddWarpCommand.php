<?php

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use angga7togk\poweressentials\utils\ValidationUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AddWarpCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('addwarp', 'add warp on server', '/addwarp <warpname>', ['createwarp']);
    $this->setPrefix("warp.prefix");
    $this->setPermission("addwarp");
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

    if (!ValidationUtils::isValidString($args[0])) {
      $sender->sendMessage($prefix . $lang->translateString('error.invalid.name'));
      return;
    }

    $mgr = PowerEssentials::getInstance()->getDataManager();

    if ($mgr->warpExists($args[0])) {
      $sender->sendMessage($prefix . $lang->translateString('error.exists', [$args[0]]));
      return;
    }

    $mgr->addWarp($args[0], $sender->getPosition());
    $sender->sendMessage($prefix . $lang->translateString('warp.add', [$args[0]]));
  }
}
