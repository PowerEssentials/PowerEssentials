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
        $this->cancelVoteTimeOut   = PEConfig::getOneSleepCancelVoteTimeout();
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
        $this->sleeper       = null;
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
        if ($this->sleeper == null) {
            return;
        }
        $this->voterToCancel[] = $player->getName();
    }

    public function canCancelOneSleep(): bool
    {
        return count($this->voterToCancel) >= $this->cancelVoteNeedCount;
    }
}
