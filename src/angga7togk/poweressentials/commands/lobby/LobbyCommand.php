<?php

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class LobbyCommand extends Command {

    public function __construct()
    {
        parent::__construct("lobby", "teleport to lobby spawn", "/lobby", ["hub", "lobbytp"]);
        $this->setPermission(PermissionConstant::ESSENTIALS_COMMAND_LOBBY);
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) return;
        if(!$sender instanceof Player){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
            return;
        }
        if(!PowerEssentials::$lobby->exists("position")){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "lobby.unset"));
            return;
        }

        $posLobby = PowerEssentials::$lobby->get("position");
        $world = Server::getInstance()->getWorldManager()->getWorldByName($posLobby[3]);
        if($world == null){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.world.null"));
            return;
        }

        $pos = new Position((float)$posLobby[0], (float)$posLobby[1], (float)$posLobby[2], $world);
        $sender->teleport($pos);
        $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "lobby.teleported"));
    }
}
