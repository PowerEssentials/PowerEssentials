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
use pocketmine\Server;

class GetPositionCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('getposition', 'get position of player', '/getposition <player>', ['getpos']);
        $this->setPrefix('getpos.prefix');
        $this->setPermission('getpos');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!isset($args[0])) {
            $sender->sendMessage($prefix . $this->getUsage());

            return;
        }

        $player = Server::getInstance()->getPlayerExact($args[0]);
        if ($player === null) {
            $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

            return;
        }

        $sender->sendMessage($prefix . $lang->translateString('getpos.success', [
          $player->getName(),
          $player->getWorld()->getDisplayName(),
          $player->getPosition()->getX(),
          $player->getPosition()->getY(),
          $player->getPosition()->getZ()
        ]));
    }
}
