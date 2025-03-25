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

namespace angga7togk\poweressentials\config;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

class PEConfig
{
    private static Config $config;
    private const CONFIG_NEW_VERSION = 1.0;

    public static function init(): void
    {
        PowerEssentials::getInstance()->saveDefaultConfig();
        self::$config = PowerEssentials::getInstance()->getConfig();
    }

    public static function getNewVersion(): float
    {
        return self::CONFIG_NEW_VERSION;
    }

    public static function getVersion(): float
    {
        $version = self::$config->get('config-version');

        return is_numeric($version) ? (float) $version : 0.0;
    }

    public static function getLang(): string
    {
        $lang = self::$config->get('language');

        return is_string($lang) ? $lang : 'en';
    }

    public static function isGamemodeJoin(): bool
    {
        return (bool) self::$config->get('gamemode-join-enable', false);
    }

    public static function getGamemodeJoin(): ?GameMode
    {
        $gamemode = self::$config->get('gamemode-join');

        return is_string($gamemode) ? GameMode::fromString($gamemode) : null;
    }

    public static function isSpawnLobbyJoin(): bool
    {
        return (bool) self::$config->get('spawn-lobby-join', false);
    }

    public static function isAntiNamespace(): bool
    {
        return (bool) self::$config->get('anti-namespace', false);
    }

    public static function isBlacklistNickname(string $nick): bool
    {
        $blacklist = self::$config->get('blacklist-nicknames', []);
        if (!is_array($blacklist)) {
            return false;
        }
        foreach ($blacklist as $nickBL) {
            if (is_string($nickBL) && strpos($nick, $nickBL) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function getMaxCharNickname(): int
    {
        $maxChar = self::$config->get('nickname-max-char');

        return is_numeric($maxChar) ? (int) $maxChar : 16;
    }

    public static function isCommandDisabled(string $commandKey): bool
    {
        $disabledCommands = self::$config->get('disable-commands', []);

        return is_array($disabledCommands) && in_array($commandKey, $disabledCommands, true);
    }

    public static function isWorldBlacklistSetHome(string $world): bool
    {
        $blacklists = self::$config->get('home-world-blacklists', []);

        return is_array($blacklists) && in_array($world, $blacklists, true);
    }

    public static function isHomePermissionLimit(): bool
    {
        return (bool) self::$config->get('home-permission-limit', false);
    }

    public static function getHomePermissionDefaultLimit(): int
    {
        $limit = self::$config->get('home-permission-default-limit');

        return is_numeric($limit) ? (int) $limit : 1;
    }

    /**
     * @return array<string, int>
     */
    public static function getHomePermissionLimits(): array
    {
        $limits = self::$config->get('home-permission-limits', []);

        if (!is_array($limits)) {
            return [];
        }

        /** @var array<string, int> $result */
        $result = [];
        foreach ($limits as $key => $value) {
            $result[(string)$key] = is_numeric($value) ? (int)$value : 0;
        }

        return $result;
    }

    public static function isShowCoordinates(): bool
    {
        return (bool) self::$config->get('show-coordinates', false);
    }

    public static function isRandomTeleportAntiWater(): bool
    {
        return (bool) self::$config->get('random-teleport-anti-water', false);
    }

    public static function getRandomTeleportTimeOut(): int
    {
        $timeout = self::$config->get('random-teleport-timeout');

        return is_numeric($timeout) ? (int) $timeout : 10;
    }

    /** @return array<int> */
    public static function getRandomTeleportRange(string $coordType): array
    {
        $range = self::$config->get('random-teleport-range', []);
        if (!is_array($range)) {
            return [];
        }

        return isset($range[$coordType]) && is_array($range[$coordType]) ?
            array_map(function ($v) {
                return is_numeric($v) ? (int)$v : 0;
            }, $range[$coordType]) : [];
    }

    public static function isRandomTeleportWorldBlocked(World $world): bool
    {
        $worldName  = $world->getFolderName();
        $blacklists = self::$config->get('random-teleport-world-blacklists', []);

        return is_array($blacklists) && in_array($worldName, $blacklists, true);
    }

    public static function getSizeMax(): float
    {
        $size = self::$config->get('size-max');

        return is_numeric($size) ? (float) $size : 5.0;
    }

    public static function isOneSleepEnabled(): bool
    {
        return (bool) self::$config->get('one-sleep-enable', true);
    }

    public static function isOneSleepCancelVote(): bool
    {
        return (bool) self::$config->get('cancel-sleep-vote', true);
    }

    public static function getOneSleepCancelVoteCount(): int
    {
        $count = self::$config->get('cancel-sleep-vote-count');

        return is_numeric($count) ? (int) $count : 3;
    }

    public static function getOneSleepCancelVoteTimeout(): int
    {
        $timeout = self::$config->get('cancel-sleep-vote-timeout');

        return is_numeric($timeout) ? (int) $timeout : 10;
    }

    public function checkTempBan(Player $player): bool
    {
        $name        = $player->getName();
        $dataManager = PowerEssentials::getInstance()->getDataManager();

        if ($dataManager->isTempBanned($name)) {
            $reason    = $dataManager->getTempBanReason($name);
            $expire    = $dataManager->getTempBans()[$name]['expire'];
            $remaining = max(0, $expire - time());

            $timeMessage = gmdate('H:i:s', $remaining);
            $player->kick(TextFormat::RED . "You are temporarily banned for $timeMessage.\nReason: $reason");

            return true;
        }

        return false;
    }
}
