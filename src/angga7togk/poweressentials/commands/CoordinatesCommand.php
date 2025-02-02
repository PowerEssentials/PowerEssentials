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

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;

class CoordinatesCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('coordinates', 'get coordinates', '/coordinates', ['coords', 'coord', 'coordinate']);
        $this->setPrefix('coordinates.prefix');
        $this->setPermission('coordinates');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!($sender instanceof Player)) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage($prefix . $lang->translateString('error.permission'));

            return;
        }
        $mgr = PowerEssentials::getInstance()->getUserManager($sender);
        if (!PEConfig::isShowCoordinates()) {
            $sender->sendMessage($prefix . $lang->translateString('coordinates.error.off'));

            return;
        }

        if ($mgr->getCoordinatesShow()) {
            $pk            = new GameRulesChangedPacket();
            $pk->gameRules = ['showcoordinates' => new BoolGameRule(false, false)];
            $sender->getNetworkSession()->sendDataPacket($pk);
            $sender->sendMessage($prefix . $lang->translateString('coordinates.disable'));
            $mgr->setCoordinatesShow(false);

            return;
        } else {
            $pk            = new GameRulesChangedPacket();
            $pk->gameRules = ['showcoordinates' => new BoolGameRule(true, false)];
            $sender->getNetworkSession()->sendDataPacket($pk);
            $sender->sendMessage($prefix . $lang->translateString('coordinates.enable'));
            $mgr->setCoordinatesShow(true);
        }
    }
}
