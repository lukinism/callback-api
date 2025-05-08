<?php

require_once(CLASSES_PATH . "Ub/VkApi.php");
require_once(CLASSES_PATH . "Ub/BindManager.php");

class UbCallbackAddUser implements UbCallbackAction
{
    private UbBindManager $bindManager;
    private UbVkApi $vkApi;

    public function execute(int $userId, array $object, array $userData): void
    {
        $this->bindManager = new UbBindManager();
        $this->vkApi = new UbVkApi($userData['token']);

        $code = $object['chat'];
        $chat = $this->bindManager->getByUserChat($userId, $code);

        if (!$chat) {
            UbUtil::echoError('no chat bind', UB_ERROR_NO_CHAT);
            return;
        }

        $res = $this->vkApi->messagesAddChatUser($object['user_id'], $chat['id_chat']);

        if (isset($res['error'])) {
            $peerId = UbVkApi::chat2PeerId($chat['id_chat']);
            $error = UbUtil::getVkErrorText($res['error']);
            $this->vkApi->messagesSend($peerId, UbIcons::WARN . ' ' . $error);
        }

        echo 'ok';
    }
}
