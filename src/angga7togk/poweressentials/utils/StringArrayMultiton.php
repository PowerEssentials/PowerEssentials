<?php

declare(strict_types=1);

namespace angga7togk\poweressentials\utils;

/** Credit Code: https://github.com/fuyutsuki/Texter/blob/122f9b45a4896c51eb5b7f4fc0aa479ea0df56a7/src/jp/mcbe/fuyutsuki/Texter/util/StringArrayMultiton.php */
trait StringArrayMultiton
{

  /** @var static[] */
  protected static array $instances = [];

  final public function __construct(string $key)
  {
    static::$instances[$key] = $this;
  }

  final public static function getInstance(string $key): ?static
  {
    return static::$instances[$key] ?? null;
  }

  final public static function removeInstance(string $key): void
  {
    unset(static::$instances[$key]);
  }
}
