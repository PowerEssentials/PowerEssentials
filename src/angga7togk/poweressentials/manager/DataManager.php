<?php

namespace angga7togk\poweressentials\manager;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class DataManager
{

  private PowerEssentials $plugin;
  private Config $data;

  public function __construct(PowerEssentials $plugin)
  {
    $this->plugin = $plugin;
    $this->plugin->saveResource("data.yml");
    $this->data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML, []);
  }

  public function getData(): Config
  {
    return $this->data;
  }

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
