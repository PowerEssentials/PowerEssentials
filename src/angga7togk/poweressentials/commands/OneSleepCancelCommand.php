<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\Server;

class OneSleepCancelCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("onesleepcancel", "Cancel One Sleep", "/scancel", ["oscancel", "scancel"]);
    $this->setPrefix('onesleep.prefix');
    $this->setPermission('onesleep.cancel');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!$sender instanceof Player) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }

    $dataMgr = PowerEssentials::getInstance()->getDataManager();
    if ($dataMgr->getSlepper() === null) {
      $sender->sendMessage($prefix . $lang->translateString('onesleep.error.sleeper.null'));
      return;
    }
    
    if($dataMgr->getSlepper()->getName() === $sender->getName()){
      $sender->sendMessage($prefix . $lang->translateString('onesleep.error.sleeper.self'));
      return;
    }


    if (!$dataMgr->haveOneSleepVoted($sender)) {
      $dataMgr->addOneSleepVote($sender);

      if($dataMgr->canCancelOneSleep()){
        $dataMgr->getSlepper()->stopSleep();
        $dataMgr->unsetSlepper();
        Server::getInstance()->broadcastMessage($prefix . $lang->translateString('onesleep.error.canceled'));
      }else {
        Server::getInstance()->broadcastMessage($prefix . $lang->translateString('onesleep.vote', [$sender->getName()]));
      }
    }
  }
}
