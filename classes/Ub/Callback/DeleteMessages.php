<?php
require_once(CLASSES_PATH . "Ub/BindManager.php");
#[AllowDynamicProperties] class UbCallbackDeleteMessages implements UbCallbackAction {
	function execute($userId, $object, $userData): void{

        $this->vkApi = new UbVkApi($userData['token']);
        $this->bindManager = new UbBindManager();

        $code = $object['chat'];
        $chat = $this->bindManager->getByUserChat($userId, $code);

		if (!$chat) {
			UbUtil::echoJson(UbUtil::buildErrorResponse('error', 'no chat bind', UB_ERROR_NO_CHAT));
            exit('ok');
		}

		$localIds = $object['local_ids'];

		if (!count($localIds)) {
            exit('ok');
		}

		$chatId = $chat['id_chat'];
		$messages = $this->vkApi->messagesGetByConversationMessageId(UbVkApi::chat2PeerId($chatId), $localIds);

        if (isset($messages['error'])) {
			$error = UbUtil::getVkErrorText($messages['error']);
			$this->vkApi->chatMessage($chatId, UB_ICON_WARN . ' ' . $error);
            exit('ok');
		}

		$messages = $messages['response']['items'];
		$ids = [];
		foreach ($messages as $m)
			$ids[] = $m['id'];

		if (!count($ids)) {
			exit('ok');
		}

        $res = $this->vkApi->messagesDelete($ids, true);

		if (isset($res['error'])) {
			$error = UbUtil::getVkErrorText($res['error']);
			$this->vkApi->chatMessage($chatId, UB_ICON_WARN . ' ' . $error);
            exit('ok');
		}

		$this->vkApi->chatMessage($chatId, UB_ICON_SUCCESS . ' Сообщения удалены');
		echo 'ok';
	}
}