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

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LobbyCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('lobby', 'teleport to lobby spawn', '/lobby', ['hub', 'lobbytp']);
        $this->setPrefix('lobby.prefix');
        $this->setPermission('lobby');
    }
    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->getPrefix() . $lang->translateString('error.console'));

            return;
        }
        if (($posLobby = PowerEssentials::getInstance()->getDataManager()->getLobby()) === null) {
            $sender->sendMessage($this->getPrefix() . $lang->translateString('error.null'));

            return;
        }
        $sender->teleport($posLobby);
        $sender->sendMessage($this->getPrefix() . $lang->translateString('lobby.teleported'));
    }
}
