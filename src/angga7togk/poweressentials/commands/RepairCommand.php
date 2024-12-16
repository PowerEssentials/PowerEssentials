<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;

class RepairCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct("repair", "Repair item in hand", "/repair <hand|all> [player]");
    $this->setPrefix("repair.prefix");
    $this->setPermission("repair");
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
    $target = $sender;
    if (isset($args[1])) {
      if (!$sender->hasPermission('poweressentials.repair.other')) {
        $sender->sendMessage($prefix . $lang->translateString('error.permission'));
        return;
      }

      $target = $sender->getServer()->getPlayerExact($args[1]);
      if ($target === null) {
        $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
        return;
      }
    }

    switch (strtolower($args[0])) {
      case "hand":
        $item = $target->getInventory()->getItemInHand();
        if ($item->isNull()) {
          $sender->sendMessage($prefix . $lang->translateString('error.hold.item'));
          return;
        }
        if (!($item instanceof Durable)) {
          $sender->sendMessage($prefix . $lang->translateString('error.item.invalid'));
          return;
        }
        $item->setDamage(0);
        $target->getInventory()->setItemInHand($item);
        $sender->sendMessage($prefix . $lang->translateString('repair.success'));
        break;
      case "all":
        if (!$sender->hasPermission('poweressentials.repair.all')) {
          $sender->sendMessage($prefix . $lang->translateString('error.permission'));
          return;
        }

        foreach ($target->getInventory()->getContents() as $slot => $item) {
          if ($item->isNull()) continue;
          if (!($item instanceof Durable)) continue;
          $target->getInventory()->setItem($slot, $item->setDamage(0));
        }
        foreach ($target->getOffHandInventory()->getContents() as $slot => $item) {
          if ($item->isNull()) continue;
          if (!($item instanceof Durable)) continue;
          $target->getOffHandInventory()->setItem($slot, $item->setDamage(0));
        }
        foreach ($target->getArmorInventory()->getContents() as $slot => $item) {
          if ($item->isNull()) continue;
          if (!($item instanceof Durable)) continue;
          $target->getArmorInventory()->setItem($slot, $item->setDamage(0));
        }
        $sender->sendMessage($prefix . $lang->translateString('repair.success.all'));
        break;
      default:
        $sender->sendMessage($prefix . $this->getUsage());
        break;
    }
  }
}
