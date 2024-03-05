<?php

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\PowerEssentials;
use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SetLobbyCommand extends PECommand
{
    public function __construct()
    {
        parent::__construct("setlobby", "set lobby spawn position", "/setlobby", ["sethub"]);
        $this->setPermission("setlobby");
    }

    /**
     * @throws JsonException
     */
    public function run(CommandSender $sender, array $message, array $args): void
    {
        $msg = $message['lobby'];
		$prefix = $msg['prefix'];
        if (!$this->testPermission($sender)) return;
        if(!$sender instanceof Player){
            $sender->sendMessage($prefix . $message['general']['cmd-console']);
            return;
        }
        $pos = $sender->getPosition();
        PowerEssentials::$lobby->setNested("position", [(int)$pos->x, (int)$pos->y, (int)$pos->z, (string)$pos->getWorld()->getFolderName()]);
        PowerEssentials::$lobby->save();
        $sender->sendMessage($prefix . $msg['set']);
    }
}