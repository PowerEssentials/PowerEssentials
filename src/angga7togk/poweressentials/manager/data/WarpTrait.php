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

use angga7togk\poweressentials\manager\DataManager;
use pocketmine\Server;
use pocketmine\world\Position;

trait WarpTrait
{
    public function warpExists(string $warpName): bool
    {
        /** @var DataManager $this */
        return isset($this->getData()->get('warps', [])[$warpName]);
    }

    public function addWarp(string $warpName, Position $pos): void
    {
        $x         = $pos->getX();
        $y         = $pos->getY();
        $z         = $pos->getZ();
        $worldName = $pos->getWorld()->getFolderName();

        /** @var DataManager $manager */
        $manager = $this;
        $manager->getData()->setNested("warps.$warpName", "$x:$y:$z:$worldName");
        $manager->getData()->save();
    }

    public function removeWarp(string $warpName): void
    {
        if (!$this->warpExists($warpName)) {
            return;
        }
        /** @var DataManager $manager */
        $manager = $this;
        $manager->getData()->removeNested("warps.$warpName");
        $manager->getData()->save();
    }

    public function getWarp(string $warpName): ?Position
    {
        if (!$this->warpExists($warpName)) {
            return null;
        }
        /** @var DataManager $manager */
        $manager  = $this;
        $dataWarp = explode(':', $manager->getData()->getNested("warps.$warpName"));

        $world = Server::getInstance()->getWorldManager()->getWorldByName($dataWarp[3]);
        if ($world === null) {
            return null;
        }
        if (!$world->isLoaded()) {
            return null;
        }

        return new Position((float) $dataWarp[0], (float) $dataWarp[1], (float) $dataWarp[2], $world);
    }

    public function getWarpNames(): array
    {
        /** @var DataManager $manager */
        $manager = $this;

        return array_keys($manager->getData()->get('warps', []));
    }
}
