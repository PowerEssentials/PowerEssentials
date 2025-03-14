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

use pocketmine\Server;
use pocketmine\world\Position;

trait WarpTrait
{
    /**
     * Checks if a warp exists.
     *
     * @param string $warpName
     * @return bool
     */
    public function warpExists(string $warpName): bool
    {
        return isset($this->getData()->get('warps', [])[$warpName]);
    }

    /**
     * Adds a new warp.
     *
     * @param string $warpName
     * @param Position $pos
     */
    public function addWarp(string $warpName, Position $pos): void
    {
        $x         = $pos->getX();
        $y         = $pos->getY();
        $z         = $pos->getZ();
        $worldName = $pos->getWorld()->getFolderName();

        $this->getData()->setNested("warps.$warpName", "$x:$y:$z:$worldName");
        $this->getData()->save();
    }

    /**
     * Removes an existing warp.
     *
     * @param string $warpName
     */
    public function removeWarp(string $warpName): void
    {
        if (!$this->warpExists($warpName)) {
            return;
        }
        $this->getData()->removeNested("warps.$warpName");
        $this->getData()->save();
    }

    /**
     * Retrieves a warp position by name.
     *
     * @param string $warpName
     * @return Position|null
     */
    public function getWarp(string $warpName): ?Position
    {
        if (!$this->warpExists($warpName)) {
            return null;
        }

        $warpData = $this->getData()->getNested("warps.$warpName");

        if (!is_string($warpData)) {
            return null;
        }

        $dataWarp = explode(':', $warpData);

        if (count($dataWarp) !== 4) {
            return null;
        }

        $world = Server::getInstance()->getWorldManager()->getWorldByName($dataWarp[3]);
        if ($world === null || !$world->isLoaded()) {
            return null;
        }

        return new Position((float) $dataWarp[0], (float) $dataWarp[1], (float) $dataWarp[2], $world);
    }

    /**
     * Retrieves all warp names.
     *
     * @return array
     */
    public function getWarpNames(): array
    {
        return array_keys($this->getData()->get('warps', []));
    }
}
