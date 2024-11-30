<?php

namespace angga7togk\poweressentials\manager;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class UserManager
{
  private PowerEssentials $plugin;
  private Player $player;
  private Config $data;

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
}
