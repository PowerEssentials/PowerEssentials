<?php

namespace angga7togk\poweressentials\manager;

use angga7togk\poweressentials\manager\data\LobbyTrait;
use angga7togk\poweressentials\manager\data\WarpTrait;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\utils\Config;

class DataManager
{

  private PowerEssentials $plugin;
  private Config $data;

  use LobbyTrait;
  use WarpTrait;

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
}
