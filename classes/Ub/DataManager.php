<?php

class UbDataManager {

	function get($userId) {
        $db = UbDbUtil::getPDO();
		$stmt = $db->prepare('SELECT * FROM userbot_data WHERE id_user = :userId');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getByIdSecret($userId, $secret) {
        $db = UbDbUtil::getPDO();
		$stmt = $db->prepare('SELECT * FROM userbot_data WHERE id_user = :id_user AND secret = :secret');
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':secret', $secret, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}

