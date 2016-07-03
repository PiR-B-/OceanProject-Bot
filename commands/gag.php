<?php

$gag = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !gag [regname/xatid] [reason]', $type);
	}

	if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
		$user = $bot->users[$message[1]];
	} else {
		foreach($bot->users as $id => $object) {
			if (is_object($object)) {
				if (strtolower($object->getRegname()) == strtolower($message[1])) {
					$user = $object;
					break;
				}
			}
		}
	}

	if (isset($user)) {

		if (isset($message[2])) {

			unset($message[0]);
			unset($message[1]);

			$reason = implode(' ', $message);
		}

		$bot->network->gag($user->getID(), 1, (!isset($reason) ? 'No reason' : $reason));
	} else {
		$bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
	}
};