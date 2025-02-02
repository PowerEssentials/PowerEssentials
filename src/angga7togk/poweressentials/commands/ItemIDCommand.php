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
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class ItemIDCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('itemid', 'Check detail item in hand', '/itemid', ['itemdb', 'itemname']);
        $this->setPrefix('itemid.prefix');
        $this->setPermission('itemid');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $item = $sender->getInventory()->getItemInHand();
        if ($item->isNull()) {
            $sender->sendMessage($prefix . $lang->translateString('error.hold.item'));

            return;
        }

        $sender->sendMessage($prefix . $lang->translateString('itemid.success', [
          $item->getName(),
          $item->getCustomName(),
          $item->getVanillaName(),
          StringToItemParser::getInstance()->lookupAliases($item)[0],
          $item->getTypeId(),
          $item->getStateId(),
        ]));
    }
}
