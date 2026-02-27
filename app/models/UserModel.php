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

        $data['id'] = uniqid();
        $data['created_at'] = date('Y-m-d H:i:s');

        $users[] = $data;

        file_put_contents($this->jsonFile, json_encode($users, JSON_PRETTY_PRINT));

        // --- GUARDAR EN MYSQL ---
        $db = Database::connect();

        $sql = "INSERT INTO users (id, name, email, password, created_at)
                VALUES (:id, :name, :email, :password, :created_at)";

        $stmt = $db->prepare($sql);
        $stmt->execute($data);
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
