<?php

namespace angga7togk\poweressentials\utils;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\GameMode;

class ConfigUtils{

	public static function isGamemodeJoin():bool{
		return PowerEssentials::getInstance()->getConfig()->get("gamemode-join-enable");
	}

	public static function getGamemodeJoin():?GameMode{
		return GameMode::fromString(PowerEssentials::getInstance()->getConfig()->get("gamemode-join"));
	}

	public static function isSpawnLobbyJoin():bool{
		return PowerEssentials::getInstance()->getConfig()->get("spawn-lobby-join");
	}

	public static function isAntiNamespace():bool{
		return PowerEssentials::getInstance()->getConfig()->get("anti-namespace");
	}

	public static function isBlacklistNickname(string $nick):bool{
		foreach (PowerEssentials::getInstance()->getConfig()->get("blacklist-nicknames") as $nickBL){
			if (strpos($nick, $nickBL)) return true;
		}
		return false;
	}

	public static function getMaxCharNickname():int{
		return (int) PowerEssentials::getInstance()->getConfig()->get("nickname-max-char");
	}
}