<?php

namespace angga7togk\poweressentials\task;

use angga7togk\poweressentials\commands\vanish\VanishCommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class VanishTask extends Task
{

  public function onRun(): void
  {
    foreach (Server::getInstance()->getOnlinePlayers() as $vanisher) {
      if (!$vanisher->spawned) continue;

      if (VanishCommand::isVanished($vanisher)) {
        $vanisher->sendActionBarMessage(PELang::fromConsole()->translateString('vanish.hud.message'));
        $vanisher->setSilent(true);
        $vanisher->getXpManager()->setCanAttractXpOrbs(false);
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
          if ($player->hasPermission("poweressentials.vanish.see")) {
            $player->showPlayer($vanisher);
          } else {
            $player->hidePlayer($vanisher);
          }
        }
      }
    }
  }
}
