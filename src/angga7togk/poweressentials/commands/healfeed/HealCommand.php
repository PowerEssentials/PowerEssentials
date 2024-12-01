<?php

namespace angga7togk\poweressentials\commands\healfeed;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class HealCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('heal', 'healing', '/heal');
    $this->setPermission('heal');
    $this->setPrefix('healfeed.prefix');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {

    if (isset($args[0])) {
      if (!$sender->hasPermission(self::PREFIX_PERMISSION . 'heal.other')) {
        $sender->sendMessage($prefix . $lang->translateString('error.permission'));
        return;
      }
      $target = Server::getInstance()->getPlayerExact($args[0]);
      if ($target == null) {
        $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
        return;
      }
      
      $target->setHealth($target->getMaxHealth());
      $target->sendMessage($prefix . $lang->translateString('healfeed.heal'));
      $sender->sendMessage($prefix . $lang->translateString('healfeed.heal.other', [$target->getName()]));
    } else {
      if ($sender instanceof Player) {
        $sender->setHealth($sender->getMaxHealth());
        $sender->sendMessage($prefix . $lang->translateString('healfeed.heal'));
      } else {
        $sender->sendMessage($prefix . $lang->translateString('error.console'));
      }
    }
  }
}
