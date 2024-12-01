<?php

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DelWarpCommand extends PECommand {

  public function __construct()
  {
    parent::__construct('delwarp', 'delete warp from server', '/delwarp <warpname>', ['removewarp']);
    $this->setPrefix('warp.prefix');
    $this->setPermission("delwarp");
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

    $mgr = PowerEssentials::getInstance()->getDataManager();
    if (!$mgr->warpExists($args[0])) {
      $sender->sendMessage($prefix . $lang->translateString('error.null'));
      return;
    }

    $mgr->removeWarp($args[0]);
    $sender->sendMessage($prefix . $lang->translateString('warp.del', [$args[0]]));
  }
}