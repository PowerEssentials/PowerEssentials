<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\message\Message;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

abstract class PECommand extends Command{
    const PREFIX_PERMISSION = "poweressentials.";
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function setPermission(?string $permission): void
    {
        parent::setPermission(self::PREFIX_PERMISSION . $permission);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        $this->run($sender, Message::getMessage(), $args);
    }

    abstract public function run(CommandSender $sender, array $message, array $args):void;
}
