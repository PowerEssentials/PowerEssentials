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

class BlessCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('bless', 'Remove negative effects', '/bless [player]');
        $this->setPrefix('bless.prefix');
        $this->setPermission('bless');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        $target = $sender;
        if (isset($args[0])) {
            $target = Server::getInstance()->getPlayerExact($args[0]);
            if ($target === null) {
                $sender->sendMessage($prefix . $lang->translateString('error.player.null'));

                return;
            }
        }
        if ($target->getName() !== $sender->getName() && $sender->hasPermission('poweressentials.bless.other')) {
            $sender->sendMessage($prefix . $lang->translateString('error.permission'));

            return;
        }

        $hasNegativeEffects = $this->removeNegativeEffects($target);

        if ($hasNegativeEffects) {
            $sender->sendMessage($prefix . $lang->translateString('bless.success', [$target->getName()]));
        } else {
            $sender->sendMessage($prefix . $lang->translateString('bless.error.blessed', [$target->getName()]));
        }
    }

    /** Credit Code: https://github.com/Wildan-dev461/BlessCommand/blob/d3690651d0f4f2f29232fb52fa40889e4b60a000/src/Wildan/bless/BlessCommand.php#L37 */
    private function removeNegativeEffects(Player $player): bool
    {
        $hasNegativeEffects = false;
        foreach ($player->getEffects()->all() as $effect) {
            if ($effect->getType()->isBad()) {
                $player->getEffects()->remove($effect->getType());
                $hasNegativeEffects = true;
            }
        }

        return $hasNegativeEffects;
    }
}
