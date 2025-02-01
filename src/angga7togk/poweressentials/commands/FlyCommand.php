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

class FlyCommand extends PECommand
{
    private array $flying = [];

    public function __construct()
    {
        parent::__construct('fly', 'flying mode in server');
        $this->setPrefix('fly.prefix');
        $this->setPermission('fly');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (isset($args[0])) {
            if (!$sender->hasPermission(self::PREFIX_PERMISSION . 'fly.other')) {
                $sender->sendMessage($prefix . $lang->translateString('error.permission'));

                return;
            }
            $target = Server::getInstance()->getPlayerExact($args[0]);
            if ($target == null) {
                $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

                return;
            }

            if (in_array($target->getName(), $this->flying)) {
                $sender->sendMessage($prefix . $lang->translateString('fly.disable.other', [$target->getName()]));
                if (($key = array_search($target->getName(), $this->flying)) !== false) {
                    unset($this->flying[$key]);
                    $target->sendMessage($prefix . $lang->translateString('fly.disable'));
                }
                $target->setAllowFlight(false);
            } else {
                $sender->sendMessage($prefix . $lang->translateString('fly.enable.other', [$target->getName()]));
                $this->flying[] = $target->getName();
                $target->sendMessage($prefix . $lang->translateString('fly.enable'));
                $target->setAllowFlight(true);
            }
        } else {
            if (!$sender instanceof Player) {
                $sender->sendMessage($prefix . $lang->translateString('error.console'));

                return;
            }
            if (in_array($sender->getName(), $this->flying)) {
                if (($key = array_search($sender->getName(), $this->flying)) !== false) {
                    unset($this->flying[$key]);
                }
                $sender->sendMessage($prefix . $lang->translateString('fly.disable'));
                $sender->setAllowFlight(false);
            } else {
                $this->flying[] = $sender->getName();
                $sender->sendMessage($prefix . $lang->translateString('fly.enable'));
                $sender->setAllowFlight(true);
            }
        }
    }
}
