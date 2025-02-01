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
use pocketmine\player\Player;
use pocketmine\Server;

class OneSleepCancelCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('onesleepcancel', 'Cancel One Sleep', '/scancel', ['oscancel', 'scancel']);
        $this->setPrefix('onesleep.prefix');
        $this->setPermission('onesleep.cancel');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $dataMgr = PowerEssentials::getInstance()->getDataManager();
        if ($dataMgr->getSlepper() === null) {
            $sender->sendMessage($prefix . $lang->translateString('onesleep.error.sleeper.null'));

            return;
        }

        if ($dataMgr->getSlepper()->getName() === $sender->getName()) {
            $sender->sendMessage($prefix . $lang->translateString('onesleep.error.sleeper.self'));

            return;
        }

        if (!$dataMgr->haveOneSleepVoted($sender)) {
            $dataMgr->addOneSleepVote($sender);

            if ($dataMgr->canCancelOneSleep()) {
                $dataMgr->getSlepper()->stopSleep();
                $dataMgr->unsetSlepper();
                Server::getInstance()->broadcastMessage($prefix . $lang->translateString('onesleep.error.canceled'));
            } else {
                Server::getInstance()->broadcastMessage($prefix . $lang->translateString('onesleep.vote', [$sender->getName()]));
            }
        }
    }
}
