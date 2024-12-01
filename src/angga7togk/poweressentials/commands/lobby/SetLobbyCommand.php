<?php

namespace angga7togk\poweressentials\commands\lobby;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SetLobbyCommand extends PECommand
{
  public function __construct()
  {
    parent::__construct("setlobby", "set lobby spawn position", "/setlobby", ["sethub"]);
    $this->setPrefix("lobby.prefix");
    $this->setPermission("setlobby");
  }

  /**
   * @throws JsonException
   */
  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!$sender instanceof Player) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }
    $pos = $sender->getPosition();
    PowerEssentials::getInstance()->getDataManager()->setLobby($pos);
    $sender->sendMessage($prefix . $lang->translateString('lobby.set'));
  }
}
