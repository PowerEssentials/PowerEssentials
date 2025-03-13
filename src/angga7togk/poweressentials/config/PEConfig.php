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
        return (float) (self::$config->get('config-version', 0.0));
    }

    public static function getLang(): string
    {
        return (string) self::$config->get('language', 'en');
    }

    public static function isGamemodeJoin(): bool
    {
        return (bool) self::$config->get('gamemode-join-enable', false);
    }

    public static function getGamemodeJoin(): ?GameMode
    {
        $gamemode = self::$config->get('gamemode-join');
        return $gamemode !== null ? GameMode::fromString((string) $gamemode) : null;
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
        foreach ($blacklist as $nickBL) {
            if (strpos($nick, (string) $nickBL) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function getMaxCharNickname(): int
    {
        return (int) self::$config->get('nickname-max-char', 16);
    }

    public static function isCommandDisabled(string $commandKey): bool
    {
        $disabledCommands = self::$config->get('disable-commands', []);
        return in_array($commandKey, $disabledCommands, true);
    }

    public static function isWorldBlacklistSetHome(string $world): bool
    {
        $blacklists = self::$config->get('home-world-blacklists', []);
        return in_array($world, $blacklists, true);
    }

    public static function isHomePermissionLimit(): bool
    {
        return (bool) self::$config->get('home-permission-limit', false);
    }

    public static function getHomePermissionDefaultLimit(): int
    {
        return (int) self::$config->get('home-permission-default-limit', 1);
    }

    public static function getHomePermissionLimits(): array
    {
        return (array) self::$config->get('home-permission-limits', []);
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
        return (int) self::$config->get('random-teleport-timeout', 10);
    }

    /** @return int[] */
    public static function getRandomTeleportRange(string $coordType): array
    {
        $range = self::$config->get('random-teleport-range', []);
        return (array) ($range[$coordType] ?? []);
    }

    public static function isRandomTeleportWorldBlocked(World $world): bool
    {
        $worldName = $world->getFolderName();
        $blacklists = self::$config->get('random-teleport-world-blacklists', []);
        return in_array($worldName, $blacklists, true);
    }

    public static function getSizeMax(): float
    {
        return (float) self::$config->get('size-max', 5.0);
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
        return (int) self::$config->get('cancel-sleep-vote-count', 3);
    }

    public static function getOneSleepCancelVoteTimeout(): int
    {
        return (int) self::$config->get('cancel-sleep-vote-timeout', 10);
    }

    public function checkTempBan(Player $player): bool
    {
        $name = $player->getName();
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
