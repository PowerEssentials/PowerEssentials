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
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\utils\TextFormat;

abstract class PECommand extends Command implements PluginOwned
{
    use PluginOwnedTrait;

    public const PREFIX_PERMISSION = 'poweressentials.';
    private string $prefix;

    public function __construct(string $name, Translatable|string $description = '', Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function setPrefix(string $prefixLang): void
    {
        $this->prefix = TextFormat::GOLD . PELang::fromConsole()->translateString($prefixLang) . ' ';
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPermission(?string $permission): void
    {
        parent::setPermission(self::PREFIX_PERMISSION . $permission);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            return;
        }
        $this->run($sender, $this->getPrefix(), PELang::fromConsole(), $args);
    }

    abstract public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void;
}
