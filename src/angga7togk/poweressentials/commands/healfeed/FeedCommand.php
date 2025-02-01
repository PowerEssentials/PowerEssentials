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

namespace angga7togk\poweressentials\commands\healfeed;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class FeedCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('feed', 'feeding', '/feed', ['eat']);
        $this->setPermission('feed');
        $this->setPrefix('healfeed.prefix');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (isset($args[0])) {
            if (!$sender->hasPermission(self::PREFIX_PERMISSION . 'feed.other')) {
                $sender->sendMessage($prefix . $lang->translateString('error.permission'));

                return;
            }
            $target = Server::getInstance()->getPlayerExact($args[0]);
            if ($target == null) {
                $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

                return;
            }
            $target->getHungerManager()->setFood($target->getHungerManager()->getMaxFood());
            $target->sendMessage($prefix . $lang->translateString('healfeed.feed'));
            $sender->sendMessage($prefix . $lang->translateString('healfeed.feed.other', [$target->getName()]));
        } else {
            if ($sender instanceof Player) {
                $sender->getHungerManager()->setFood($sender->getHungerManager()->getMaxFood());
                $sender->sendMessage($prefix . $lang->translateString('healfeed.feed'));
            } else {
                $sender->sendMessage($prefix . $lang->translateString('error.console'));
            }
        }
    }
}
