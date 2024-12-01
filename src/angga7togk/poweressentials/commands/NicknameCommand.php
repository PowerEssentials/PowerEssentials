<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class NicknameCommand extends PECommand
{

	public function __construct()
	{
		parent::__construct("nickname", "change nickname player", '/nickname help', ['nick', 'changenick', 'cn']);
		$this->setPrefix('nickname.prefix');
		$this->setPermission("nickname");
	}

	public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
	{
		if (!$sender instanceof Player) {
			$sender->sendMessage($prefix . $lang->translateString('error.console'));
			return;
		}


		if (!isset($args[0])) {
			$sender->sendMessage($prefix . $this->getUsage());
			return;
		}

		$nickname = $args[0];
		if (PEConfig::isBlacklistNickname($nickname)) {
			$sender->sendMessage($prefix . $lang->translateString('error.blacklist', [$nickname]));
			return;
		}
		if (strlen($nickname) > ($max = PEConfig::getMaxCharNickname())) {
			$sender->sendMessage($prefix . $lang->translateString('error.max.char', [$max]));
			return;
		}
		$targetName = $args[1] ?? $sender->getName();
		$target = Server::getInstance()->getPlayerExact($targetName);
		$mgr = PowerEssentials::getInstance()->getUserManager($sender);
		if ($target == null) {
			$sender->sendMessage($prefix . $lang->translateString('error.player.null'));
			return;
		}
		$targetIsSelf = strtolower($target->getName()) == strtolower($sender->getName());
		if (!$targetIsSelf && !$sender->hasPermission(self::PREFIX_PERMISSION . "nickname.other")) {
			$sender->sendMessage($prefix . $lang->translateString('error.permission'));
			return;
		}
		if ($nickname == 'reset') {
			$target->setDisplayName($target->getName());
			$mgr->removeCustomNick();
			$target->sendMessage($prefix . $lang->translateString('nickname.reset', [$target->getName()]));
			if (!$targetIsSelf) $sender->sendMessage($prefix . $lang->translateString('nickname.reset', [$target->getName()]));
		} elseif ($nickname == 'help') {
			$sender->sendMessage(TextFormat::GOLD . "Nickname help\n/nickname <nickname> [player]\n/nickname reset [player]");
		} else {
			$target->setDisplayName($nickname);
			$mgr->setCustomNick($nickname);
			$target->sendMessage($prefix . $lang->translateString('nickname.changed', [$target->getName(), $nickname]));
			if (!$targetIsSelf) $sender->sendMessage($prefix . $lang->translateString('nickname.changed', [$target->getName(), $nickname]));
		}
	}
}
