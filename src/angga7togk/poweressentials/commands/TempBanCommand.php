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
use pocketmine\utils\TextFormat;

class TempBanCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('tempban', 'Temporarily ban a player', '/tempban <player> <time> [reason]');
        $this->setPrefix('tempban.prefix');
        $this->setPermission('tempban');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (count($args) < 2) {
            $sender->sendMessage($prefix . TextFormat::RED . '/tempban <player> <time> [reason]');

            return;
        }

        $targetName = $args[0];
        $timeString = $args[1];
        $reason     = isset($args[2]) ? implode(' ', array_slice($args, 2)) : $lang->translateString('tempban.no_reason');

        $target = Server::getInstance()->getPlayerByPrefix($targetName);
        if (!$target instanceof Player) {
            $sender->sendMessage($prefix . TextFormat::RED . $lang->translateString('tempban.not_found'));

            return;
        }

        if ($target->hasPermission('tempban.exempt')) {
            $sender->sendMessage($prefix . TextFormat::RED . $lang->translateString('tempban.exempt'));

            return;
        }

        $duration = $this->parseTime($timeString);
        if ($duration === null) {
            $sender->sendMessage($prefix . TextFormat::RED . $lang->translateString('tempban.invalid'));

            return;
        }

        $userManager = PowerEssentials::getInstance()->getUserManager($target);
        $userManager->setTempBan($targetName, $duration, $reason);

        $target->kick(TextFormat::RED . $lang->translateString('tempban.success', [$timeString, $reason]));

        Server::getInstance()->broadcastMessage($prefix . $lang->translateString('tempban.broadcast', [$targetName, $timeString, $reason]));
    }

    /**
     * Parse time string into seconds.
     *
     * @param string $timeString Format: <number><unit> (e.g., 10m, 1h)
     * @return int|null Time in seconds, or null if format is invalid
     */
    private function parseTime(string $timeString): ?int
    {
        if (!preg_match('/^(\d+)(s|m|h|d)$/', $timeString, $matches)) {
            return null;
        }

        $timeValue = (int) $matches[1];
        $timeUnit  = $matches[2];

        return match ($timeUnit) {
            's' => $timeValue,
            'm' => $timeValue * 60,
            'h' => $timeValue * 3600,
            'd' => $timeValue * 86400,
        };
    }
}
