<?php

class UbBindManager {

    public function getByUserChat($userId, $code) {
        $db = UbDbUtil::getPDO();
        $stmt = $db->prepare('SELECT * FROM userbot_bind WHERE id_user = :userId AND code = :code');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveOrUpdate($t) {
        $db = UbDbUtil::getPDO();
        $stmt = $db->prepare('INSERT INTO userbot_bind SET id_user = :id_user, code = :code, id_chat = :id_chat ON DUPLICATE KEY UPDATE id_chat = VALUES(id_chat)');
        $stmt->bindParam(':id_user', $t['id_user'], PDO::PARAM_INT);
        $stmt->bindParam(':code', $t['code'], PDO::PARAM_STR);
        $stmt->bindParam(':id_chat', $t['id_chat'], PDO::PARAM_INT);
        return $stmt->execute();
    }
}