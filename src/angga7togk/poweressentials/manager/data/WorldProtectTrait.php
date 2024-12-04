<?php

namespace angga7togk\poweressentials\manager\data;

use angga7togk\poweressentials\manager\DataManager;

trait WorldProtectTrait
{

  public function getWorldProtected(string $world): array
  {
    /** @var DataManager $manager */
    $manager = $this;
    if (!$manager->getData()->exists('worldprotect')) return [];
    if (!isset($manager->getData()->get('worldprotect')[$world])) return [];
    return $manager->getData()->get('worldprotect')[$world] ?? [];
  }

  public function isWorldProtected(string $type, string $world): bool
  {
    return in_array($type, $this->getWorldProtected($world));
  }

  public function setWorldProtected(string $type, bool $value, string $worldName): void
  {
    /** @var DataManager $manager */
    $manager = $this;
    $protects = $this->getWorldProtected($worldName);

    if (!$value) {
      $protects[] = $type;
    } else {
      $protects = array_filter($protects, fn($v) => $v !== $type);
    }

    $manager->getData()->setNested("worldprotect.$worldName", $protects);
    $manager->getData()->save();
  }
}
