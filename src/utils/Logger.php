<?php

namespace utils;

use \utils\Colors;

class Logger {

    public static function info(string $message) : void {
        date_default_timezone_set('Europe/Moscow');
        echo Colors::$GRAY.'['.Colors::$AQUA.'INFO'.Colors::$GRAY.'] '.Colors::$GRAY.'['.Colors::$WHITE.date('H:i:s', time()).Colors::$GRAY.']: '.Colors::$WHITE.$message.Colors::$RESET.PHP_EOL;
    }

    public static function debug(string $message) : void {
        date_default_timezone_set('Europe/Moscow');
        echo Colors::$GRAY.'['.Colors::$DARK_GRAY.'DEBUG'.Colors::$GRAY.'] '.Colors::$GRAY.'['.Colors::$WHITE.date('H:i:s', time()).Colors::$GRAY.']: '.Colors::$WHITE.$message.Colors::$RESET.PHP_EOL;
    }
}
