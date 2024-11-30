<?php

namespace angga7togk\poweressentials\utils;

class ValidationUtils
{
  public static function isValidString($string): bool
  {
    return preg_match('/^[a-zA-Z0-9_]+$/', $string) === 1;
  }
}
