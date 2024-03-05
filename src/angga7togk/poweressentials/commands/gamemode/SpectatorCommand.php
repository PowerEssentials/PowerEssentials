<?php

namespace angga7togk\poweressentials\commands\gamemode;

use angga7togk\poweressentials\commands\PECommand;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class SpectatorCommand extends PECommand{

	public function __construct()
	{
		parent::__construct("gmspc", "change spectator mode", null, ["gmsp"]);
		$this->setPermission("gamemode");
	}

	public function run(CommandSender $sender, array $message, array $args): void
	{
		$msg = $message['gamemode'];
		$prefix = $msg['prefix'];
		if ($this->testPermission($sender)){
			if (!$sender->hasPermission(self::PREFIX_PERMISSION . "gmspc")){
				$sender->sendMessage($prefix . $message['general']['no-perm']);
				return;
			}
		}else{
			return;
		}

		if (isset($args[0])){
			if (!$sender->hasPermission(self::PREFIX_PERMISSION . "gamemode.other")){
				$sender->sendMessage($prefix . $message['general']['no-perm']);
				return;
			}
			$target = Server::getInstance()->getPlayerByPrefix($args[0]);
			if($target == null){
				$sender->sendMessage($prefix . $message['general']['player-null']);
				return;
			}
			$target->setGamemode(GameMode::SPECTATOR());
			$target->sendMessage($prefix . str_replace(["{player}", "{gamemode}"], ["your", "Spectator"], $msg['change']));
			$sender->sendMessage($prefix . str_replace(["{player}", "{gamemode}"], [$target->getName(), "Spectator"], $msg['change']));
		}else{
			if(!$sender instanceof Player){
				$sender->sendMessage($prefix . $message['general']['cmd-console']);
				return;
			}
			$sender->setGamemode(GameMode::SPECTATOR());
			$sender->sendMessage($prefix . str_replace(["{player}", "{gamemode}"], ["your", "Spectator"], $msg['change']));
		}

	}
}
