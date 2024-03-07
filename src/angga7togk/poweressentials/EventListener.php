<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use angga7togk\poweressentials\utils\ConfigUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use pocketmine\world\Position;

class EventListener implements Listener{

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if(!$player->hasPermission(PermissionConstant::ESSENTIALS_ANTINAMESPACE_BYPASS)){
            if (ConfigUtils::isAntiNamespace()){
                if(strpos($player->getName(), " ")){
                    $player->kick(LanguageManager::getTranslator()->translate($player, "general.no.namepace"));
                }
            }
        }
        if (ConfigUtils::isGamemodeJoin()){
            $gamemode = ConfigUtils::getGamemodeJoin();
            if ($gamemode != null){
                $player->setGamemode($gamemode);
            }
        }
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
}
