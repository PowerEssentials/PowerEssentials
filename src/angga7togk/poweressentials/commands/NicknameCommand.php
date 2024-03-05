<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\utils\ConfigUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class NicknameCommand extends PECommand
{

	private array $nicknames = [];

	public function __construct()
	{
		parent::__construct("nickname", "change nickname player", '/nickname help', ['nick', 'changenick', 'cn']);
		$this->setPermission("nickname");
	}

	public function run(CommandSender $sender, array $message, array $args): void
	{
		$msg = $message['nickname'];
		$prefix = $msg['prefix'];
		if (!$this->testPermission($sender)) return;
		if(!$sender instanceof Player){
			$sender->sendMessage($prefix . $message['general']['cmd-console']);
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage($prefix . $this->getUsage());
			return;
		}

		$nickname = $args[0];
		if(ConfigUtils::isBlacklistNickname($nickname)){
			$sender->sendMessage($prefix . str_replace("{name}", $nickname, $msg['blacklist']));
			return;
		}
		if(strlen($nickname) > ($max = ConfigUtils::getMaxCharNickname())){
			$sender->sendMessage($prefix . str_replace("{max}", $max, $msg['max-char']));
			return;
		}
		$targetName = $args[1] ?? $sender->getName();
		$target = Server::getInstance()->getPlayerByPrefix($targetName);
		if($target == null){
			$sender->sendMessage($prefix . $message['general']['player-null']);
			return;
		}
		$targetIsSelf = strtolower($target->getName()) == strtolower($sender->getName());
		if (!$targetIsSelf && !$sender->hasPermission(self::PREFIX_PERMISSION . "nickname.other")){
			$sender->sendMessage($prefix . $message['general']['no-perm']);
			return;
		}
		if($nickname == 'reset'){
			$target->setDisplayName($target->getName());
			if (($key = array_search($target->getName(), $this->nicknames)) !== false) {
				unset($this->nicknames[$key]);
			}
			$target->sendMessage($prefix . str_replace("{player}", $targetIsSelf ? "Your" : $target->getName(), $msg['reset']));
			if (!$targetIsSelf) $sender->sendMessage($prefix . str_replace("{player}", $target->getName(), $msg['reset']));
		}elseif($nickname == 'help'){
			$sender->sendMessage(TextFormat::GOLD . "Nickname help\n/nickname <nickname> [player]\n/nickname reset [player]");
		} else{
			$target->setDisplayName($nickname);
			$this->nicknames[$target->getName()] = $nickname;
			$target->sendMessage($prefix . str_replace(["{player}", "{name}"], [$targetIsSelf ? "Your" : $target->getName(), $nickname], $msg['change']));
			if (!$targetIsSelf) $sender->sendMessage($prefix . str_replace(["{player}", "{name}"], [$target->getName(), $nickname], $msg['change']));
		}
	}
}