<?php

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class LobbyCommand extends PECommand{

    public function __construct()
    {
        parent::__construct("lobby", "teleport to lobby spawn", "/lobby", ["hub", "lobbytp"]);
        $this->setPermission("lobby");
    }
    public function run(CommandSender $sender, array $message, array $args): void
    {
        $msg = $message['lobby'];
		$prefix = $msg['prefix'];
        if (!$this->testPermission($sender)) return;
        if(!$sender instanceof Player){
            $sender->sendMessage($prefix . $message['general']['cmd-console']);
            return;
        }
        if(!PowerEssentials::$lobby->exists("position")){
            $sender->sendMessage($prefix . $msg['noset']);
            return;
        }

        $posLobby = PowerEssentials::$lobby->get("position");
        $world = Server::getInstance()->getWorldManager()->getWorldByName($posLobby[3]);
        if($world == null){
            $sender->sendMessage($prefix . $message['general']['world-null']);
            return;
        }

        $pos = new Position((float)$posLobby[0], (float)$posLobby[1], (float)$posLobby[2], $world);
        $sender->teleport($pos);
        $sender->sendMessage($prefix . $msg['teleported']);
    }
}
