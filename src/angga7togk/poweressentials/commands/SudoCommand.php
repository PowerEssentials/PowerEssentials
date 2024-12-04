<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class SudoCommand extends PECommand {

    public function __construct() {
        parent::__construct("sudo", "Execute command or send message as a selected player", "/sudo <target> <cmd or msg>");
        $this->setPrefix("sudo.prefix");
        $this->setPermission("sudo");
    }

    public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage($prefix . $this->getUsage());
            return;
        }

        $targetName = array_shift($args);
        $target = Server::getInstance()->getPlayerExact($targetName);
        
        if (!$target instanceof Player) { 
            $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
            return;
        }

        $commandOrMessage = implode(" ", $args);
        $target->chat($commandOrMessage); 

        $sender->sendMessage($prefix . $lang->translateString('sudo.success', [$target->getName()]));
    }

}
