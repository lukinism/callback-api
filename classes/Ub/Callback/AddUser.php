<?php
require_once(CLASSES_PATH . "Ub/VkApi.php");
require_once(CLASSES_PATH . "Ub/BindManager.php");
#[AllowDynamicProperties] class UbCallbackAddUser implements UbCallbackAction {
	function execute($userId, $object, $userData): void
    {

        $this->bindManager = new UbBindManager();
        $this->vkApi = new UbVkApi($userData['token']);

		$code = $object['chat'];
		$chat = $this->bindManager->getByUserChat($userId, $code);

		if (!$chat) {
			UbUtil::echoJson(UbUtil::buildErrorResponse('error', 'no chat bind', UB_ERROR_NO_CHAT));
			return;
		}

		$res = $this->vkApi->messagesAddChatUser($object['user_id'], $chat['id_chat']);

        if (isset($res['error'])) {
			$peerId = UbVkApi::chat2PeerId($chat['id_chat']);
			$error = UbUtil::getVkErrorText($res['error']);
			$this->vkApi->messagesSend($peerId, UB_ICON_WARN . ' ' . $error);
		}

		echo 'ok';
	}
}