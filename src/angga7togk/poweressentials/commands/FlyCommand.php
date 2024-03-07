<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class FlyCommand extends Command {

    /** @var array $flying */
	private array $flying = [];

	public function __construct()
	{
		parent::__construct("fly", "flying mode in server");
		$this->setPermission(PermissionConstant::ESSENTIALS_COMMAND_FLY);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
        if (!$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_FLY)) {
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
            return;
        }

		if(isset($args[0])){
			if(!$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_FLY_OTHER)){
				$sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
				return;
			}
			$target = Server::getInstance()->getPlayerExact($args[0]) ?? Server::getInstance()->getPlayerByPrefix($args[0]);
			if($target == null){
				$sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.player.null"));
				return;
			}

			if(in_array($target->getName(), $this->flying)){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "fly.disable.other", [
                    "{%player}" => $target->getName()
                ]));
				if (($key = array_search($target->getName(), $this->flying)) !== false) {
					unset($this->flying[$key]);
                    $target->sendMessage(LanguageManager::getTranslator()->translate($target, "fly.disable.self"));
				}
				$target->setAllowFlight(false);
			}else{
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "fly.enable.other", [
                    "{%player}" => $target->getName()
                ]));
				$this->flying[] = $target->getName();
                $target->sendMessage(LanguageManager::getTranslator()->translate($target, "fly.enable.self"));
                $target->setAllowFlight(true);
			}
		}else{
			if(!$sender instanceof Player){
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.cmd.console"));
				return;
			}
			if(in_array($sender->getName(), $this->flying)){
				if (($key = array_search($sender->getName(), $this->flying)) !== false) {
					unset($this->flying[$key]);
				}
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "fly.disable.self"));
				$sender->setAllowFlight(false);
			}else{
				$this->flying[] = $sender->getName();
                $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "fly.enable.self"));
				$sender->setAllowFlight(true);
			}
		}
	}
}
