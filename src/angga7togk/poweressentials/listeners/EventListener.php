<?php

/*
 *   ____                        _____                    _   _       _
 *  |  _ \ _____      _____ _ __| ____|___ ___  ___ _ __ | |_(_) __ _| |___
 *  | |_) / _ \ \ /\ / / _ \ '__|  _| / __/ __|/ _ \ '_ \| __| |/ _` | / __|
 *  |  __/ (_) \ V  V /  __/ |  | |___\__ \__ \  __/ | | | |_| | (_| | \__ \
 *  |_|   \___/ \_/\_/ \___|_|  |_____|___/___/\___|_| |_|\__|_|\__,_|_|___/
 *
 *
 * This file is part of PowerEssentials plugins.
 *
 * (c) Angga7Togk <kiplihode123321@gmail.com>
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace angga7togk\poweressentials\listeners;

use angga7togk\poweressentials\commands\AFKCommand;
use angga7togk\poweressentials\commands\vanish\VanishCommand;
use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\manager\DataManager;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class EventListener implements Listener
{
    private DataManager $dataManager;
    private PELang $lang;
    public function __construct(private PowerEssentials $plugin)
    {
        $this->dataManager = $this->plugin->getDataManager();
        $this->lang        = PELang::fromConsole();
    }

    public function onLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $this->plugin->registerUserManager($player);

        // Anti namespace
        if (!$player->hasPermission('poweressentials.antinamespace.bypass')) {
            if (PEConfig::isAntiNamespace()) {
                if (strpos($player->getName(), ' ')) {
                    $player->kick(TextFormat::RED . PELang::fromConsole()->translateString('error.namespace'));
                }
            }
        }
    }

    public function onSleep(PlayerBedEnterEvent $event): void
    {
        $player = $event->getPlayer();
        if (count($this->plugin->getServer()->getOnlinePlayers()) !== 1) {
            if ($this->dataManager->getSlepper() === null) {
                $this->dataManager->setSlepper($player);
                $this->plugin->getServer()->broadcastMessage(TextFormat::GOLD . $this->lang->translateString('onesleep.prefix') . ' ' . $this->lang->translateString('onesleep.broadcast', [$player->getName()]));
            }
        }
    }

    public function onUnsleep(PlayerBedLeaveEvent $event): void
    {
        $player  = $event->getPlayer();
        $sleeper = $this->dataManager->getSlepper();
        if ($sleeper !== null && $sleeper->getName() === $player->getName()) {
            $this->dataManager->unsetSlepper();
        }
    }

    public function onDropPickup(EntityItemPickupEvent $event): void
    {
        $player = $event->getEntity();
        if ($player instanceof Player && VanishCommand::isVanished($player)) {
            $event->cancel();
        }
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        $this->plugin->unregisterUserManager($player);

        // Vanish unset data
        if (VanishCommand::isVanished($player)) {
            VanishCommand::unsetDataVanish($player);
        }
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $mgr    = $this->plugin->getUserManager($player);

        // Coordinates
        if ($mgr->getCoordinatesShow()) {
            $pk            = new GameRulesChangedPacket();
            $pk->gameRules = ['showcoordinates' => new BoolGameRule(true, false)];
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

    public function onHitPlayer(EntityDamageByEntityEvent $event): void
    {
        $target  = $event->getEntity();
        $damager = $event->getDamager();
        if ($target instanceof Player && $damager instanceof Player) {
            // AFK
            if (AFKCommand::isAfk($target) && !$damager->hasPermission('poweressentials.afk.bypass')) {
                $lang = PELang::fromConsole();
                $damager->sendMessage(TextFormat::GOLD . $lang->translateString('afk.prefix') . ' ' . $lang->translateString('afk.error.target.is.afk', [$target->getName()]));
            }
        }
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        if (AFKCommand::isAfk($player)) {
            $lang = PELang::fromConsole();
            $player->sendMessage(TextFormat::GOLD . $lang->translateString('afk.prefix') . ' ' . $lang->translateString('afk.disabled'));
            AFKCommand::disabledAfk($player);
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

    public function onBreak(BlockBreakEvent $event): void
    {
        $this->banItemEvent($event);
    }

    private function banItemEvent(Event $event): void
    {
        if ($event instanceof BlockBreakEvent || $event instanceof BlockPlaceEvent || $event instanceof PlayerInteractEvent) {
            $player      = $event->getPlayer();
            $itemInHand  = $player->getInventory()->getItemInHand();
            $playerWorld = $player->getWorld();

            // Ban Item
            if ($this->dataManager->isBannedItem($itemInHand, $playerWorld) && !$player->hasPermission('poweressentials.banitem.bypass')) {
                $lang   = PELang::fromConsole();
                $prefix = TextFormat::GOLD . $lang->translateString('banitem.prefix') . ' ';

                $player->sendMessage($prefix . $lang->translateString('banitem.error.item.is.banned'));
                $event->cancel();
            }
        }
    }

    public function onChat(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (PowerEssentials::getInstance()->getUserManager()->isMuted($name)) {
            $event->cancel();
            $player->sendMessage($prefix . $lang->translateString('mute.notify'));
        }
    }
}
