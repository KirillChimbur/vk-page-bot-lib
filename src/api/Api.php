<?php

namespace api;

use \utils\Utils;

class Api {
    public static $access_token, $user_id;

    public static function callMethod($method, $params = []) {
        $params['access_token'] = self::$access_token;
        $params['v'] = '5.131';
        return Utils::requestURL('https://api.vk.com/method/'.$method.'?'.http_build_query($params));
    }
    public static function sendMessage($message, $update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        $params['message'] = $message;
        $params['random_id'] = 0;
        return self::callMethod('messages.send', $params);
    }
    public static function editMessage($message, $update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        $params['message'] = $message;
        $params['message_id'] = $update['message_id'];
        $params['random_id'] = 0;
        return self::callMethod('messages.edit', $params);
    }
    public static function getChatMembers($update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        return self::callMethod('messages.getConversationMembers', $params);
    }
    public static function getUser($user_id, $params = []) {
        $params['user_id'] = $user_id;
        return self::callMethod('users.get', $params)['response'][0];
    }
}
