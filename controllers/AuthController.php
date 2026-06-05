<?php

class AuthController extends Controller
{
    public function login()
    {
        $this->view('admin/login');
    }

public function comprobar()
{
    require_once '../models/Admin.php';

    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($usuario) || empty($password)) {
        $this->view('admin/login', [
            'error' => 'Introduce usuario y contraseña'
        ]);
        return;
    }
    
    $adminModel = new Admin();
    $admin = $adminModel->buscarPorUsuario($usuario);

    if ($admin && password_verify($password, $admin['password'])) {
        
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nombre'] = $admin['nombre'];

        header('Location: ' . BASE_URL . 'admin');
        exit;
    }

    $this->view('admin/login', [
        'error' => 'Usuario o contraseña incorrectos'
    ]);
}

    public function logout()
{
    session_destroy();
    header('Location: ' . BASE_URL . 'login');
    exit;
}
}