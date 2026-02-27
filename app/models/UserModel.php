<?php

require_once MODEL_PATH . '/database.php';

class UserModel
{
    private $jsonFile;

    public function __construct()
    {
        $this->jsonFile = DATA_PATH . '/users.json';

        if (!file_exists($this->jsonFile)) {
            file_put_contents($this->jsonFile, json_encode([]));
        }
    }

    public function create($data)
    {
        // --- GUARDAR EN JSON ---
        $users = json_decode(file_get_contents($this->jsonFile), true);
        $record = [];

        $record['id'] = uniqid();
        $record['name'] = isset($data['full_name']) ? $data['full_name'] : ($data['name'] ?? '');
        $record['email'] = $data['email'] ?? '';
        $record['phone'] = $data['phone'] ?? '';
        $record['type'] = $data['type'] ?? '';
        $record['department'] = $data['department'] ?? '';
        $record['position'] = $data['position'] ?? '';
        $record['hired_at'] = $data['hired_at'] ?? '';
        $record['status'] = $data['status'] ?? '';
        $record['created_at'] = date('Y-m-d H:i:s');

        $users[] = $record;

        file_put_contents($this->jsonFile, json_encode($users, JSON_PRETTY_PRINT));

        // Intentar guardar en MySQL si la tabla existe, pero no romper si hay error
        try {
            $db = Database::connect();

            $sql = "INSERT INTO users (id, name, email, phone, type, department, position, hired_at, status, created_at)
                    VALUES (:id, :name, :email, :phone, :type, :department, :position, :hired_at, :status, :created_at)";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':id' => $record['id'],
                ':name' => $record['name'],
                ':email' => $record['email'],
                ':phone' => $record['phone'],
                ':type' => $record['type'],
                ':department' => $record['department'],
                ':position' => $record['position'],
                ':hired_at' => $record['hired_at'],
                ':status' => $record['status'],
                ':created_at' => $record['created_at']
            ]);
        } catch (Exception $e) {
            // registrar error en auditoría o ignorar para no romper flujo
        }
    }

    public function findByEmail($email)
    {
        $users = json_decode(file_get_contents($this->jsonFile), true);

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return null;
    }
}
