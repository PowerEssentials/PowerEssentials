<?php

namespace angga7togk\poweressentials\commands\gamemode;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class CreativeCommand extends PECommand{

	public function __construct()
	{
		parent::__construct("gmc", "change creative mode");
		$this->setPrefix("gamemode.prefix");
		$this->setPermission("gamemode.gmc");
	}

	public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
	{
		if (isset($args[0])) {
			if (!$sender->hasPermission(self::PREFIX_PERMISSION . "gamemode.other")) {
				$sender->sendMessage($prefix . $lang->translateString('error.permission'));
				return;
			}
			$target = Server::getInstance()->getPlayerExact($args[0]);
			if ($target == null) {
				$sender->sendMessage($prefix . $lang->translateString('error.player.null'));
				return;
			}
			$target->setGamemode(GameMode::CREATIVE());
			$target->sendMessage($prefix . $lang->translateString('gamemode.changed', [
				$target->getName(),
				"creative"
			]));
			$sender->sendMessage($prefix . $lang->translateString('gamemode.changed', [
				$target->getName(),
				"creative"
			]));
		} else {
			if (!$sender instanceof Player) {
				$sender->sendMessage($prefix . $lang->translateString('error.console'));
				return;
			}
			$sender->setGamemode(GameMode::CREATIVE());
			$sender->sendMessage($prefix . $lang->translateString('gamemode.changed', [
				$sender->getName(),
				"creative"
			]));
		}
	}
}
