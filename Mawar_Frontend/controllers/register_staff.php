<?php
session_start();
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $_SESSION['error'] = 'Semua field harus diisi';
        header('Location: ../views/staff_register.php');
        exit();
    }

    if ($password !== $password_confirmation) {
        $_SESSION['error'] = 'Password tidak cocok';
        header('Location: ../views/staff_register.php');
        exit();
    }

    try {
        // Add your database insertion logic here
        // Example:
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'staff')");
        $stmt->execute([$name, $email, $phone, $hashed_password]);

        $_SESSION['success'] = 'Registrasi berhasil';
        header('Location: ../views/login.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Terjadi kesalahan saat registrasi';
        header('Location: ../views/staff_register.php');
        exit();
    }
}