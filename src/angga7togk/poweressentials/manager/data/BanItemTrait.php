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
use pocketmine\item\Item;
use pocketmine\world\World;

trait BanItemTrait
{
    /** @return String[] */
    public function getBannedItems(World $world): array
    {
        /** @var DataManager $manager */
        $manager = $this;
        if (!$manager->getData()->exists('banitems')) {
            return [];
        }
        $bans = $manager->getData()->get('banitems');
        if (!isset($bans[$world->getFolderName()])) {
            return [];
        }

        return $bans[$world->getFolderName()];
    }

    public function isBannedItem(Item $item, World $world): bool
    {
        $worldName       = $world->getFolderName();
        $itemVanillaName = $item->getVanillaName();

        /** @var DataManager $manager */
        $manager = $this;
        if (!$manager->getData()->exists('banitems')) {
            return false;
        }
        $bans = $manager->getData()->get('banitems');
        if (!isset($bans[$worldName])) {
            return false;
        }

        return in_array($itemVanillaName, $bans[$worldName]);
    }

    public function banItem(Item $item, World $world): void
    {
        $worldName       = $world->getFolderName();
        $itemVanillaName = $item->getVanillaName();
        $banItems        = $this->getBannedItems($world);

        if (in_array($itemVanillaName, $banItems)) {
            return;
        }
        $banItems[] = $itemVanillaName;

        /** @var DataManager $manager */
        $manager = $this;
        $manager->getData()->setNested("banitems.$worldName", $banItems);
        $manager->getData()->save();
    }

    public function unbanItem(Item $item, World $world): void
    {
        $worldName       = $world->getFolderName();
        $itemVanillaName = $item->getVanillaName();
        $banItems        = $this->getBannedItems($world);

        if (!in_array($itemVanillaName, $banItems)) {
            return;
        }
        $banItems = array_filter($banItems, fn ($banItem) => $banItem !== $itemVanillaName);

        /** @var DataManager $manager */
        $manager = $this;
        $manager->getData()->setNested("banitems.$worldName", $banItems);
        $manager->getData()->save();
    }
}
