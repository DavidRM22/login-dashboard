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
        // obtener todos los empleados para listarlos en el dashboard
        $allUsersRaw = json_decode(file_get_contents(DATA_PATH . '/users.json'), true);

        // leer filtros desde GET
        $search = trim($_GET['search'] ?? '');
        $typeFilter = trim($_GET['type'] ?? '');
        $statusFilter = trim($_GET['status'] ?? '');

        // aplicar filtros (si existen)
        $allUsers = array_values(array_filter($allUsersRaw, function ($u) use ($search, $typeFilter, $statusFilter) {
            // búsqueda libre sobre nombre, email y puesto
            if ($search !== '') {
                $hay = false;
                $needle = mb_strtolower($search);
                foreach (['name', 'email', 'position'] as $field) {
                    if (!empty($u[$field]) && mb_strpos(mb_strtolower($u[$field]), $needle) !== false) {
                        $hay = true;
                        break;
                    }
                }
                if (!$hay) return false;
            }

            if ($typeFilter !== '' && (!isset($u['type']) || $u['type'] !== $typeFilter)) {
                return false;
            }

            if ($statusFilter !== '' && (!isset($u['status']) || $u['status'] !== $statusFilter)) {
                return false;
            }

            return true;
        }));

        // 3. Cargar vista
        require VIEW_PATH . '/dashboard.php';
    }


    public function addEmployee()
    {
        authRequired();
        require VIEW_PATH . '/add_employee.php';
    }

    public function saveEmployee()
    {
        authRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(route('dashboard', 'index'));
        }

        $payload = [];
        $payload['full_name'] = trim($_POST['full_name'] ?? '');
        $payload['email'] = trim($_POST['email'] ?? '');
        $payload['phone'] = trim($_POST['phone'] ?? '');
        $payload['type'] = trim($_POST['type'] ?? '');
        $payload['department'] = trim($_POST['department'] ?? '');
        $payload['position'] = trim($_POST['position'] ?? '');
        $payload['hired_at'] = trim($_POST['hired_at'] ?? '');
        $payload['status'] = trim($_POST['status'] ?? '');

        $userModel = new UserModel();
        $userModel->create($payload);

        redirect(route('dashboard', 'index'));
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
