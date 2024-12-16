<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\Server;

class GetPositionCommand extends PECommand{


  public function __construct(){
    parent::__construct('getposition', 'get position of player', '/getposition <player>', ['getpos']);
    $this->setPrefix('getpos.prefix');
    $this->setPermission('getpos');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if(!isset($args[0])){
      $sender->sendMessage($prefix . $this->getUsage());
      return;
    }

    $player = Server::getInstance()->getPlayerExact($args[0]);
    if($player === null){
      $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
      return;
    }

    $sender->sendMessage($prefix . $lang->translateString('getpos.success', [
      $player->getName(),
      $player->getWorld()->getDisplayName(),
      $player->getPosition()->getX(),
      $player->getPosition()->getY(),
      $player->getPosition()->getZ()
    ]));
  }
}