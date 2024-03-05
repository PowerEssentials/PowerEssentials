<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class FlyCommand extends PECommand {

	private array $flying = [];

	public function __construct()
	{
		parent::__construct("fly", "flying mode in server");
		$this->setPermission("fly");
	}

	public function run(CommandSender $sender, array $message, array $args): void
	{
		$msg = $message['fly'];
		$prefix = $msg['prefix'];
		if (!$this->testPermission($sender)) return;

		if(isset($args[0])){
			if(!$sender->hasPermission(self::PREFIX_PERMISSION . "fly.other")){
				$sender->sendMessage($prefix . $message['general']['no-perm']);
				return;
			}
			$target = Server::getInstance()->getPlayerByPrefix($args[0]);
			if($target == null){
				$sender->sendMessage($prefix . $message['general']['player-null']);
				return;
			}

			if(in_array($target->getName(), $this->flying)){
				$sender->sendMessage($prefix . str_replace("{player}", $target->getName(), $msg['disable-other']));
				if (($key = array_search($target->getName(), $this->flying)) !== false) {
					unset($this->flying[$key]);
					$target->sendMessage($prefix . $msg['disable']);
				}
				$target->setAllowFlight(false);
			}else{
				$sender->sendMessage($prefix . str_replace("{player}", $target->getName(), $msg['enable-other']));
				$this->flying[] = $target->getName();
				$target->sendMessage($prefix . $msg['enable']);
				$target->setAllowFlight(true);
			}
		}else{
			if(!$sender instanceof Player){
				$sender->sendMessage($prefix . $message['general']['cmd-console']);
				return;
			}
			if(in_array($sender->getName(), $this->flying)){
				if (($key = array_search($sender->getName(), $this->flying)) !== false) {
					unset($this->flying[$key]);
				}
				$sender->sendMessage($prefix . $msg['disable']);
				$sender->setAllowFlight(false);
			}else{
				$this->flying[] = $sender->getName();
				$sender->sendMessage($prefix . $msg['enable']);
				$sender->setAllowFlight(true);
			}
		}
	}
}
