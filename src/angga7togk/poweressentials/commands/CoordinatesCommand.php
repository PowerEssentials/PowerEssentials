<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;

class CoordinatesCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("coordinates", "get coordinates", "/coordinates", ['coords', 'coord', 'coordinate']);
    $this->setPermission("coordinates");
  }

  public function run(CommandSender $sender, array $message, array $args): void
  {
    $msg = $message['coordinates'];
    $prefix = $msg['prefix'];

    if (!($sender instanceof Player)) {
      $sender->sendMessage($prefix . $message['general']['cmd-console']);
      return;
    }

    if (!$this->testPermission($sender)) {
      $sender->sendMessage($prefix . $message['general']['no-perm']);
      return;
    }
    $mgr = PowerEssentials::getInstance()->getUserManager($sender);
    if (!PEConfig::isShowCoordinates()) {
      $sender->sendMessage($prefix . $msg['off']);
      return;
    }

    if ($mgr->getCoordinatesShow()) {
      $pk = new GameRulesChangedPacket();
      $pk->gameRules = ["showcoordinates" => new BoolGameRule(false, false)];
      $sender->getNetworkSession()->sendDataPacket($pk);
      $sender->sendMessage($prefix . $msg['disable']);
      $mgr->setCoordinatesShow(false);
      return;
    }else{
      $pk = new GameRulesChangedPacket();
      $pk->gameRules = ["showcoordinates" => new BoolGameRule(true, false)];
      $sender->getNetworkSession()->sendDataPacket($pk);
      $sender->sendMessage($prefix . $msg['enable']);
      $mgr->setCoordinatesShow(true);
    }
  }
}
