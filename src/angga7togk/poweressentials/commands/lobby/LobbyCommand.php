<?php

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LobbyCommand extends PECommand
{

    public function __construct()
    {
        parent::__construct("lobby", "teleport to lobby spawn", "/lobby", ["hub", "lobbytp"]);
        $this->setPrefix("lobby.prefix");
        $this->setPermission("lobby");
    }
    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->getPrefix() . $lang->translateString('error.console'));
            return;
        }
        if (($posLobby = PowerEssentials::getInstance()->getDataManager()->getLobby()) === null) {
            $sender->sendMessage($this->getPrefix() . $lang->translateString('error.null'));
            return;
        }
        $sender->teleport($posLobby);
        $sender->sendMessage($this->getPrefix() . $lang->translateString('lobby.teleported'));
    }
}
