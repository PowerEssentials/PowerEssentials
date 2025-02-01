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
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SizeCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct('size', 'Set size of player', '/size <size: 0.5 or others | reset>');
        $this->setPrefix('size.prefix');
        $this->setPermission('size');
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($prefix . $lang->translateString('error.console'));

            return;
        }

        if (!isset($args[0])) {
            $sender->sendMessage($prefix . $this->getUsage());

            return;
        }

        if ($args[0] == 'reset') {
            $args[0] = 1;
        }

        if (!is_numeric($args[0])) {
            $sender->sendMessage($prefix . $lang->translateString('error.numeric'), [$args[0]]);

            return;
        }

        if ((float) $args[0] < 0.1 || (float) $args[0] > PEConfig::getSizeMax()) {
            $sender->sendMessage($prefix . $lang->translateString('size.error.invalid'));

            return;
        }
        $sender->setScale((float) $args[0]);
        $sender->sendMessage($prefix . $lang->translateString('size.success', [$args[0]]));
    }
}
