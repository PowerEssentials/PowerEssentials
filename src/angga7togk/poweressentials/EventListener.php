<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\user\NicknameManager;
use angga7togk\poweressentials\message\Message;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;

class EventListener implements Listener
{

	public function __construct(private PowerEssentials $plugin) {}

	public function onLogin(PlayerLoginEvent $event): void{
		$player = $event->getPlayer();
		$this->plugin->registerUserManager($player);

		// Anti namespace
		if (!$player->hasPermission("poweressentials.antinamespace.bypass")) {
			if (PEConfig::isAntiNamespace()) {
				if (strpos($player->getName(), " ")) {
					$player->kick(Message::getMessage()['general']['no-namespace']);
				}
			}
		}


	}

	public function onQuit(PlayerQuitEvent $event): void{
		$this->plugin->unregisterUserManager($event->getPlayer());
	}


	public function onJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		$mgr = $this->plugin->getUserManager($player);

		// Coordinates
		if ($mgr->getCoordinatesShow()) {
			$pk = new GameRulesChangedPacket();
			$pk->gameRules = ["showcoordinates" => new BoolGameRule(true, false)];
			$event->getPlayer()->getNetworkSession()->sendDataPacket($pk);
		}

		// Custom Nickname
		if (($nick = $mgr->getCustomNick()) != null) {
			$player->setDisplayName($nick);
		}

		// Force Gamemode
		if (PEConfig::isGamemodeJoin()) {
			if (($gamemode = PEConfig::getGamemodeJoin()) != null) {
				$player->setGamemode($gamemode);
			}
		}

		// Spawn Lobby 
		if (PEConfig::isSpawnLobbyJoin()) {
			if (($posLobby = $this->plugin->getDataManager()->getLobby()) != null) {
				$player->teleport($posLobby);
			}
		}
	}
}
