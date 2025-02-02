<?php

namespace angga7togk\poweressentials\commands\mute;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use angga7togk\poweressentials\managers\UserManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MuteCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct("mute", "Mute a player", "/mute <player> [reason]", []);
        $this->setPrefix("mute.prefix");
        $this->setPermission("mute");
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($prefix . $lang->translateString('error.permission'));
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage($prefix . $lang->translateString('mute.usage'));
            return;
        }

        $playerName = array_shift($args);
        $reason = empty($args) ? $lang->translateString('mute.default_reason') : implode(" ", $args);

        $player = PowerEssentials::getInstance()->getServer()->getPlayerByPrefix($playerName);
        $userManager = PowerEssentials::getInstance()->getUserManager();

        if ($userManager->isMuted($playerName)) {
            $sender->sendMessage($prefix . $lang->translateString('mute.already_muted', [$playerName]));
            return;
        }

        $userManager->mutePlayer($playerName, $reason);
        $sender->sendMessage($prefix . $lang->translateString('mute.success', [$playerName, $reason]));

        if ($player instanceof Player) {
            $player->sendMessage($prefix . $lang->translateString('mute.notify', [$reason]));
        }
    }
}
