<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\user\NicknameManager;
use angga7togk\poweressentials\message\Message;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\Server;
use pocketmine\world\Position;

class EventListener implements Listener
{

	public function __construct(private PowerEssentials $plugin) {}

	public function gamemodeSetOnJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		if (PEConfig::isGamemodeJoin()) {
			$gamemode = PEConfig::getGamemodeJoin();
			if ($gamemode != null) {
				$player->setGamemode($gamemode);
			}
		}
	}

	public function spawnToLobbyOnJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		if (PEConfig::isSpawnLobbyJoin()) {
			$posLobby = $this->plugin->getDataManager()->getLobby();
			if ($posLobby != null) {
				$player->teleport($posLobby);
			}
		}
	}

	public function setCustomNickOnJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		$nickMgr = new NicknameManager($player);
		if ($nick = $nickMgr->getCustomNick() != null) {
			$player->setDisplayName($nick);
		}
	}

	public function coordinatesOnJoin(PlayerJoinEvent $event): void
	{
		if (PEConfig::isShowCoordinates()) {
			$pk = new GameRulesChangedPacket();
			$pk->gameRules = ["showcoordinates" => new BoolGameRule(true, false)];
			$event->getPlayer()->getNetworkSession()->sendDataPacket($pk);
		}
	}

	public function antiNamespaceJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		if (!$player->hasPermission("poweressentials.antinamespace.bypass")) {
			if (PEConfig::isAntiNamespace()) {
				if (strpos($player->getName(), " ")) {
					$player->kick(Message::getMessage()['general']['no-namespace']);
				}
			}
		}
	}
}
