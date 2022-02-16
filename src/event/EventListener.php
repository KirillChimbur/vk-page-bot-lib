<?php

namespace event;

use \utils\Utils;

class EventListener {

    public static $stop = false,
    $events = [];

    public static function listen($event, $function) {
        self::$events[$event] = $function;
    }

    public static function sendNewEvent($event, $params = []) {
        if (!isset(self::$events[$event])) return;
        if (self::$stop) return;
        usleep(1);
        call_user_func_array(self::$events[$event], $params);
    }
}
