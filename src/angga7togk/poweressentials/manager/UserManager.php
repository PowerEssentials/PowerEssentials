<?php

namespace angga7togk\poweressentials\manager;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;

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

  public function getHomeLimit(): int
  {
    $defaultLimit = PEConfig::getHomePermissionDefaultLimit();
    foreach (PEConfig::getHomePermissionLimits() as $permission => $limit) {
      if ($this->getPlayer()->hasPermission($permission)) {
        $defaultLimit += $limit;
      }
    }
    return $defaultLimit;
  }

  public function getHomeCount(): int
  {
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

  public function getHomeNames(): array
  {
    return array_keys($this->getData()->get("homes"));
  }


  public function setCustomNick(string $nickname): void
  {
    $this->getData()->set("custom-nick", $nickname);
    $this->getData()->save();
  }

  public function getCustomNick(): ?string
  {
    if (!$this->getData()->exists("custom-nick")) return null;
    return $this->getData()->get("custom-nick");
  }

  public function removeCustomNick(): void
  {
    $this->getData()->remove("custom-nick");
    $this->getData()->save();
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
