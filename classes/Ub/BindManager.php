<?php

class UbBindManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = UbDbUtil::getPDO();
    }

    public function getByUserChat(int $userId, string $code): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM userbot_bind WHERE id_user = :userId AND code = :code'
        );
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function saveOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO userbot_bind (id_user, code, id_chat)
             VALUES (:id_user, :code, :id_chat)
             ON DUPLICATE KEY UPDATE id_chat = VALUES(id_chat)'
        );

        $stmt->bindValue(':id_user', $data['id_user'], PDO::PARAM_INT);
        $stmt->bindValue(':code', $data['code'], PDO::PARAM_STR);
        $stmt->bindValue(':id_chat', $data['id_chat'], PDO::PARAM_INT);

        return $stmt->execute();
    }
}
