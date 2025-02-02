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

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;

class UnmuteCommand extends PECommand implements PluginOwned
{
    public function __construct()
    {
        parent::__construct("unmute", "Unmute a player", "/unmute <player>", []);
        $this->setPrefix("unmute.prefix");
        $this->setPermission("unmute");
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($prefix . $lang->translateString('error.permission'));
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage($prefix . $lang->translateString('unmute.usage'));
            return;
        }

        $playerName = array_shift($args);

        $userManager = PowerEssentials::getInstance()->getUserManager();

        if (!$userManager->isMuted($playerName)) {
            $sender->sendMessage($prefix . $lang->translateString('unmute.not_muted', [$playerName]));
            return;
        }

        $userManager->unmutePlayer($playerName);
        $sender->sendMessage($prefix . $lang->translateString('unmute.success', [$playerName]));
    }
}
