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
        $posLobby = PowerEssentials::getInstance()->getDataManager()->getLobby();
        if($posLobby === null){
            $sender->sendMessage($prefix . $msg['noset']);
            return;
        }
        $sender->teleport($posLobby);
        $sender->sendMessage($prefix . $msg['teleported']);
    }
}
