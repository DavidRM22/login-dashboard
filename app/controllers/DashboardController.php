<?php

require_once MODEL_PATH . '/UserModel.php';
require_once MODEL_PATH . '/AuditModel.php';

class DashboardController
{
    public function index()
    {
        // 1. Proteger ruta
        authRequired();

        // 2. Obtener usuario
        $email = $_SESSION['user_id'];

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        // 3. Cargar vista
        require VIEW_PATH . '/dashboard.php';
    }


    public function addEmployee()
    {
        authRequired();
        require VIEW_PATH . '/add_employee.php';
    }

    public function logout()
    {
        session_destroy();
        redirect(route('auth', 'login'));
    }

    public function audit()
    {
    authRequired();

    $auditModel = new AuditModel();
    $logs = json_decode(file_get_contents(DATA_PATH . '/audit.json'), true);

    require VIEW_PATH . '/audit.php';
    }

}
