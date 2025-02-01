<?php

/*
 *   ____                        _____                    _   _       _
 *  |  _ \ _____      _____ _ __| ____|___ ___  ___ _ __ | |_(_) __ _| |___
 *  | |_) / _ \ \ /\ / / _ \ '__|  _| / __/ __|/ _ \ '_ \| __| |/ _` | / __|
 *  |  __/ (_) \ V  V /  __/ |  | |___\__ \__ \  __/ | | | |_| | (_| | \__ \
 *  |_|   \___/ \_/\_/ \___|_|  |_____|___/___/\___|_| |_|\__|_|\__,_|_|___/
 *
 *
 * This file is part of PowerEssentials plugins.
 *
 * (c) Angga7Togk <kiplihode123321@gmail.com>
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

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
        $this->addTeleportRequest($requester, $target, 'to');
    }

    public function setRequestTeleportHere(Player $requester, Player $target): void
    {
        $this->addTeleportRequest($requester, $target, 'here');
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
            if (!$task->isCancelled()) {
                $task->cancel();
            }

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
