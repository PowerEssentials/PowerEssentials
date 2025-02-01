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

declare(strict_types = 1);

namespace angga7togk\poweressentials\utils;

/** Credit Code: https://github.com/fuyutsuki/Texter/blob/122f9b45a4896c51eb5b7f4fc0aa479ea0df56a7/src/jp/mcbe/fuyutsuki/Texter/util/StringArrayMultiton.php */
trait StringArrayMultiton
{
    /** @var static[] */
    protected static array $instances = [];

    final public function __construct(string $key)
    {
        static::$instances[$key] = $this;
    }

    final public static function getInstance(string $key): ?static
    {
        return static::$instances[$key] ?? null;
    }

    final public static function removeInstance(string $key): void
    {
        unset(static::$instances[$key]);
    }
}
