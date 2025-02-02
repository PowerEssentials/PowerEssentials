<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;

class UnmuteCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct("unmute", "Unmute a player", "/unmute <player>", []);
        $this->setPrefix("unmute.prefix");
        $this->setPermission("mute");
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($prefix . $lang->translateString('error.permission'));
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage($prefix . $lang->translateString('unmute.usage'));
            return;
        }

        $playerName = $args[0];
        $userManager = PowerEssentials::getInstance()->getUserManager();

        if (!$userManager->isMuted($playerName)) {
            $sender->sendMessage($prefix . $lang->translateString('unmute.not_muted', [$playerName]));
            return;
        }

        $userManager->unmutePlayer($playerName);
        $sender->sendMessage($prefix . $lang->translateString('unmute.success', [$playerName]));
    }
}
