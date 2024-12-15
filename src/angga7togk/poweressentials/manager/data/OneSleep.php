<?php

namespace angga7togk\poweressentials\manager\data;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;

trait OneSleep
{
  private ?Player $sleeper = null;

  /** @var string[] $voterToCancel */
  private array $voterToCancel = [];

  private int $cancelVoteTimeOut;
  private int $cancelVoteNeedCount;

  private ?TaskHandler $handler = null;

  public function __construct()
  {
    $this->cancelVoteTimeOut = PEConfig::getOneSleepCancelVoteTimeout();
    $this->cancelVoteNeedCount = PEConfig::getOneSleepCancelVoteCount();

  }

  public function setSlepper(Player $player): void
  {
    $this->sleeper = $player;

    $this->handler = PowerEssentials::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
      function (): void {
        if ($this->getSlepper() !== null) {
          $this->getSlepper()->getWorld()->setTime(0);
          $this->getSlepper()->stopSleep();
          $this->unsetSlepper();
        }
      }
    ), 20 * $this->cancelVoteTimeOut);
  }

  public function getSlepper(): ?Player
  {
    return $this->sleeper;
  }

  public function unsetSlepper(): void
  {
    $this->sleeper = null;
    $this->voterToCancel = [];

    if ($this->handler != null) {
      $this->handler->cancel();
      $this->handler = null;
    }
  }

  public function haveOneSleepVoted(Player $player): bool
  {
    return in_array($player->getName(), $this->voterToCancel);
  }

  public function addOneSleepVote(Player $player): void
  {
    if ($this->sleeper == null) return;
    $this->voterToCancel[] = $player->getName();
  }

  public function canCancelOneSleep(): bool
  {
    return count($this->voterToCancel) >= $this->cancelVoteNeedCount;
  }
}
