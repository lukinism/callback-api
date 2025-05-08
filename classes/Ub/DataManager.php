<?php

class UbDataManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = UbDbUtil::getPDO();
    }

    public function get(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM userbot_data WHERE id_user = :userId');
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByIdSecret(int $userId, string $secret): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM userbot_data WHERE id_user = :id_user AND secret = :secret'
        );
        $stmt->bindValue(':id_user', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':secret', $secret, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
