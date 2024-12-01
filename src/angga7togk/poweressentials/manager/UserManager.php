<?php

namespace angga7togk\poweressentials\manager;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\user\HomeTrait;
use angga7togk\poweressentials\manager\user\NicknameTrait;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class UserManager extends PEConfig
{
  private PowerEssentials $plugin;
  private Player $player;
  private Config $data;
  
  use HomeTrait;
  use NicknameTrait;

  public function __construct(Player $player)
  {
    $this->plugin = PowerEssentials::getInstance();
    $this->player = $player;
    @mkdir($this->plugin->getDataFolder() . "users/");
  }

  public function getData(): Config
  {
    $this->data ?? $this->data = new Config($this->plugin->getDataFolder() . "users/" . $this->player->getName() . ".yml", Config::YAML, []);
    return $this->data;
  }

  public function getPlayer(): Player
  {
    return $this->player;
  }

  public function getPlugin(): PowerEssentials
  {
    return $this->plugin;
  }

  public function setCoordinatesShow(bool $value): void{
    $this->getData()->set("show-coordinates", $value);
    $this->getData()->save();
  }

  public function getCoordinatesShow(): bool{
    if(!PEConfig::isShowCoordinates()) return false;
    return $this->getData()->get("show-coordinates", true);
  }
}
