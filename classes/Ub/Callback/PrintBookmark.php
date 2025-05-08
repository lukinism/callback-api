<?php
require_once(CLASSES_PATH . "Ub/BindManager.php");
#[AllowDynamicProperties] class UbCallbackPrintBookmark implements UbCallbackAction {
	function execute($userId, $object, $userData): void
    {
        $this->bindManager = new UbBindManager();
		$chat = $this->bindManager->getByUserChat($userId, $object['chat']);
		if (!$chat) {
			UbUtil::echoError('no chat bind', UB_ERROR_NO_CHAT);
			return;
		}

		$peerId = UbVkApi::chat2PeerId($chat['id_chat']);
		$vk = new UbVkApi($userData['token']);
		$message = $vk->messagesGetByConversationMessageId($peerId, [$object['conversation_message_id']]);
		if (isset($message['error'])) {
			$e = $message['error'];
			$res = $vk->messagesSend($peerId, UbIcons::WARN . ' Ошибка ВК: ' . $e['error_msg'] . ' (' . $e['error_code'] . ')');
			return;
		}
		$messages = $message['response']['items'];

		if (sizeof($messages)) {
			$resMessage = '🔼 Перейти к закладке «' . $object['description'] . '»';
			$message = $vk->messagesSend($peerId, $resMessage, ['reply_to' => $messages[0]['id']]);
			if (isset($message['error'])) { // ошибка при отправлении в ВК
				$e = $message['error'];

				$msg = UbIcons::WARN . " Закладка недоступна. Удаляю.";
                $msg .= match ($e['error_code']) {
                    100 => "\n Скорее всего сменился юзербот (100)",
                    default => "\nОшибка ВК: " . $e['error_msg'] . ' (' . $e['error_code'] . ')',
                };
				$res = $vk->messagesSend($peerId, $msg);
			}
		}
		echo 'ok';
	}

}