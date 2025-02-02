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

namespace angga7togk\poweressentials\commands\banitem;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class BanItemListCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('banitemlist', 'Show banned item list from the world', '/banitemlist [world]');
        $this->setPrefix('banitem.prefix');
        $this->setPermission('banitem');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $world = isset($args[0]) ? Server::getInstance()->getWorldManager()->getWorldByName($args[0]) : $sender->getWorld();

        if ($world === null || !$world->isLoaded()) {
            $sender->sendMessage($prefix . $lang->translateString('error.world.null'));

            return;
        }

        $mgr  = PowerEssentials::getInstance()->getDataManager();
        $bans = $mgr->getBannedItems($world);

        if (count($bans) === 0) {
            $sender->sendMessage($prefix . $lang->translateString('banitem.list.empty'));

            return;
        }
        $sender->sendMessage($prefix . implode(', ', $bans));
    }
}
