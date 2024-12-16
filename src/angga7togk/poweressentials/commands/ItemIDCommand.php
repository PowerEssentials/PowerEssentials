<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class ItemIDCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("itemid", "Check detail item in hand", "/itemid", ["itemdb", "itemname"]);
    $this->setPrefix('itemid.prefix');
    $this->setPermission('itemid');
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

    $sender->sendMessage($prefix . $lang->translateString('itemid.success', [
      $item->getName(),
      $item->getCustomName(),
      $item->getVanillaName(),
      StringToItemParser::getInstance()->lookupAliases($item)[0],
      $item->getTypeId(),
      $item->getStateId(),
    ]));
  }
}
