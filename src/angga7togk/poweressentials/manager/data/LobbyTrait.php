<?php

namespace angga7togk\poweressentials\manager\data;

use pocketmine\world\Position;

trait LobbyTrait
{
  public function setLobby(Position $pos): void
  {
    $x = $pos->getX();
    $y = $pos->getY();
    $z = $pos->getZ();
    $world = $pos->getWorld()->getFolderName();
    $this->getData()->set("lobby", "$x:$y:$z:$world");
    $this->getData()->save();
  }

  public function getLobby(): ?Position
  {
    if (!$this->getData()->exists("lobby")) return null;
    $lobby = $this->getData()->get("lobby");
    $lobby = explode(":", $lobby);

    $world = $this->plugin->getServer()->getWorldManager()->getWorldByName($lobby[3]);
    if ($world === null) return null;
    if (!$world->isLoaded()) return null;
    return new Position((int) $lobby[0], (int) $lobby[1], (int) $lobby[2], $world);
  }
}
