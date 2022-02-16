<?php

namespace api;

use \api\Api;
use \utils\Utils;
use \event\EventListener;

class Longpoll {

    public static $server,
    $key,
    $ts;

    public static function startPolling() {
        $poll = Api::callMethod('messages.getLongPollServer');
        self::$server = $poll['response']['server'];
        self::$ts = $poll['response']['ts'];
        self::$key = $poll['response']['key'];

        while (true) {
            $server = Utils::requestURL('https://'.self::$server.'?'.http_build_query([
                'act' => 'a_check',
                'key' => self::$key,
                'ts' => self::$ts,
                'wait' => 30,
                'mode' => 2,
                'version' => 3
            ]));
            self::$ts = $server['ts'];
            if (!isset($server['updates'])) return self::startPolling();
            foreach ($server['updates'] as $update) {
                if ($update[0] === 4) EventListener::sendNewEvent('message', [['text' => $update[5], 'peer_id' => isset($update[3]) ? $update[3] : $update[6], 'from_id' => isset($update[6]) ? (int)$update[6] : $update[3], 'message_id' => $update[1], 'attachments' => isset($update[7]) ? $update[7] : null]]);
                if ($update[0] === 8 && $update[1]) EventListener::sendNewEvent('friend_online', [['user_id' => $update[1]]]);
                if ($update[0] === 9 && $update[1]) EventListener::sendNewEvent('friend_offline', [['user_id' => $update[1]]]);
            }
        }
    }
}
