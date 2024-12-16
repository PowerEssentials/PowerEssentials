<?php

namespace angga7togk\poweressentials\commands;

use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\player\Player;

class SendItemCommand extends PECommand
{
  public function __construct()
  {
    parent::__construct('senditem', 'send item in your hand to player', '/senditem <player> [amount]');
    $this->setPrefix('senditem.prefix');
    $this->setPermission('senditem');
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
    $target = $sender->getServer()->getPlayerExact($args[0]);
    if ($target === null) {
      $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
      return;
    }

    $item = $sender->getInventory()->getItemInHand();
    if ($item->isNull()) {
      $sender->sendMessage($prefix . $lang->translateString('error.hold.item'));
      return;
    }

    if (!$target->getInventory()->canAddItem($item)) {
      $sender->sendMessage($prefix . $lang->translateString('senditem.error.inventory.full', [$target->getName()]));
      return;
    }

    $amount = isset($args[1]) ? (int)$args[1] : $item->getCount();
    if (is_numeric($amount) && $amount > 0 && $amount <= $item->getCount()) {
      $itemForSender = clone($item);
      $itemForTarget = clone($item);

      $sender->getInventory()->setItemInHand($itemForSender->setCount($itemForSender->getCount() - $amount));
      $target->getInventory()->addItem($itemForTarget->setCount($amount));
      $target->sendMessage($prefix . $lang->translateString('senditem.success.target', [$amount, $item->getName(), $sender->getName()]));
      $sender->sendMessage($prefix . $lang->translateString('senditem.success', [$amount, $item->getName(), $target->getName()]));
    }else{
      $sender->sendMessage($prefix . $lang->translateString('senditem.error.invalid.amount'));
    }
  }
}
