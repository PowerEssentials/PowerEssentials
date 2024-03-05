<?php

namespace angga7togk\poweressentials\message;

use angga7togk\poweressentials\PowerEssentials;

class Message{
    public static function getMessage():array{
        return PowerEssentials::$messages;
    }
}
