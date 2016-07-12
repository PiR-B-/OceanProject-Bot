<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class UnDunce
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!$bot->botHasPower(158)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                sprintf('Sorry, but i don\'t have the power \'%s\'.', 'dunce'),
                $type
            );
        }

        if (!isset($message[1]) || empty($message[1])) {
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !undunce [regname/xatid]', $type, true);
        }

        if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
            $user = $bot->users[$message[1]];
        } else {
            foreach ($bot->users as $id => $object) {
                if (is_object($object)) {
                    if (strtolower($object->getRegname()) == strtolower($message[1])) {
                        $user = $object;
                        break;
                    }
                }
            }
        }

        if (isset($user)) {
            if (!$user->isDunced()) {
                return $bot->network->sendMessageAutoDetection($who, 'That user is not dunced.', $type);
            }

            $bot->network->ban($user->getID(), 0, $reason ?? '', 'gd');
        } else {
            $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
        }
    }
}
