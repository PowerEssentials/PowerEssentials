<?php

namespace angga7togk\poweressentials\commands\gamemode;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class SpectatorCommand extends Command {

    public function __construct()
    {
        parent::__construct("gmspc", "change spectator mode");
        $this->setPermission(PermissionConstant::ESSENTIALS_COMMAND_GMSPC);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_GMSPC)){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
            return;
        }

        if (isset($args[0])){
            if (!$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_GMSPC_OTHER)){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
                return;
            }
            $target = Server::getInstance()->getPlayerExact($args[0]) ?? Server::getInstance()->getPlayerByPrefix($args[0]);
            if($target == null){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.player.null"));
                return;
            }
            $target->setGamemode(GameMode::SPECTATOR());
            $target->sendMessage(LanguageManager::getTranslator()->translate($target, "gamemode.change.self", [
                "{%gamemode}" => "Spectator"
            ]));
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "gamemode.change.other", [
                "{%player}" => $target->getName(),
                "{%gamemode}" => "Spectator"
            ]));
        }else{
            if(!$sender instanceof Player){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.cmd.console"));
                return;
            }
            $sender->setGamemode(GameMode::SPECTATOR());
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "gamemode.change.self", [
                "{%gamemode}" => "Spectator"
            ]));
        }
    }
}
