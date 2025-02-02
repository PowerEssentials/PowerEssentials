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

class WorldProtectCommand extends PECommand
{
    /** @var string[] $protectTypes */
    private array $protectTypes = ['place', 'break', 'pvp', 'hunger', 'health', 'falldamage', 'interaction', 'explosion'];

    public function __construct()
    {
        parent::__construct('worldprotect', 'Protect world from breaking ,placing blocks, hunger, pvp, and others', '/worldprotect <place, break, pvp, hunger, health, falldamage, interaction, explosion> <value: true | false> [world]', ['wp', 'wprotect']);
        $this->setPrefix('worldprotect.prefix');
        $this->setPermission('worldprotect');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (count($args) < 2) {
            $sender->sendMessage($prefix . $this->getUsage());

            return;
        }

        if (!$sender instanceof Player && count($args) < 3) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $type = strtolower($args[0]);
        if (!in_array($type, $this->protectTypes)) {
            $sender->sendMessage($prefix . $lang->translateString('worldprotect.error.invalid.type'));

            return;
        }

        if (!in_array(strtolower($args[1]), ['true', 'false'], true)) {
            $sender->sendMessage($prefix . $lang->translateString('worldprotect.error.invalid.value'));

            return;
        }
        $value = strtolower($args[1]) === 'true';

        /** @var Player $sender */
        $world = isset($args[2]) ? $sender->getServer()->getWorldManager()->getWorldByName($args[2]) : $sender->getWorld();

        if ($world === null || !$world->isLoaded()) {
            $sender->sendMessage($prefix . $lang->translateString('error.world.null'));

            return;
        }

        $mgr = PowerEssentials::getInstance()->getDataManager();
        $mgr->setWorldProtected($type, $value, $world->getFolderName());
        $sender->sendMessage($prefix . $lang->translateString('worldprotect.success', [$type, $value]));
    }
}
