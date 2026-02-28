<?php

require_once MODEL_PATH . '/UserModel.php';
require_once MODEL_PATH . '/AuditModel.php';

class DashboardController
{
    private function enforcePasswordUpdated()
    {
        $email = $_SESSION['user_id'] ?? null;
        if (!$email) {
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!empty($user['must_change_password'])) {
            redirect(route('auth', 'changePasswordRequired'));
        }
    }

    private function generateTemporaryPassword()
    {
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $special = '!@#$%^&*';

        $requiredChars = [
            $lower[random_int(0, strlen($lower) - 1)],
            $upper[random_int(0, strlen($upper) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        $all = $lower . $upper . $digits . $special;
        for ($i = 0; $i < 4; $i++) {
            $requiredChars[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($requiredChars);

        return 'TechSkills' . implode('', $requiredChars);
    }

    public function index()
    {
        authRequired();
        $this->enforcePasswordUpdated();

        $email = $_SESSION['user_id'];

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        $allUsersRaw = json_decode(file_get_contents(DATA_PATH . '/users.json'), true);

        $search = trim($_GET['search'] ?? '');
        $typeFilter = trim($_GET['type'] ?? '');
        $statusFilter = trim($_GET['status'] ?? '');

        $allUsers = array_values(array_filter($allUsersRaw, function ($u) use ($search, $typeFilter, $statusFilter) {
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

        require VIEW_PATH . '/dashboard.php';
    }


    public function addEmployee()
    {
        authRequired();
        $this->enforcePasswordUpdated();
        require VIEW_PATH . '/add_employee.php';
    }

    public function saveEmployee()
    {
        authRequired();
        $this->enforcePasswordUpdated();

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
        $payload['password'] = $this->generateTemporaryPassword();
        $payload['must_change_password'] = true;

        $userModel = new UserModel();
        $userModel->create($payload);

        $_SESSION['employee_temp_password_message'] = 'Empleado creado exitosamente. Contraseña temporal: ' . $payload['password'] . '. Guarde esta información.';

        redirect(route('dashboard', 'addEmployee'));
    }

    public function logout()
    {
        session_destroy();
        redirect(route('auth', 'login'));
    }

    public function audit()
    {
        authRequired();
        $this->enforcePasswordUpdated();

        $auditModel = new AuditModel();
        $logs = json_decode(file_get_contents(DATA_PATH . '/audit.json'), true);

        require VIEW_PATH . '/audit.php';
    }
}
