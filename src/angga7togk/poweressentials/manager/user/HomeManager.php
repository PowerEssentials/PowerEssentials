<?php

namespace angga7togk\poweressentials\manager\user;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\UserManager;
use pocketmine\player\Player;
use pocketmine\world\Position;

class HomeManager extends UserManager
{

  public function __construct(Player $player)
  {
    parent::__construct($player);
  }

  public function getHomeLimit(): int{
    $defaultLimit = PEConfig::getHomePermissionDefaultLimit();
    foreach(PEConfig::getHomePermissionLimits() as $permission => $limit){
      if($this->getPlayer()->hasPermission($permission)) {
        $defaultLimit += $limit;
      }
    }
    return $defaultLimit;
  }

  public function getHomeCount(): int{
    return count($this->getData()->get("homes"));
  }

  public function homeExists(string $homeName): bool
  {
    return isset($this->getData()->get("homes")[$homeName]);
  }

  public function createHome(string $homeName): void
  {
    $pos = $this->getPlayer()->getPosition();
    $x = $pos->getX();
    $y = $pos->getY();
    $z = $pos->getZ();
    $worldName = $pos->getWorld()->getFolderName();
    $this->getData()->setNested("homes.$homeName", "$x:$y:$z:$worldName");
    $this->getData()->save();
  }

  public function deleteHome(string $homeName): void
  {
    $this->getData()->removeNested("homes.$homeName");
    $this->getData()->save();
  }

  public function getHomeData(string $homeName): ?array
  {
    if (!$this->homeExists($homeName)) {
      return null;
    }
    $data = $this->getData()->getNested("homes.$homeName");
    $data = explode(":", $data);
    return [
      "x" => (float) $data[0],
      "y" => (float) $data[1],
      "z" => (float) $data[2],
      "world" => $data[3],
    ];
  }

  public function getHome(string $homeName): ?Position
  {
    $data = $this->getHomeData($homeName);
    if ($data === null) return null;
    $world = $this->getPlugin()->getServer()->getWorldManager()->getWorldByName($data["world"]);
    if ($world === null) return null;
    if ($world->isLoaded()) return null;

    return new Position($data["x"], $data["y"], $data["z"], $world);
  }

  public function getHomeNames() : array{
    return array_keys($this->getData()->get("homes"));
  }
}
