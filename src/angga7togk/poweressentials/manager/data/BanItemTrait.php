<?php

namespace angga7togk\poweressentials\manager\data;

use angga7togk\poweressentials\manager\DataManager;
use pocketmine\item\Item;
use pocketmine\world\World;

trait BanItemTrait
{

  /** @return String[] */
  public function getBannedItems(World $world): array
  {
    /** @var DataManager $manager */
    $manager = $this;
    if (!$manager->getData()->exists("banitems")) return [];
    $bans = $manager->getData()->get('banitems');
    if (!isset($bans[$world->getFolderName()])) return [];
    return $bans[$world->getFolderName()];
  }

  public function isBannedItem(Item $item, World $world): bool
  {
    $worldName = $world->getFolderName();
    $itemVanillaName = $item->getVanillaName();

    /** @var DataManager $manager */
    $manager = $this;
    if (!$manager->getData()->exists("banitems")) return false;
    $bans = $manager->getData()->get('banitems');
    if (!isset($bans[$worldName])) return false;

    return in_array($itemVanillaName, $bans[$worldName]);
  }

  public function banItem(Item $item, World $world): void
  {
    $worldName = $world->getFolderName();
    $itemVanillaName = $item->getVanillaName(); 
    $banItems = $this->getBannedItems($world);

    if (in_array($itemVanillaName, $banItems)) return;
    $banItems[] = $itemVanillaName;

    /** @var DataManager $manager */
    $manager = $this;
    $manager->getData()->setNested("banitems.$worldName", $banItems);
    $manager->getData()->save();
  }

  public function unbanItem(Item $item, World $world): void
  {
    $worldName = $world->getFolderName();
    $itemVanillaName = $item->getVanillaName();
    $banItems = $this->getBannedItems($world);

    if (!in_array($itemVanillaName, $banItems)) return;
    $banItems = array_filter($banItems, fn ($banItem) => $banItem !== $itemVanillaName);

    /** @var DataManager $manager */
    $manager = $this;
    $manager->getData()->setNested("banitems.$worldName", $banItems);
    $manager->getData()->save();
  }
}
