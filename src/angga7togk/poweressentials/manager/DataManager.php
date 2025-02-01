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

namespace angga7togk\poweressentials\manager;

use angga7togk\poweressentials\manager\data\BanItemTrait;
use angga7togk\poweressentials\manager\data\LobbyTrait;
use angga7togk\poweressentials\manager\data\OneSleep;
use angga7togk\poweressentials\manager\data\TPATrait;
use angga7togk\poweressentials\manager\data\WarpTrait;
use angga7togk\poweressentials\manager\data\WorldProtectTrait;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\utils\Config;

class DataManager
{
    use LobbyTrait;
    use WarpTrait;
    use BanItemTrait;
    use WorldProtectTrait;
    /** Temporary Data */
    use OneSleep{
        OneSleep::__construct as private __constructOneSleep;
    }
    use TPATrait;

    private PowerEssentials $plugin;
    private Config $data;

    public function __construct(PowerEssentials $plugin)
    {
        $this->plugin = $plugin;
        $this->plugin->saveResource('data.yml');
        $this->data = new Config($this->plugin->getDataFolder() . 'data.yml', Config::YAML, []);
        $this->__constructOneSleep();
    }

    public function getData(): Config
    {
        return $this->data;
    }
}
