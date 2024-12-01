<?php

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WarpCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('warp', 'teleport to any warp', '/warp <warpname>', ['warps']);
    $this->setPrefix("warp.prefix");
    $this->setPermission("warp");
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!($sender instanceof Player)) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }

    $mgr = PowerEssentials::getInstance()->getDataManager();
    if (!isset($args[0])) {
      $sender->sendMessage($prefix . implode(', ', $mgr->getWarpNames()));
      return;
    }

    if (!$mgr->warpExists($args[0])) {
      $sender->sendMessage($prefix . $lang->translateString('error.null'));
      return;
    }

    $warpPos = $mgr->getWarp($args[0]);
    if ($warpPos === null) {
      $sender->sendMessage($prefix . $lang->translateString('error.null'));
      return;
    }

    $sender->teleport($warpPos);
    $sender->sendMessage($prefix . $lang->translateString('warp.teleport', [$args[0]]));
  }
}
