<?php

namespace angga7togk\poweressentials\commands\gamemode;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class SurvivalCommand extends Command {

    public function __construct()
    {
        parent::__construct("gms", "change survival mode");
        $this->setPermission(PermissionConstant::ESSENTIALS_COMMAND_GMS);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_GMS)){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
            return;
        }

        if (isset($args[0])){
            if (!$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_GMS_OTHER)){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
                return;
            }
            $target = Server::getInstance()->getPlayerExact($args[0]) ?? Server::getInstance()->getPlayerByPrefix($args[0]);
            if($target == null){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.player.null"));
                return;
            }
            $target->setGamemode(GameMode::SURVIVAL());
            $target->sendMessage(LanguageManager::getTranslator()->translate($target, "gamemode.change", [
                "{%player}" => "Your",
                "{%gamemode}" => "Survival"
            ]));
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "gamemode.change", [
                "{%player}" => $target->getName(),
                "{%gamemode}" => "Survival"
            ]));
        }else{
            if(!$sender instanceof Player){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.cmd.console"));
                return;
            }
            $sender->setGamemode(GameMode::ADVENTURE());
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "gamemode.change", [
                "{%player}" => "Your",
                "{%gamemode}" => "Survival"
            ]));
        }
    }
}
