<?php

require_once MODEL_PATH . '/UserModel.php';

class ProductController
{
    private function isClientGmailAccount($email)
    {
        $email = trim((string)$email);
        return preg_match('/@gmail\.com$/i', $email) === 1;
    }

    public function index()
    {
        $email = $_SESSION['user_id'] ?? null;
        $isLoggedIn = !empty($email);
        $isGmail = false;
        $user = null;

        if ($isLoggedIn) {
            $isGmail = $this->isClientGmailAccount($email);

            if (!$isGmail) {
                redirect(route('dashboard', 'index'));
            }

            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);
        }

        require VIEW_PATH . '/products_store.php';
    }
}
