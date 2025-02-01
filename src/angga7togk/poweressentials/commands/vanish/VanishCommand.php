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

namespace angga7togk\poweressentials\commands\vanish;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class VanishCommand extends PECommand
{
    private static $vanishedPlayers = [];

    public function __construct()
    {
        parent::__construct('vanish', 'Vanish from the server', '/vanish [player]');
        $this->setPrefix('vanish.prefix');
        $this->setPermission('vanish');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $target = isset($args[0]) ? $sender->getServer()->getPlayerExact($args[0]) : $sender;

        if ($target === null) {
            $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

            return;
        }

        $isVanish = self::isVanished($target);
        if ($target->getName() === $sender->getName()) {
            if ($isVanish) {
                $sender->sendMessage($prefix . $lang->translateString('vanish.disabled'));
            } else {
                $sender->sendMessage($prefix . $lang->translateString('vanish.enabled'));
            }
        } else {
            if (!$sender->hasPermission('poweressentials.vanish.other')) {
                $sender->sendMessage($prefix . $lang->translateString('error.permission'));

                return;
            }

            if ($isVanish) {
                $sender->sendMessage($prefix . $lang->translateString('vanish.disabled.other', [$target->getName()]));
            } else {
                $sender->sendMessage($prefix . $lang->translateString('vanish.enabled.other', [$target->getName()]));
            }
        }
        $this->setVanish($target, !$isVanish, $lang);
    }

    public static function getVanishedPlayers(): array
    {
        return self::$vanishedPlayers;
    }

    public static function unsetDataVanish(Player $player): void
    {
        unset(self::$vanishedPlayers[array_search($player->getName(), self::$vanishedPlayers)]);
    }

    public static function isVanished(Player $player): bool
    {
        return in_array($player->getName(), self::$vanishedPlayers);
    }

    public function setVanish(Player $player, bool $vanish, PELang $lang)
    {
        if ($vanish) {
            self::$vanishedPlayers[] = $player->getName();
        } else {
            unset(self::$vanishedPlayers[array_search($player->getName(), self::$vanishedPlayers)]);
        }

        foreach (Server::getInstance()->getOnlinePlayers() as $other) {
            if (!$other->hasPermission('poweressentials.vanish.see')) {
                continue;
            }
            if ($vanish) {
                $other->sendMessage($this->getPrefix() . $lang->translateString('vanish.enabled.other', [$player->getName()]));
            } else {
                $other->sendMessage($this->getPrefix() . $lang->translateString('vanish.disabled.other', [$player->getName()]));
            }
        }
    }
}
