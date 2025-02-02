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

namespace angga7togk\poweressentials\commands\warp;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WarpCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('warp', 'teleport to any warp', '/warp <warpname>', ['warps']);
        $this->setPrefix('warp.prefix');
        $this->setPermission('warp');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!($sender instanceof Player)) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $mgr = PowerEssentials::getInstance()->getDataManager();
        if (!isset($args[0])) {
            $sender->sendMessage($prefix . implode(', ', $mgr->getWarpNames()));

            return;
        }

        if (!$mgr->warpExists($args[0])) {
            $sender->sendMessage($prefix . $lang->translateString('error.null'));

            return;
        }

        $warpPos = $mgr->getWarp($args[0]);
        if ($warpPos === null) {
            $sender->sendMessage($prefix . $lang->translateString('error.null'));

            return;
        }

        $sender->teleport($warpPos);
        $sender->sendMessage($prefix . $lang->translateString('warp.teleport', [$args[0]]));
    }
}
