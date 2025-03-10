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

trait TempBanTrait
{
    /**
     * @return array<string, array{expire: int, reason: string}>
     */
    public function getTempBans(): array
    {
        return $this->getData()->get('tempbans', []);
    }

    public function isTempBanned(string $playerName): bool
    {
        $bans = $this->getTempBans();
        if (!isset($bans[$playerName])) {
            return false;
        }

        if (time() > $bans[$playerName]['expire']) {
            $this->removeTempBan($playerName);

            return false;
        }

        return true;
    }

    public function setTempBan(string $playerName, int $duration, string $reason): void
    {
        $bans              = $this->getTempBans();
        $bans[$playerName] = [
            'expire' => time() + $duration,
            'reason' => $reason,
        ];

        $this->getData()->set('tempbans', $bans);
        $this->getData()->save();
    }

    public function getTempBanReason(string $playerName): string
    {
        return $this->getTempBans()[$playerName]['reason'] ? strval($this->getTempBans()[$playerName]['reason']) : 'No reason provided';
    }

    public function removeTempBan(string $playerName): void
    {
        $bans = $this->getTempBans();
        if (!isset($bans[$playerName])) {
            return;
        }

        unset($bans[$playerName]);
        $this->getData()->set('tempbans', $bans);
        $this->getData()->save();
    }
}
