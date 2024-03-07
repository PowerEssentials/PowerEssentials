<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionConstant;
use angga7togk\poweressentials\utils\ConfigUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class NicknameCommand extends Command
{

    /** @var array $nicknames */
	private array $nicknames = [];

	public function __construct()
	{
		parent::__construct("nickname", "change nickname player", '/nickname help', ['nick', 'changenick', 'cn']);
		$this->setPermission(PermissionConstant::ESSENTIALS_COMMAND_NICKNAME);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
		if(!$sender instanceof Player){
			$sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.cmd.console"));
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage($this->getUsage());
			return;
		}

		$nickname = $args[0];
		if(ConfigUtils::isBlacklistNickname($nickname)){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "nickname.blacklist", [
                "{%name}" => $nickname
            ]));
			return;
		}
		if(strlen($nickname) > ($max = ConfigUtils::getMaxCharNickname())){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "nickname.max.char", [
                "{%max}" => $max
            ]));
			return;
		}
		$targetName = $args[1] ?? $sender->getName();
		$target = Server::getInstance()->getPlayerExact($args[0]) ?? Server::getInstance()->getPlayerByPrefix($targetName);
		if($target == null){
			$sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.player.null"));
			return;
		}
		$targetIsSelf = strtolower($target->getName()) == strtolower($sender->getName());
		if (!$targetIsSelf && !$sender->hasPermission(PermissionConstant::ESSENTIALS_COMMAND_NICKNAME_OTHER)){
            $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "general.no.permission"));
			return;
		}
		if($nickname == 'reset'){
			$target->setDisplayName($target->getName());
			if (($key = array_search($target->getName(), $this->nicknames)) !== false) {
				unset($this->nicknames[$key]);
			}
            $target->sendMessage(LanguageManager::getTranslator()->translate($target, "nickname.reset", [
                "{%player}" => $targetIsSelf ? "Your" : $target->getName()
            ]));
			if (!$targetIsSelf) $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "nickname.reset", [
                "{%player}" => $target->getName()
            ]));
		}elseif($nickname == 'help'){
			$sender->sendMessage(TextFormat::GOLD . "Nickname help\n/nickname <nickname> [player]\n/nickname reset [player]");
		} else{
			$target->setDisplayName($nickname);
			$this->nicknames[$target->getName()] = $nickname;
            $target->sendMessage(LanguageManager::getTranslator()->translate($target, "nickname.change", [
                "{%player}" => $targetIsSelf ? "Your" : $target->getName(),
                "{%name}" => $nickname
            ]));
			if (!$targetIsSelf) $sender->sendMessage(LanguageManager::getTranslator()->translate($sender, "nickname.change", [
                "{%player}" => $target->getName(),
                "{%name}" => $nickname
            ]));
		}
	}
}