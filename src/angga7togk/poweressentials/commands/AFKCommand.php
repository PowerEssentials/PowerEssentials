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

class AFKCommand extends PECommand
{
    /** @var string[] $afk */
    private static array $afk = [];

    public function __construct()
    {
        parent::__construct('afk', 'set afk for your self', '/afk', ['away']);
        $this->setPrefix('afk.prefix');
        $this->setPermission('afk');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $nick = $sender->getName();

        if (isset(self::$afk[$nick])) {
            unset(self::$afk[$nick]);
            $sender->sendMessage($prefix . $lang->translateString('afk.disabled'));

            return;
        } else {
            self::$afk[$nick] = $nick;
            $sender->sendMessage($prefix . $lang->translateString('afk.enabled'));

            foreach ($sender->getServer()->getOnlinePlayers() as $player) {
                if ($sender->getName() == $player->getName()) {
                    continue;
                }
                $player->sendMessage($prefix . $lang->translateString('afk.broadcast', [$nick]));
            }
        }
    }

    public static function isAfk(Player $player): bool
    {
        return isset(self::$afk[$player->getName()]);
    }

    /** @return string[] */
    public static function getAfk(): array
    {
        return self::$afk;
    }

    public static function disabledAfk(Player $player): void
    {
        unset(self::$afk[$player->getName()]);
    }
}
