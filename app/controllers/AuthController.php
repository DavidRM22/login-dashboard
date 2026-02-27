<?php

require_once MODEL_PATH . '/AuditModel.php';
require_once MODEL_PATH . '/UserModel.php';
require_once MODEL_PATH . '/OTPModel.php';
require_once APP_PATH . '/services/MailerService.php';

class AuthController
{
    public function login()
    {
        require VIEW_PATH . '/login.php';
    }

    public function doLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            echo "❌ Credenciales incorrectas<br>";
            echo "<a href='" . route('auth', 'login') . "'>Volver</a>";
            return;
        }

        $otpModel = new OTPModel();
        $otpCode = $otpModel->generate($email);

        $mailer = new MailerService();
        $otpSent = $mailer->sendOTP($email, (string)$otpCode);

        if (!$otpSent) {
            echo "❌ No se pudo enviar el OTP al correo. Intenta nuevamente.<br>";
            echo "<a href='" . route('auth', 'login') . "'>Volver</a>";

            $audit = new AuditModel();
            $audit->log('EVENT_FAILED_OTP_DELIVERY', $email, 'Error enviando OTP por correo en login');
            return;
        }

        $_SESSION['otp_email'] = $email;

        $audit = new AuditModel();
        $audit->log('EVENT_LOGIN_ATTEMPT', $email, 'Credenciales válidas, OTP enviado por correo');

        redirect(route('auth', 'verify'));
    }

    public function register()
    {
        require VIEW_PATH . '/register.php';
    }

    public function doRegister()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new UserModel();
        $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $audit = new AuditModel();
        $audit->log('EVENT_REGISTER', $email, 'Usuario registrado');

        $otpModel = new OTPModel();
        $otpCode = $otpModel->generate($email);

        $mailer = new MailerService();
        $otpSent = $mailer->sendOTP($email, (string)$otpCode);

        if (!$otpSent) {
            echo "❌ Usuario registrado, pero no se pudo enviar el OTP al correo.<br>";
            echo "<a href='" . route('auth', 'login') . "'>Ir al login</a>";
            $audit->log('EVENT_FAILED_OTP_DELIVERY', $email, 'Error enviando OTP por correo en registro');
            return;
        }

        $_SESSION['otp_email'] = $email;
        $audit->log('EVENT_OTP_SENT', $email, 'OTP enviado por correo');

        redirect(route('auth', 'verify'));
    }

    public function verify()
    {
        require VIEW_PATH . '/verify.php';
    }

    public function verifyOTP()
    {
        $code = $_POST['code'];
        $email = $_SESSION['otp_email'] ?? null;

        if (!$email) {
            die("Sesión expirada. Regístrese nuevamente.");
        }

        $otpModel = new OTPModel();
        $isValid = $otpModel->verify($email, $code);

        if (!$isValid) {
            echo "❌ Código incorrecto o expirado<br>";
            echo "<a href='" . route('auth', 'verify') . "'>Intentar de nuevo</a>";
            $audit = new AuditModel();
            $audit->log('EVENT_FAILED_OTP', $email, 'Código incorrecto o expirado');
            return;
        }

        $audit = new AuditModel();
        $audit->log('EVENT_LOGIN', $email, 'Login exitoso');

        $_SESSION['user_id'] = $email;
        unset($_SESSION['otp_email']);

        redirect(route('dashboard', 'index'));
    }
}
