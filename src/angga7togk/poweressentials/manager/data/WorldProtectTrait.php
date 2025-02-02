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

trait WorldProtectTrait
{
    public function getWorldProtected(string $world): array
    {
        /** @var DataManager $manager */
        $manager = $this;
        if (!$manager->getData()->exists('worldprotect')) {
            return [];
        }
        if (!isset($manager->getData()->get('worldprotect')[$world])) {
            return [];
        }

        return $manager->getData()->get('worldprotect')[$world] ?? [];
    }

    public function isWorldProtected(string $type, string $world): bool
    {
        return in_array($type, $this->getWorldProtected($world));
    }

    public function setWorldProtected(string $type, bool $value, string $worldName): void
    {
        /** @var DataManager $manager */
        $manager  = $this;
        $protects = $this->getWorldProtected($worldName);

        if (!$value) {
            $protects[] = $type;
        } else {
            $protects = array_filter($protects, fn ($v) => $v !== $type);
        }

        $manager->getData()->setNested("worldprotect.$worldName", $protects);
        $manager->getData()->save();
    }
}
