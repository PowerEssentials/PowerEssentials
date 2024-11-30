<?php

namespace angga7togk\poweressentials\manager\user;

trait NicknameTrait
{
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
}
