<?php

namespace angga7togk\poweressentials\manager\data;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;

trait TPATrait
{
  private array $tpaRequests = [];

  public function setRequestTeleportTo(Player $requester, Player $target): void
  {
    $this->addTeleportRequest($requester, $target, "to");
  }

  public function setRequestTeleportHere(Player $requester, Player $target): void
  {
    $this->addTeleportRequest($requester, $target, "here");
  }

  private function addTeleportRequest(Player $requester, Player $target, string $type): void
  {
    $this->removeTeleportRequest($requester, $target);
    $task = $this->scheduleExpiryTask(function () use ($requester, $target): void {
      $this->removeTeleportRequest($requester, $target);
    });

    $this->tpaRequests[$target->getName()][$requester->getName()] = [
      'type' => $type,
      'task' => $task
    ];
  }

  public function removeTeleportRequest(Player $requester, Player $target): void
  {
    if (isset($this->tpaRequests[$target->getName()][$requester->getName()])) {
      $task = $this->tpaRequests[$target->getName()][$requester->getName()]['task'];
      if (!$task->isCancelled()) $task->cancel();

      unset($this->tpaRequests[$target->getName()][$requester->getName()]);
    }
  }

  public function getRequestTeleportType(Player $target, Player $requester): string|false
  {
    return $this->tpaRequests[$target->getName()][$requester->getName()]['type'] ?? false;
  }

  private function scheduleExpiryTask(\Closure $task): TaskHandler
  {
    return PowerEssentials::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask($task), 20 * 60);
  }
}
