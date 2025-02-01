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

use pocketmine\world\Position;

trait LobbyTrait
{
    public function setLobby(Position $pos): void
    {
        $x     = $pos->getX();
        $y     = $pos->getY();
        $z     = $pos->getZ();
        $world = $pos->getWorld()->getFolderName();
        $this->getData()->set('lobby', "$x:$y:$z:$world");
        $this->getData()->save();
    }

    public function getLobby(): ?Position
    {
        if (!$this->getData()->exists('lobby')) {
            return null;
        }
        $lobby = $this->getData()->get('lobby');
        $lobby = explode(':', $lobby);

        $world = $this->plugin->getServer()->getWorldManager()->getWorldByName($lobby[3]);
        if ($world === null) {
            return null;
        }
        if (!$world->isLoaded()) {
            return null;
        }

        return new Position((int) $lobby[0], (int) $lobby[1], (int) $lobby[2], $world);
    }
}
