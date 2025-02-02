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
use pocketmine\Server;

class SudoCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('sudo', 'Execute command or send message as a selected player', '/sudo <target> <cmd or msg>');
        $this->setPrefix('sudo.prefix');
        $this->setPermission('sudo');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (count($args) < 2) {
            $sender->sendMessage($prefix . $this->getUsage());

            return;
        }

        $targetName = array_shift($args);
        $target     = Server::getInstance()->getPlayerExact($targetName);

        if (!$target instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

            return;
        }

        $commandOrMessage = implode(' ', $args);
        $target->chat($commandOrMessage);

        $sender->sendMessage($prefix . $lang->translateString('sudo.success', [$target->getName()]));
    }
}
