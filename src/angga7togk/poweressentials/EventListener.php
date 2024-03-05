<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\message\Message;
use angga7togk\poweressentials\utils\ConfigUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use pocketmine\world\Position;

class EventListener implements Listener{

	public function gamemodeSetOnJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		if (ConfigUtils::isGamemodeJoin()){
			$gamemode = ConfigUtils::getGamemodeJoin();
			if ($gamemode != null){
				$player->setGamemode($gamemode);
			}
		}
	}

	public function spawnToLobbyOnJoin(PlayerJoinEvent $event):void{
		$player = $event->getPlayer();
		if (ConfigUtils::isSpawnLobbyJoin()) {
			if (PowerEssentials::$lobby->exists("position")) {
				$posLobby = PowerEssentials::$lobby->get("position");
				$world = Server::getInstance()->getWorldManager()->getWorldByName($posLobby[3]);
				if ($world != null) {
					$pos = new Position((float)$posLobby[0], (float)$posLobby[1], (float)$posLobby[2], $world);
					$player->teleport($pos);
				}

			}
		}
	}

	public function antiNamespaceJoin(PlayerJoinEvent $event):void{
		$player = $event->getPlayer();
		if(!$player->hasPermission("poweressentials.antinamespace.bypass")){
			if (ConfigUtils::isAntiNamespace()){
				if(strpos($player->getName(), " ")){
					$player->kick(Message::getMessage()['general']['no-namespace']);
				}
			}
		}
	}
}
