<?php
require_once(CLASSES_PATH . "Ub/BindManager.php");
class UbCallbackBind implements UbCallbackAction {
    private UbBindManager $bindManager;
    private UbVkApi $vkApi;
	function execute($userId, $object, $userData): void
    {

        $this->vkApi = new UbVkApi($userData['token']);
        $this->bindManager = new UbBindManager();

        $chat = $this->bindManager->getByUserChat($userId, $object['chat']);

        $result = $this->vkApi->messagesGetConversations();
        if (isset($result['error'])) {
            UbUtil::echoErrorVkResponse($result['error']);
            return;
        }
        $result = $result['response'];
        $goodChats = self::findChats($result['items'], $object);
        $userChatId = 0;
        if ($goodChats['sure']) {
            $userChatId = UbVkApi::peer2ChatId($goodChats['items'][0]['peer_id']);
        } else {
            foreach ($goodChats['items'] as $chat) {
                $result = $this->vkApi->messagesGetHistory($chat['peer_id'], 0, 100);

                if (isset($result['error'])) {
                    UbUtil::echoErrorVkResponse($result['error']);
                    return;
                }

                foreach ($result['response']['items'] as $item) {
                    if (self::isMessagesEqual($item, $object)) {
                        $userChatId = UbVkApi::peer2ChatId($item['peer_id']);
                    }
                }

                if ($userChatId) {
                    break;
                }
            }
        }

        if ($userChatId) {
            $t = [
                'id_user' => $userId,
                'code' => $object['chat'],
                'id_chat' => $userChatId
            ];

            $this->bindManager->saveOrUpdate($t);
            $this->vkApi->chatMessage($userChatId, UB_ICON_SUCCESS . ' Беседа распознана');
            echo 'ok';
        } else {
            UbUtil::echoError('no chat id', UB_ERROR_CANT_BIND_CHAT);
        }
    }

    private static function findChats($items, $vkMessage): array
    {
        $goodChats = [];

        foreach ($items as $item) {
            $lm = $item['last_message'];
            $sLocal = $lm['conversation_message_id'];

            if ($sLocal > $vkMessage['conversation_message_id'] - 300 && $sLocal < $vkMessage['conversation_message_id'] + 300) {
                if ($vkMessage['from_id'] == $lm['from_id'] && $vkMessage['conversation_message_id'] == $sLocal && $lm['text'] == $vkMessage['text']) {
                    return [
                        'sure' => 1,
                        'items' => [$item['last_message']]
                    ];
                }

                $goodChats[] = $item['last_message'];
            }
        }

        return [
            'sure' => 0,
            'items' => $goodChats
        ];
    }


	private static function isMessagesEqual($m1, $m2): bool
    {
		return ($m1['from_id'] == $m2['from_id'] && $m1['conversation_message_id'] == $m2['conversation_message_id'] && $m1['text'] == $m2['text']);
	}

}