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

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\block\Water;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;

class RTPCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('RTPCommand', 'Random teleport to world', '/rtp [world]', ['rtp', 'wilderness', 'wild']);
        $this->setPrefix('rtp.prefix');
        $this->setPermission('randomteleport');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $worldName = $args[0] ?? $sender->getWorld()->getFolderName();
        $world     = Server::getInstance()->getWorldManager()->getWorldByName($worldName);
        if ($world === null) {
            $sender->sendMessage($prefix . $lang->translateString('error.world.null'));

            return;
        }

        if (PEConfig::isRandomTeleportWorldBlocked($world)) {
            $sender->sendMessage($prefix . $lang->translateString('error.blacklist', [$world->getFolderName()]));

            return;
        }

        $rangeX  = PEConfig::getRandomTeleportRange('x');
        $rangeZ  = PEConfig::getRandomTeleportRange('z');
        $timeOut = PEConfig::getRandomTeleportTimeOut();

        $sender->sendMessage($prefix . $lang->translateString('rtp.teleporting'));

        $this->attemptTeleport($sender, $prefix, $lang, $world, $rangeX, $rangeZ, $timeOut);
    }

    private function attemptTeleport(Player $sender, string $prefix, PELang $lang, World $world, array $rangeX, array $rangeZ, int &$timeOut): void
    {
        if ($timeOut <= 0) {
            $sender->sendMessage($prefix . $lang->translateString('rtp.error.failed', [$world->getFolderName()]));

            return;
        }

        $x = random_int($rangeX[0], $rangeX[1]);
        $z = random_int($rangeZ[0], $rangeZ[1]);

        $world->orderChunkPopulation($x >> 4, $z >> 4, null)->onCompletion(
            function () use ($sender, $world, $x, $z, &$timeOut, $prefix, $lang): void {
                $y       = $world->getHighestBlockAt($x, $z) + 1;
                $blockAt = $world->getBlockAt($x, $y - 2, $z);

                if (($blockAt instanceof Water || strtolower($blockAt->getName()) === 'water') && PEConfig::isRandomTeleportAntiWater()) {
                    $timeOut--;
                    $this->attemptTeleport($sender, $prefix, $lang, $world, PEConfig::getRandomTeleportRange('x'), PEConfig::getRandomTeleportRange('z'), $timeOut);
                } else {
                    $sender->teleport(new Position($x, $y, $z, $world));
                    $sender->sendMessage($prefix . $lang->translateString('rtp.success', [$world->getFolderName()]));
                }
            },
            function () use ($sender, $prefix, $lang): void {
                $sender->sendMessage($prefix . $lang->translateString('error.chunk.teleport'));
            }
        );
    }
}
