<?php

namespace angga7togk\poweressentials\commands\banitem;

use angga7togk\poweressentials\commands\PECommand;
use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\PowerEssentials;
use pocketmine\block\Air;
use pocketmine\player\Player;
use pocketmine\Server;

class BanItemCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('banitem', 'Banned item in hand from world', '/banitem [world]');
    $this->setPrefix('banitem.prefix');
    $this->setPermission('banitem');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!$sender instanceof Player) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }

    $item = $sender->getInventory()->getItemInHand();

    if($item->isNull()){
      $sender->sendMessage($prefix . $lang->translateString('error.hold.item'));
      return;
    }

    $world = isset($args[0]) ? Server::getInstance()->getWorldManager()->getWorldByName($args[0]) : $sender->getWorld();

    if ($world === null || !$world->isLoaded()) {
      $sender->sendMessage($prefix . $lang->translateString('error.world.null'));
      return;
    }

    $mgr = PowerEssentials::getInstance()->getDataManager();
    $mgr->banItem($item, $world);

    $sender->sendMessage($prefix . $lang->translateString('banitem.ban.success'));
  }
}
