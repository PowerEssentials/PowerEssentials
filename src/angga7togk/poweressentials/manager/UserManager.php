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

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\user\HomeTrait;
use angga7togk\poweressentials\manager\user\MuteTrait;
use angga7togk\poweressentials\manager\user\NicknameTrait;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class UserManager extends PEConfig
{
    use HomeTrait;
    use MuteTrait;
    use NicknameTrait;
    private PowerEssentials $plugin;
    private Player $player;
    private Config $data;

    public function __construct(Player $player)
    {
        $this->plugin = PowerEssentials::getInstance();
        $this->player = $player;
        @mkdir($this->plugin->getDataFolder() . 'users/');
    }

    public function getData(): Config
    {
        $this->data ?? $this->data = new Config($this->plugin->getDataFolder() . 'users/' . $this->player->getName() . '.yml', Config::YAML, []);

        return $this->data;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getPlugin(): PowerEssentials
    {
        return $this->plugin;
    }

    public function setCoordinatesShow(bool $value): void
    {
        $this->getData()->set('show-coordinates', $value);
        $this->getData()->save();
    }

    public function getCoordinatesShow(): bool
    {
        if (!PEConfig::isShowCoordinates()) {
            return false;
        }

        return $this->getData()->get('show-coordinates', true);
    }
}
