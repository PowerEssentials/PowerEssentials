<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\manager\DataManager;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\utils\TextFormat;

class EventListener implements Listener
{
	private DataManager $dataManager;
	public function __construct(private PowerEssentials $plugin)
	{
		$this->dataManager = $this->plugin->getDataManager();
	}

	public function onLogin(PlayerLoginEvent $event): void
	{
		$player = $event->getPlayer();
		$this->plugin->registerUserManager($player);

		// Anti namespace
		if (!$player->hasPermission("poweressentials.antinamespace.bypass")) {
			if (PEConfig::isAntiNamespace()) {
				if (strpos($player->getName(), " ")) {
					$player->kick(TextFormat::RED . PELang::fromConsole()->translateString('error.namespace'));
				}
			}
		}
	}

	public function onQuit(PlayerQuitEvent $event): void
	{
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
			if (($posLobby = $this->dataManager->getLobby()) != null) {
				$player->teleport($posLobby);
			}
		}
	}

	public function onInteraction(PlayerInteractEvent $event): void
	{
		$this->banItemEvent($event);
	}

	public function onPlace(BlockPlaceEvent $event): void
	{
		$this->banItemEvent($event);
	}

	public function onBreak(BlockBreakEvent $event)
	{
		$this->banItemEvent($event);
	}

	private function banItemEvent(Event $event)
	{
		if ($event instanceof BlockBreakEvent || $event instanceof BlockPlaceEvent || $event instanceof PlayerInteractEvent) {
			$player = $event->getPlayer();
			$itemInHand = $player->getInventory()->getItemInHand();
			$playerWorld = $player->getWorld();

			// Ban Item
			if ($this->dataManager->isBannedItem($itemInHand, $playerWorld) && !$player->hasPermission('poweressentials.banitem.bypass')) {
				$lang = PELang::fromConsole();
				$prefix = TextFormat::GOLD . $lang->translateString('banitem.prefix') . " ";

				$player->sendMessage($prefix .  $lang->translateString('banitem.error.item.is.banned'));
				$event->cancel();
			}
		}
	}
}
