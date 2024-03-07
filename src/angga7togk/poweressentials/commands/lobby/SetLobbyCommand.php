<?php

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use angga7togk\poweressentials\PowerEssentials;
use JsonException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SetLobbyCommand extends Command
{
    public function __construct()
    {
        parent::__construct("setlobby", "set lobby spawn position", "/setlobby", ["sethub"]);
        $this->setPermission(PermissionConstant::ESSENTIALS_COMMAND_SETLOBBY);
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) return;
        if(!$sender instanceof Player){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.cmd.console"));
            return;
        }
        $pos = $sender->getPosition();
        PowerEssentials::$lobby->setNested("position", [(int)$pos->x, (int)$pos->y, (int)$pos->z, (string)$pos->getWorld()->getFolderName()]);
        PowerEssentials::$lobby->save();
        $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "lobby.set"));
    }
}