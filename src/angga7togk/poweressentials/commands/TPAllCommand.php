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
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TPAllCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('tpall', 'Teleported all players', '/tpall [player]');
        $this->setPrefix('tpall.prefix');
        $this->setPermission('tpall');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $target = $sender->getServer()->getPlayerExact($args[0] ?? '') ?? $sender;

        $players = $sender->getServer()->getOnlinePlayers();
        if (count($players) < 2) {
            $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

            return;
        }

        foreach ($players as $player) {
            if ($player->getName() !== $target->getName()) {
                $player->teleport($target->getPosition());
            }
        }

        $sender->sendMessage($prefix . $lang->translateString('tpall.success', [$target->getName()]));
    }
}
