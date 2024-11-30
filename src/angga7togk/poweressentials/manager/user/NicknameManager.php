<?php

namespace angga7togk\poweressentials\manager\user;

use angga7togk\poweressentials\manager\UserManager;
use pocketmine\player\Player;

class NicknameManager extends UserManager
{
  public function __construct(Player $player)
  {
    parent::__construct($player);
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

  public function removeCustomNick(): void{
    $this->getData()->remove("custom-nick");
    $this->getData()->save();
  }
}
