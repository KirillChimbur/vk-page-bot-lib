<?php

require('vendor/autoload.php');

use \api\ {
    Api,
    Longpoll
};
use \utils\ {
    Logger,
    Colors
};
use \event\EventListener;

Api::$access_token = ''; // Токен. Получить можно на сайте — vkhost.github.io (Kate Mobile)
Api::$user_id = 0; // Айди пользователя

system('clear');
Logger::info('Бот успешно запустился!');

EventListener::listen('friend_online', function($update) {
    $user = Api::getUser(str_replace('-', '', $update['user_id']));
    Logger::debug(Colors::$GRAY.'['.Colors::$DARK_GRAY.'Handle Online'.Colors::$GRAY.']: '.Colors::$WHITE.'Друг "'.$user['first_name'].' '.$user['last_name'].'" стал онлайн');
});

EventListener::listen('friend_offline', function($update) {
    $user = Api::getUser(str_replace('-', '', $update['user_id']));
    Logger::debug(Colors::$GRAY.'['.Colors::$DARK_GRAY.'Handle Offline'.Colors::$GRAY.']: '.Colors::$WHITE.'Друг "'.$user['first_name'].' '.$user['last_name'].'" стал оффлайн');
});

EventListener::listen('message', function($msg) {
    if ($msg['text']) Logger::debug(Colors::$GRAY.'['.Colors::$DARK_GRAY.'Handle Message'.Colors::$GRAY.']: '.Colors::$WHITE.$msg['text']);
    if (strtolower($msg['text']) === '-push' && $msg['from_id'] === Api::$user_id) {
        EventListener::$stop = true;
        $users = '';
        foreach (Api::getChatMembers($msg)['response']['items'] as $user) {
            if ($user['member_id'] > 0) $users .= '@id'.$user['member_id'].' (a)';
        }
        Api::sendMessage($users, $msg);
        EventListener::$stop = false;
    }
    if (strtolower($msg['text']) === '-magick' && $msg['from_id'] === Api::$user_id) {
        EventListener::$stop = true;
        Api::editMessage(str_repeat('💣', 30), $msg);
        sleep(1);
        Api::editMessage('магия', $msg);
        sleep(1);
        Api::editMessage(str_repeat('❤💛💚💙💜', 50), $msg);
        sleep(1);
        Api::editMessage('крутая магия', $msg);
        sleep(1);
        Api::editMessage('.', $msg);
        EventListener::$stop = false;
    }
});
Longpoll::startPolling();
