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

use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\manager\DataManager;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class WorldProtectListener implements Listener
{
    private string $prefix;
    private PELang $lang;
    private DataManager $dataManager;
    public function __construct(private PowerEssentials $plugin)
    {
        $this->dataManager = $this->plugin->getDataManager();
        $this->lang        = PELang::fromConsole();
        $this->prefix      = TextFormat::GOLD . $this->lang->translateString('worldprotect.prefix') . ' ';
    }

    public function onHungerReduce(PlayerExhaustEvent $event)
    {
        $player    = $event->getPlayer();
        $worldName = $player->getWorld()->getFolderName();
        if ($this->dataManager->isWorldProtected('hunger', $worldName)) {
            $event->cancel();
        }
    }

    public function onExplosion(EntityPreExplodeEvent $event)
    {
        $worldName = $event->getEntity()->getWorld()->getFolderName();
        if ($this->dataManager->isWorldProtected('explosion', $worldName)) {
            $event->setBlockBreaking(false);
        }
    }

    public function onEntityDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $worldName = $player->getWorld()->getFolderName();

            // Anti Health Reduce
            if ($this->dataManager->isWorldProtected('health', $worldName)) {
                $player->setHealth($player->getMaxHealth());
            }

            // Fall Damage
            if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
                if ($this->dataManager->isWorldProtected('falldamage', $worldName)) {
                    $event->cancel();
                }
            }
        }
    }

    public function onHitPlayer(EntityDamageByEntityEvent $event)
    {
        if ($event->getEntity() instanceof Player) {
            $player = $event->getDamager();
            if ($player instanceof Player) {
                $worldName = $player->getWorld()->getFolderName();
                if ($this->dataManager->isWorldProtected('pvp', $worldName) && !$player->hasPermission('poweressentials.worldprotect.bypass')) {
                    $player->sendMessage($this->prefix . $this->lang->translateString('worldprotect.error.world.protected'));
                    $event->cancel();
                }
            }
        }
    }

    public function onInteraction(PlayerInteractEvent $event): void
    {
        $worldName = $event->getPlayer()->getWorld()->getFolderName();
        if ($this->dataManager->isWorldProtected('interaction', $worldName) && !$event->getPlayer()->hasPermission('poweressentials.worldprotect.bypass')) {
            $event->getPlayer()->sendMessage($this->prefix . $this->lang->translateString('worldprotect.error.world.protected'));
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event): void
    {
        $worldName = $event->getPlayer()->getWorld()->getFolderName();
        if ($this->dataManager->isWorldProtected('place', $worldName) && !$event->getPlayer()->hasPermission('poweressentials.worldprotect.bypass')) {
            $event->getPlayer()->sendMessage($this->prefix . $this->lang->translateString('worldprotect.error.world.protected'));
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $worldName = $event->getPlayer()->getWorld()->getFolderName();
        if ($this->dataManager->isWorldProtected('break', $worldName) && !$event->getPlayer()->hasPermission('poweressentials.worldprotect.bypass')) {
            ;
            $event->getPlayer()->sendMessage($this->prefix . $this->lang->translateString('worldprotect.error.world.protected'));
            $event->cancel();
        }
    }
}
